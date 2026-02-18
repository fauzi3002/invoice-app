@extends('layouts.app')

@section('content')
<div class="pb-24 max-w-full mx-auto px-4" x-data="{ openModal: false, deleteUrl: '', productName: '' }">

    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">
        <div>
            <h2 class="text-2xl font-bold text-gray-800 tracking-tight">Detail Produk</h2>
            <p class="text-sm text-gray-500 mt-1">
                Informasi lengkap mengenai aset inventaris Anda
            </p>
        </div>

        <div class="flex">
            <a href="{{ route('produk.index') }}" 
               class="inline-flex items-center px-6 py-2.5 bg-white border border-gray-200 text-gray-600 rounded-lg text-sm font-bold hover:bg-gray-50 transition active:scale-95">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Kembali
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">

        {{-- Gambar Produk --}}
        <div class="space-y-4">
            <div class="bg-white p-3 rounded-lg shadow-sm border border-gray-200">
                <div class="aspect-square rounded-lg overflow-hidden bg-gray-50 border border-gray-100 flex items-center justify-center">
                    @if ($produk->gambar)
                        <img src="{{ asset('storage/' . $produk->gambar) }}"
                             alt="{{ $produk->nama_produk }}"
                             class="w-full h-full object-cover">
                    @else
                        <div class="flex flex-col items-center text-gray-300">
                            <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                      d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.581-1.581a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <span class="text-[10px] mt-2 font-bold tracking-widest uppercase">No Image</span>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="grid grid-cols-2 gap-3">
                <a href="{{ route('produk.edit', $produk->id) }}" 
                   class="bg-white text-center text-blue-600 px-4 py-2.5 rounded-lg text-xs font-bold hover:bg-blue-50 transition border border-gray-200">
                    Edit Produk
                </a>
                <button @click="openModal = true; deleteUrl = '{{ route('produk.destroy', $produk->id) }}'; productName = '{{ $produk->nama_produk }}'"
                        class="bg-white text-center text-red-600 px-4 py-2.5 rounded-lg text-xs font-bold hover:bg-red-50 transition border border-gray-200">
                    Hapus
                </button>
            </div>
        </div>

        {{-- Detail Produk --}}
        <div class="md:col-span-2 space-y-6">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                <div class="p-6 md:p-8 space-y-8">
                    {{-- Identitas --}}
                    <div class="flex flex-col sm:flex-row justify-between items-start gap-4">
                        <div>
                            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Nama Produk</span>
                            <h1 class="text-2xl font-bold text-gray-800 leading-tight">
                                {{ $produk->nama_produk }}
                            </h1>
                            <p class="text-xs text-blue-600 font-mono mt-1">ID: #{{ str_pad($produk->id, 5, '0', STR_PAD_LEFT) }}</p>
                        </div>

                        <span class="px-3 py-1.5 rounded-lg text-[10px] font-bold uppercase tracking-wider border
                            {{ $produk->stok > 0 ? 'bg-green-50 text-green-700 border-green-100' : 'bg-red-50 text-red-700 border-red-100' }}">
                            ● {{ $produk->stok > 0 ? 'Tersedia' : 'Habis' }}
                        </span>
                    </div>

                    {{-- Informasi Harga & Stok --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 pt-6 border-t border-gray-50">
                        <div class="p-4 bg-gray-50/50 rounded-lg border border-gray-100">
                            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mb-1">Harga Satuan</p>
                            <p class="text-xl font-bold text-blue-900">
                                Rp {{ number_format($produk->harga_satuan, 0, ',', '.') }}
                            </p>
                        </div>

                        <div class="p-4 bg-gray-50/50 rounded-lg border border-gray-100">
                            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mb-1">Total Stok</p>
                            <p class="text-xl font-bold text-gray-800">
                                {{ $produk->stok }} <span class="text-xs font-medium text-gray-400 uppercase tracking-tighter ml-1">Unit</span>
                            </p>
                        </div>
                    </div>

                    {{-- Deskripsi --}}
                    <div class="pt-6">
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mb-3">
                            Deskripsi Produk
                        </p>
                        <div class="bg-white p-4 rounded-lg border border-gray-100 min-h-[100px]">
                            <p class="text-sm text-gray-600 leading-relaxed italic">
                                {{ $produk->deskripsi ?? 'Tidak ada deskripsi tambahan untuk produk ini.' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- DELETE MODAL --}}
    <div x-show="openModal" 
         x-transition.opacity
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-sm" 
         x-cloak>
        <div @click.away="openModal = false"
             class="bg-white rounded-lg max-w-sm w-full p-6 shadow-2xl border border-gray-200">
            <div class="text-center">
                <div class="mx-auto flex items-center justify-center h-14 w-14 rounded-full bg-red-50 mb-4 border border-red-100">
                    <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-gray-900">Konfirmasi Hapus</h3>
                <p class="text-sm text-gray-500 mt-2">
                    Apakah Anda yakin ingin menghapus <span class="font-bold text-gray-800" x-text="productName"></span>? Data ini tidak dapat dipulihkan.
                </p>
            </div>
            <div class="mt-6 flex gap-3">
                <button @click="openModal = false"
                        class="flex-1 px-4 py-2.5 bg-gray-100 text-gray-700 rounded-lg text-sm font-bold hover:bg-gray-200 transition">
                    Batal
                </button>
                <form :action="deleteUrl" method="POST" class="flex-1">
                    @csrf @method('DELETE')
                    <button type="submit"
                            class="w-full px-4 py-2.5 bg-red-600 text-white rounded-lg text-sm font-bold hover:bg-red-700 shadow-lg shadow-red-200 transition">
                        Ya, Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection