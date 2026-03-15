<?php

namespace App\Http\Controllers;

use App\Models\PengaturanToko;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PengaturanTokoController extends Controller
{
    /**
     * Tampilkan halaman pengaturan toko
     */
    public function index()
{
    // Mengambil data pertama, jika tidak ada, buat objek kosong baru (bukan null)
    $toko = PengaturanToko::first() ?? new PengaturanToko;

    return view('pengaturan_toko.index', compact('toko'));
}

    /**
     * Simpan / Update pengaturan toko
     */
    public function update(Request $request)
{
    $request->validate([
        'nama_toko'        => 'required|string|max:255',
        'email'            => 'nullable|email',
        'no_telepon'       => 'nullable|string|max:20',
        'nama_bank'        => 'nullable|string|max:100',
        'pemilik_rekening' => 'nullable|string|max:100',
        'no_rekening'      => 'nullable|string|max:50',
        'logo_toko'        => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        'alamat_toko'      => 'nullable|string|max:500',
        'tanda_tangan'     => 'nullable|string', // Validasi input base64
    ]);

    $pengaturan = PengaturanToko::first();

    $data = $request->only([
        'nama_toko',
        'email',
        'no_telepon',
        'nama_bank',
        'pemilik_rekening',
        'no_rekening',
        'alamat_toko',
    ]);

    // 1. PROSES UPLOAD LOGO (File Upload Biasa)
    if ($request->hasFile('logo_toko')) {
        if ($pengaturan && $pengaturan->logo_toko) {
            Storage::disk('public')->delete($pengaturan->logo_toko);
        }
        $data['logo_toko'] = $request->file('logo_toko')->store('logo-toko', 'public');
    }

    // 2. PROSES TANDA TANGAN (Base64 Decoder)
    if ($request->filled('tanda_tangan')) {
        // Hapus tanda tangan lama jika ada
        if ($pengaturan && $pengaturan->tanda_tangan) {
            Storage::disk('public')->delete($pengaturan->tanda_tangan);
        }

        $image64 = $request->tanda_tangan; // Ambil string base64
        
        // Bersihkan string (hapus "data:image/png;base64,")
        $replace = substr($image64, 0, strpos($image64, ',') + 1); 
        $image = str_replace($replace, '', $image64); 
        $image = str_replace(' ', '+', $image); 
        
        // Buat nama file unik
        $imageName = 'tanda-tangan/' . Str::random(20) . '.png';

        // Simpan ke storage public
        Storage::disk('public')->put($imageName, base64_decode($image));

        // Masukkan path ke array data untuk disimpan di DB
        $data['tanda_tangan'] = $imageName;
    }

    // Update atau Create
    PengaturanToko::updateOrCreate(
        ['id' => optional($pengaturan)->id],
        $data
    );

    return redirect()->back()->with('success', 'Pengaturan toko berhasil disimpan');
}
}
