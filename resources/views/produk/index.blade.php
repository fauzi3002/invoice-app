@extends('layouts.app')

@section('content')
<div class="pb-24">

    <!-- BUTTON -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
    <div>
        <h2 class="text-2xl font-semibold text-gray-800">Daftar Produk</h2>
        <p class="text-sm text-gray-500 mt-1">
            Kelola stok dan harga produk Anda
        </p>
    </div>

    <div class="justify-end flex flex-col md:flex-row gap-4">
        <input type="search" name="" id="searchInput" placeholder="Cari Produk"
               class="w-full md:w-64 px-4 py-2 border border-gray-300 rounded-lg
                      focus:outline-none focus:ring-2 focus:ring-blue-300">
    

    <a href="{{route ('produk.create')}}"
       class="px-8 py-3 bg-blue-900 text-center text-white rounded-lg font-bold shadow-lg shadow-blue-200 hover:bg-blue-700 hover:shadow-blue-300 transition active:scale-95">
        + Tambah Produk
    </a>
    </div>
</div>
<div id="productList" class="space-y-4 md:hidden px-2">

@forelse($produks as $produk)
    <div class="product-card"
         data-nama="{{ strtolower($produk->nama_produk) }}"
         x-data="{ openModal: false, deleteUrl: '', productName: '' }">

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4">

            <div class="flex items-center gap-4 mb-4">
                <div style="width: 56px; height: 56px; min-width: 56px; min-height: 56px;"
                     class="bg-gray-100 rounded-xl flex items-center justify-center overflow-hidden shrink-0 border border-gray-100">

                    @if($produk->gambar)
                        <img src="{{ asset('storage/' . $produk->gambar) }}"
                             alt="Foto {{ $produk->nama_produk }}"
                             style="width: 100%; height: 100%; object-fit: cover;"
                             class="block">
                    @else
                        <div class="flex flex-col items-center">
                            <svg class="w-6 h-6 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.581-1.581a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                    @endif
                </div>

                <div class="min-w-0 flex-1">
                    <h3 class="font-bold text-gray-800 truncate text-sm">
                        {{ $produk->nama_produk }}
                    </h3>
                    <p class="text-[10px] text-gray-400 uppercase tracking-widest font-bold">
                        Nomor:  {{ $loop->iteration }}
                    </p>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4 py-3 border-t border-b border-gray-50 mb-4">
                <div>
                    <p class="text-gray-400 text-[10px] uppercase font-bold mb-1">Stok</p>
                    <span class="inline-block {{ $produk->stok > 0 ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}
                                 px-3 py-1 rounded-lg text-xs font-bold">
                        {{ $produk->stok }} Unit
                    </span>
                </div>

                <div class="text-right">
                    <p class="text-gray-400 text-[10px] uppercase font-bold mb-1">Harga Satuan</p>
                    <p class="font-bold text-blue-900 text-sm">
                        Rp {{ number_format($produk->harga_satuan, 0, ',', '.') }}
                    </p>
                </div>
            </div>

            <div class="flex gap-2">
                <a href="{{ route('produk.show', $produk->id) }}"
                   class="flex-1 bg-white text-center text-gray-700 px-3 py-2 rounded-lg text-xs font-bold hover:bg-gray-100 transition active:scale-95 border border-gray-200">
                    Detail
                </a>

                <a href="{{ route('produk.edit', $produk->id) }}"
                   class="flex-1 bg-white text-center text-gray-700 px-3 py-2 rounded-lg text-xs font-bold hover:bg-gray-100 transition active:scale-95 border border-gray-200">
                    Edit
                </a>

                <button
                    @click="openModal = true;
                            deleteUrl = '{{ route('produk.destroy', $produk->id) }}';
                            productName = '{{ $produk->nama_produk }}'"
                    class="flex-1 bg-red-50 text-red-600 py-2.5 rounded-lg text-xs font-bold hover:bg-red-100 transition border border-red-100">
                    Hapus
                </button>
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
@empty
    <div class="bg-gray-50 rounded-2xl border-2 border-dashed border-gray-200 p-10 text-center">
        <p class="text-gray-500 text-sm">Tidak ada produk tersedia.</p>
    </div>
@endforelse

</div>


    <!-- ================= DESKTOP (TABLE) ================= -->
    {{-- DESKTOP VIEW --}}
<div class="hidden md:block bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <table class="w-full divide-y divide-gray-200">
        <thead class="bg-blue-900 text-white">
            <tr>
                <th class="px-6 py-4 text-left text-xs font-bold uppercase">No</th>
                <th class="px-6 py-4 text-left text-xs font-bold uppercase">Produk</th>
                <th class="px-6 py-4 text-left text-xs font-bold uppercase">Stok</th>
                <th class="px-6 py-4 text-left text-xs font-bold uppercase">Harga</th>
                <th class="px-6 py-4 text-center text-xs font-bold uppercase">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            {{-- Pastikan variabel ini adalah Collection hasil dari ->get() atau ->all() --}}
            @forelse($produks as $p)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4 text-sm text-gray-500">{{ $loop->iteration }}</td>
                    <td class="px-6 py-4">
                        <p class="font-bold text-gray-800">{{ $p->nama_produk }}</p>
                        <p class="text-xs text-gray-400">{{ Str::limit($p->deskripsi, 50) }}</p>
                    </td>
                    <td class="px-6 py-4 text-sm {{ $p->stok <= 5 ? 'text-red-600 font-bold' : 'text-gray-600' }}">
                        {{ $p->stok }}
                    </td>
                    <td class="px-6 py-4 font-bold text-blue-900">
                        Rp {{ number_format($p->harga_satuan, 0, ',', '.') }}
                    </td>
                    <td class="px-6 py-4 text-center">
                        <div class="flex justify-center gap-3">
                            <a href="{{ route('produk.edit', $p->id) }}" class="text-blue-600 hover:text-blue-900 text-sm font-bold">Edit</a>
                            <form action="{{ route('produk.destroy', $p->id) }}" method="POST">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900 text-sm font-bold" onclick="return confirm('Hapus?')">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="px-6 py-10 text-center text-gray-500 italic">
                        Belum ada data produk.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('searchInput');
    const productCards = document.querySelectorAll('.product-card');

    searchInput.addEventListener('input', function () {
        const keyword = this.value.toLowerCase();

        productCards.forEach(card => {
            const namaProduk = card.dataset.nama;

            if (namaProduk.includes(keyword)) {
                card.classList.remove('hidden');
            } else {
                card.classList.add('hidden');
            }
        });
    });
});
</script>
