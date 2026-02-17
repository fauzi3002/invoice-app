<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Struk;
use App\Models\Produk;
use App\Models\StrukItem;
use App\Models\PengaturanToko;
use App\Exports\StrukExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;


class StrukController extends Controller
{
    public function index()
    {
        $struks = Struk::all();
        $produk = Produk::all();
        return view('buat_struk.index', compact('produk', 'struks'));
    }

    public function create()
    {
        $struks = Struk::all();
        $produk = Produk::all();
        return view('buat_struk.create', compact('produk', 'struks'));
    }

public function show($id)
{
    $struk = Struk::with(['items.produk'])->findOrFail($id);
    // Mengambil data pertama, jika tidak ada tetap kirim objek kosong agar tidak error property on null
    $toko = PengaturanToko::first() ?? new PengaturanToko(); 

    return view('buat_struk.show', compact('struk', 'toko'));
}

    public function destroy($id)
    {
        $struk = Struk::findOrFail($id);
        $struk->items()->delete(); // Hapus item terkait terlebih dahulu
        $struk->delete();

        return redirect()->route('buat_struk.index')
                         ->with('success', 'Struk berhasil dihapus!');
    }

    public function store(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'customer_name'  => 'required|string|max:255',
            'customer_phone' => 'nullable|string|max:20',
            'customer_address' => 'nullable|string',
            'status'         => 'required|in:pending,partial,lunas',
            'amount_paid'    => 'required|numeric|min:0',
            'items'          => 'required|array',
        ]);

        // 2. Filter item yang qty-nya lebih dari 0
        $selectedItems = collect($request->items)->filter(function($item) {
            return $item['qty'] > 0;
        });

        if ($selectedItems->isEmpty()) {
            return back()->with('error', 'Pilih minimal satu produk dengan jumlah yang benar.');
        }

        

        try {
            // Mulai Transaksi Database
            DB::beginTransaction();

            // 3. Hitung Total Harga dari Items
            $totalHarga = $selectedItems->sum(function($item) {
                return $item['price'] * $item['qty'];
            });

            // 4. Hitung Sisa Tagihan
            $jumlahBayar = $request->amount_paid;
            $sisaTagihan = $totalHarga - $jumlahBayar;

            // 5. Simpan Header Struk
            $struk = Struk::create([
                'nama_pelanggan'    => $request->customer_name,
                'no_telepon'        => $request->customer_phone,
                'alamat'            => $request->customer_address,
                'total_harga'       => $totalHarga,
                'jumlah_bayar'      => $jumlahBayar,
                'sisa_tagihan'      => max(0, $sisaTagihan), // Pastikan tidak negatif
                'status_pembayaran' => $request->status,
            ]);

            foreach ($selectedItems as $produkId => $detail) {
                // A. Cari Produk & Cek Stok
                $produk = Produk::find($produkId); // Pastikan $produkId adalah ID yang benar dari DB

                if (!$produk) {
                    throw new \Exception("Produk dengan ID {$produkId} tidak ditemukan.");
                }

                if ($produk->stok < $detail['qty']) {
                    throw new \Exception("Stok untuk {$produk->nama_produk} tidak mencukupi (Tersisa: {$produk->stok}).");
                }

                // B. Kurangi Stok
                $produk->decrement('stok', $detail['qty']);

                // C. Simpan Detail Item
                $subtotal = $detail['price'] * $detail['qty'];
                StrukItem::create([
                    'struk_id'  => $struk->id,
                    'produk_id' => $produkId,
                    'harga'     => $detail['price'],
                    'qty'       => $detail['qty'],
                    'subtotal'  => $subtotal,
                ]);
            }


            // Jika semua berhasil, simpan permanen
            DB::commit();

            return redirect()->route('buat_struk.index')
                             ->with('success', 'Struk berhasil dibuat!');

        } catch (\Exception $e) {
            // Jika ada error, batalkan semua perubahan database
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $struk = Struk::with('items.produk')->findOrFail($id);
        $produk = Produk::all();

        return view('buat_struk.edit', compact('struk', 'produk'));
    }

    
    public function update(Request $request, $id)
{
    $struk = Struk::with('items')->findOrFail($id);

    // ❌ Jika lunas → stop
    if ($struk->status_pembayaran === 'lunas') {
        return back()->with('error', 'Struk lunas tidak bisa diubah.');
    }

    DB::beginTransaction();

    try {

        // ====================================================
        // 🟡 CASE 1: STATUS PENDING → BOLEH EDIT ITEM
        // ====================================================
        if ($struk->status_pembayaran === 'pending') {

            $selectedItems = collect($request->items)
                ->filter(fn($item) => $item['qty'] > 0);

            if ($selectedItems->isEmpty()) {
                throw new \Exception("Minimal 1 produk dipilih.");
            }

            // 1️⃣ Kembalikan stok lama
            foreach ($struk->items as $item) {
                $item->produk->increment('stok', $item->qty);
            }

            // 2️⃣ Hapus item lama
            $struk->items()->delete();

            // 3️⃣ Hitung total baru
            $totalHarga = 0;

            foreach ($selectedItems as $produkId => $detail) {

                $produk = Produk::findOrFail($produkId);

                if ($produk->stok < $detail['qty']) {
                    throw new \Exception("Stok {$produk->nama_produk} tidak cukup.");
                }

                $subtotal = $detail['price'] * $detail['qty'];
                $totalHarga += $subtotal;

                StrukItem::create([
                    'struk_id'  => $struk->id,
                    'produk_id' => $produkId,
                    'harga'     => $detail['price'],
                    'qty'       => $detail['qty'],
                    'subtotal'  => $subtotal,
                ]);

                $produk->decrement('stok', $detail['qty']);
            }

            $jumlahBayar = $request->jumlah_bayar ?? 0;

$sisaTagihan = $totalHarga - $jumlahBayar;

if ($jumlahBayar <= 0) {
    $status = 'pending';
} elseif ($jumlahBayar < $totalHarga) {
    $status = 'partial';
} else {
    $status = 'lunas';
    $jumlahBayar = $totalHarga; // biar tidak lebih
    $sisaTagihan = 0;
}

$struk->update([
    'total_harga'       => $totalHarga,
    'jumlah_bayar'      => $jumlahBayar,
    'sisa_tagihan'      => $sisaTagihan,
    'status_pembayaran' => $status
]);

        }

        // ====================================================
        // 🟢 CASE 2: STATUS PARTIAL → HANYA UPDATE PEMBAYARAN
        // ====================================================
        if ($struk->status_pembayaran === 'partial') {

    $tambahBayar = (int) $request->tambah_bayar;
    $totalHarga = $struk->total_harga;

    // Tambahkan ke jumlah lama
    $jumlahBayarBaru = $struk->jumlah_bayar + $tambahBayar;

    // Hitung sisa
    $sisa = $totalHarga - $jumlahBayarBaru;

    if ($sisa <= 0) {
        $status = 'lunas';
        $sisa = 0;
        $jumlahBayarBaru = $totalHarga; // Supaya tidak lebih dari total
    } else {
        $status = 'partial';
    }

    $struk->update([
        'jumlah_bayar' => $jumlahBayarBaru,
        'sisa_tagihan' => $sisa,
        'status_pembayaran' => $status,
    ]);
}


        DB::commit();

        return redirect()->route('buat_struk.index')
            ->with('success', 'Struk berhasil diperbarui.');

    } catch (\Exception $e) {

        DB::rollBack();
        return back()->with('error', $e->getMessage());
    }
}

public function exportExcel($id)
{
    $struk = Struk::with('items.produk')->findOrFail($id);
    $toko = PengaturanToko::first(); // Sesuaikan cara ambil data toko Anda

    return Excel::download(new StrukExport($struk, $toko), 'Invoice-'.$struk->id.'.xlsx');
}



}
