<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produk;
use Illuminate\Support\Facades\Storage;

class ProdukController extends Controller
{
    public function index()
    {
        $produks = \App\Models\Produk::all();
        return view('produk.index', compact('produks'));
    }

    public function create()
    {
        return view('produk.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_produk' => 'required|string|max:255',
            'harga_satuan' => 'required|numeric',
            'stok' => 'required|integer',
            'deskripsi' => 'nullable|string',
            'gambar' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('gambar')) {
            $path = $request->file('gambar')->store('produk_gambar', 'public');
            $validated['gambar'] = $path;
        }

        \App\Models\Produk::create($validated);

        return redirect()->route('produk.index')->with('success', 'Produk berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $produks = Produk::findOrFail($id);
        
        return view('produk.edit', compact('produks'));
    }

    public function update(Request $request, $id)
    {
        $produk = Produk::findOrFail($id);

        $request->validate([
            'nama_produk'   => 'required|string|max:255',
            'stok'          => 'required|integer|min:0',
            'harga_satuan'  => 'required|numeric|min:0',
            'deskripsi'     => 'nullable|string',
            'gambar'        => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $data = [
            'nama_produk'  => $request->nama_produk,
            'stok'         => $request->stok,
            'harga_satuan' => $request->harga_satuan,
            'deskripsi'    => $request->deskripsi,
        ];

        if ($request->hasFile('gambar')) {
            if ($produk->gambar) {
                Storage::disk('public')->delete($produk->gambar);
            }

            $path = $request->file('gambar')->store('produk', 'public');
            $data['gambar'] = $path;
        }

        $produk->update($data);

        return redirect()->route('produk.index')->with('success', 'Produk berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $produk = Produk::findOrFail($id);

        if ($produk->gambar) {
            Storage::disk('public')->delete($produk->gambar);
        }

        $produk->delete();

        return redirect()->route('produk.index')->with('success', 'Produk berhasil dihapus!');
    }

    public function show($id)
    {
        $produk = Produk::findOrFail($id);
        
        return view('produk.show', compact('produk'));
    }
}
