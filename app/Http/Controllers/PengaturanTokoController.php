<?php

namespace App\Http\Controllers;

use App\Models\PengaturanToko;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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

        // upload logo jika ada
        if ($request->hasFile('logo_toko')) {

            // hapus logo lama
            if ($pengaturan && $pengaturan->logo_toko) {
                Storage::disk('public')->delete($pengaturan->logo_toko);
            }

            $data['logo_toko'] = $request->file('logo_toko')
                ->store('logo-toko', 'public');
        }

        // create atau update
        PengaturanToko::updateOrCreate(
            ['id' => optional($pengaturan)->id],
            $data
        );

        return redirect()->back()->with('success', 'Pengaturan toko berhasil disimpan');
    }
}
