@extends('layouts.app')

@section('content')
<div class="pb-24" x-data="{ openModal: false, deleteUrl: '', productName: '' }">

    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Detail Produk</h2>
            <p class="text-sm text-gray-500 mt-1">
                Informasi lengkap mengenai aset dibawah ini
            </p>
        </div>

        <div class="justify-end flex flex-col md:flex-row gap-4 text-center">
            <a href="{{ route('produk.index') }}" wire:navigate class="px-6 py-3 bg-gray-100 text-gray-700 rounded-lg font-semibold hover:bg-gray-300 transition active:scale-95">
                Kembali ke daftar Produk
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        {{-- Gambar Produk --}}
        <div>
            <div class="bg-white p-2 rounded-3xl shadow-xl border border-gray-100">
                <div class="aspect-square rounded-2xl overflow-hidden bg-gray-50 border">
                    @if ($produk->gambar)
                        <img
                            src="{{ asset('storage/' . $produk->gambar) }}"
                            alt="{{ $produk->nama_produk }}"
                            class="w-full h-full object-cover"
                        >
                    @else
                        <div class="w-full h-full flex flex-col items-center justify-center text-gray-300">
                            <svg class="w-20 h-20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                      d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.581-1.581a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <span class="text-xs mt-2 font-bold tracking-widest uppercase">No Image</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Detail Produk --}}
        <div class="md:col-span-2">
            <div class="bg-white rounded-3xl shadow-xl border border-gray-100 p-6 md:p-8 space-y-8">

                {{-- Identitas --}}
                <div class="flex justify-between items-start">
                    <div>
                        <h1 class="text-xl font-bold text-gray-800">
                            {{ $produk->nama_produk }}
                        </h1>
                        <p class="text-sm text-gray-400">
                            ID Produk: #{{ $produk->id }}
                        </p>
                    </div>

                    <span class="px-4 py-2 rounded-2xl text-xs font-black uppercase
                        {{ $produk->stok > 0
                            ? 'bg-green-100 text-green-700'
                            : 'bg-red-100 text-red-700' }}">
                        {{ $produk->stok > 0 ? 'In Stock' : 'Out of Stock' }}
                    </span>
                </div>

                {{-- Informasi Harga & Stok --}}
                <div class="grid grid-cols-2 mt-4 gap-4 items-center justify-center text-center">
                    <div class="info-box">
                        <p class="info-label">Harga Satuan (Rp)</p>
                        <p class="info-value text-blue-900">
                            Rp {{ number_format($produk->harga_satuan, 0, ',', '.') }}
                        </p>
                    </div>

                    <div class="info-box">
                        <p class="info-label">Total Stok</p>
                        <p class="info-value">
                            {{ $produk->stok }}
                            <span class="text-sm font-medium text-gray-400">Unit</span>
                        </p>
                    </div>
                </div>

                {{-- Deskripsi --}}
                <div class="mt-6 border-t p-2 border-gray-500">
                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mb-3">
                        Deskripsi Produk
                    </p>
                    <p class="text-gray-600 leading-relaxed italic">
                        {{ $produk->deskripsi ?? 'Tidak ada deskripsi untuk produk ini.' }}
                    </p>
                </div>
            </div>
            <div class="justify-between flex gap-2 p-4">
                <a href="{{ route('produk.edit', $produk->id) }}" class="flex-1 bg-white text-center text-gray-700 px-3 py-2 rounded-lg text-xs font-bold hover:bg-gray-100 transition active:scale-95 border border-gray-200">
                    Edit
                </a>
                <button @click="openModal = true;
                            deleteUrl = '{{ route('produk.destroy', $produk->id) }}';
                            productName = '{{ $produk->nama_produk }}'"
                         class=
                            "flex-1 bg-red-50 text-red-600 text-center px-3 py-2 rounded-lg text-xs font-bold hover:bg-red-100 transition active:scale-95 border border-red-100">Hapus</button>
            </div>
        </div>
    </div>

    <!-- MODAL -->
        <div x-show="openModal"
             x-transition
             class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50"
             x-cloak>

            <div @click.away="openModal = false"
                 class="bg-white rounded-2xl max-w-sm w-full p-6 shadow-2xl">

                <div class="text-center">
                    <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-red-100 mb-4">
                        <svg class="h-8 w-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>

                    <h3 class="text-lg font-bold text-gray-900">Konfirmasi Hapus</h3>
                    <p class="text-sm text-gray-500 mt-2">
                        Apakah Anda yakin ingin menghapus
                        <span class="font-bold text-gray-800" x-text="productName"></span>?
                    </p>
                </div>

                <div class="mt-6 flex gap-3">
                    <button @click="openModal = false"
                            class="flex-1 px-4 py-2.5 bg-gray-100 text-gray-700 rounded-xl font-bold hover:bg-gray-200 transition">
                        Batal
                    </button>

                    <form :action="deleteUrl" method="POST" class="flex-1">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="w-full px-4 py-2.5 bg-red-600 text-white rounded-xl font-bold hover:bg-red-700 shadow-lg shadow-red-200 transition">
                            Ya, Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>
</div>
@endsection
