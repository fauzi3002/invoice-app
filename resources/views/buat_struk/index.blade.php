@extends('layouts.app')

@section('content')
<div class="pb-24">
    {{-- HEADER SECTION --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
        <div>
            <h2 class="text-2xl font-semibold text-gray-800">Daftar Struk</h2>
            <p class="text-sm text-gray-500 mt-1">Kelola dan pantau semua riwayat transaksi Anda</p>
        </div>

        <div class="flex flex-col md:flex-row gap-4 justify-end">
            <input type="search" id="searchInput" placeholder="Cari Pelanggan..."
                   class="w-full md:w-64 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-300">

            <a href="{{ route('buat_struk.create') }}"
               class="px-8 py-3 bg-blue-900 text-center text-white rounded-lg font-bold shadow-lg shadow-blue-200 hover:bg-blue-700 hover:shadow-blue-300 transition active:scale-95">
                + Buat Struk Baru
            </a>
        </div>
    </div>

    {{-- MOBILE VIEW (Cards) --}}
    <div id="strukList" class="space-y-4 md:hidden px-2">
        @forelse($struks as $struk)
            @php
                $statusClasses = [
                    'lunas'   => 'bg-green-100 text-green-700',
                    'partial' => 'bg-orange-100 text-orange-700',
                    'pending' => 'bg-red-100 text-red-700'
                ][$struk->status_pembayaran] ?? 'bg-gray-100 text-gray-700';
            @endphp

            <div class="struk-card" 
                 data-nama="{{ strtolower($struk->nama_pelanggan) }}"
                 x-data="{ openModal: false, deleteUrl: '', customerName: '' }">
                
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center shrink-0 border border-blue-100">
                            <svg class="w-6 h-6 text-blue-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>

                        <div class="min-w-0 flex-1">
                            <h3 class="font-bold text-gray-800 truncate text-sm">{{ $struk->nama_pelanggan }}</h3>
                            <p class="text-[10px] text-gray-400 uppercase tracking-widest font-bold">
                                Tanggal: {{ $struk->created_at->format('d/m/Y') }}
                            </p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4 py-3 border-t border-b border-gray-50 mb-4">
                        <div>
                            <p class="text-gray-400 text-[10px] uppercase font-bold mb-1">Status</p>
                            <span class="inline-block {{ $statusClasses }} px-3 py-1 rounded-lg text-[10px] font-bold uppercase">
                                {{ $struk->status_pembayaran }}
                            </span>
                        </div>

                        <div class="text-right">
                            <p class="text-gray-400 text-[10px] uppercase font-bold mb-1">Total Harga</p>
                            <p class="font-bold text-green-700 text-sm">Rp {{ number_format($struk->total_harga, 0, ',', '.') }}</p>

                            <p class="text-gray-400 text-[10px] uppercase font-bold mb-1">Sudah Dibayar</p>
                            <p class="font-bold text-blue-700 text-sm">Rp {{ number_format($struk->jumlah_bayar, 0, ',', '.') }}</p>

                            <p class="text-gray-400 text-[10px] uppercase font-bold mb-1">Sisa Tagihan</p>
                            <p class="font-bold text-red-500 text-sm">Rp {{ number_format($struk->sisa_tagihan, 0, ',', '.') }}</p>
                        </div>
                    </div>

                    <div class="flex gap-2">
                        <a href="{{ route('buat_struk.show', $struk->id) }}"
                           class="flex-1 bg-white text-center text-gray-700 px-3 py-2 rounded-lg text-xs font-bold hover:bg-gray-100 transition border border-gray-200">
                            Detail
                        </a>
                        <a href="{{ route('buat_struk.edit', $struk->id) }}"
                           class="flex-1 bg-white text-center text-gray-700 px-3 py-2 rounded-lg text-xs font-bold hover:bg-gray-100 transition active:scale-95 border border-gray-200">
                            Edit
                        </a>
                        <button @click="openModal = true; 
                                        deleteUrl = '{{ route('buat_struk.destroy', $struk->id) }}'; 
                                        customerName = '{{ $struk->nama_pelanggan }}'"
                                class="flex-1 bg-red-50 text-red-600 py-2.5 rounded-lg text-xs font-bold hover:bg-red-100 transition border border-red-100">
                            Hapus
                        </button>
                    </div>
                </div>

                {{-- DELETE MODAL (Mobile) --}}
                <div x-show="openModal" x-transition class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50" x-cloak>
                    <div @click.away="openModal = false" class="bg-white rounded-2xl max-w-sm w-full p-6 shadow-2xl">
                        <div class="text-center">
                            <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-red-100 mb-4">
                                <svg class="h-8 w-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                            </div>
                            <h3 class="text-lg font-bold text-gray-900">Hapus Struk?</h3>
                            <p class="text-sm text-gray-500 mt-2">
                                Menghapus struk milik <span class="font-bold text-gray-800" x-text="customerName"></span> bersifat permanen.
                            </p>
                        </div>
                        <div class="mt-6 flex gap-3">
                            <button @click="openModal = false" class="flex-1 px-4 py-2.5 bg-gray-100 text-gray-700 rounded-xl font-bold hover:bg-gray-200 transition">Batal</button>
                            <form :action="deleteUrl" method="POST" class="flex-1">
                                @csrf @method('DELETE')
                                <button type="submit" class="w-full px-4 py-2.5 bg-red-600 text-white rounded-xl font-bold hover:bg-red-700 shadow-lg shadow-red-200 transition">Hapus</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-gray-50 rounded-2xl border-2 border-dashed border-gray-200 p-10 text-center">
                <p class="text-gray-500 text-sm">Belum ada struk tersedia.</p>
            </div>
        @endforelse
    </div>

    {{-- DESKTOP VIEW (Table) --}}
    <div class="hidden md:block bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <table class="w-full divide-y divide-gray-200">
            <thead class="bg-blue-900 text-white">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-bold uppercase">No</th>
                    <th class="px-6 py-4 text-left text-xs font-bold uppercase">Pelanggan</th>
                    <th class="px-6 py-4 text-left text-xs font-bold uppercase">Tanggal</th>
                    <th class="px-6 py-4 text-left text-xs font-bold uppercase">Total Harga</th>
                    <th class="px-6 py-4 text-left text-xs font-bold uppercase">Sudah Dibayar</th>
                    <th class="px-6 py-4 text-left text-xs font-bold uppercase">Sisa Tagihan</th>
                    <th class="px-6 py-4 text-left text-xs font-bold uppercase">Status</th>
                    <th class="px-6 py-4 text-center text-xs font-bold uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100" id="desktopList">
                @foreach($struks as $struk)
                    @php
                        $badgeClasses = [
                            'lunas'   => 'bg-green-100 text-green-700',
                            'partial' => 'bg-orange-100 text-orange-700',
                            'pending' => 'bg-red-100 text-red-700'
                        ][$struk->status_pembayaran] ?? 'bg-gray-100 text-gray-700';
                    @endphp
                    <tr class="hover:bg-gray-50 transition struk-row" data-nama="{{ strtolower($struk->nama_pelanggan) }}">
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $loop->iteration }}</td>
                        <td class="px-6 py-4">
                            <p class="font-bold text-gray-800">{{ $struk->nama_pelanggan }}</p>
                            <p class="text-xs text-gray-400">{{ $struk->no_telepon ?? '-' }}</p>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $struk->created_at->format('d M Y') }}</td>
                        <td class="px-6 py-4 font-bold text-blue-900">Rp {{ number_format($struk->total_harga, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 font-bold text-blue-900">Rp {{ number_format($struk->jumlah_bayar, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 font-bold text-red-700">Rp {{ number_format($struk->sisa_tagihan, 0, ',', '.') }}</td>
                        <td class="px-6 py-4">
                            <span class="{{ $badgeClasses }} px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider">
                                {{ $struk->status_pembayaran }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex justify-center gap-3 items-center">
                                <a href="{{ route('buat_struk.show', $struk->id) }}" class="text-green-700 hover:text-green-900 font-bold text-sm">Detail</a>
                                <a href="{{ route('buat_struk.edit', $struk->id) }}" class="text-blue-700 hover:text-blue-900 font-bold text-sm">Edit</a>
                                <form action="{{ route('buat_struk.destroy', $struk->id) }}" method="POST" onsubmit="return confirm('Hapus struk ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800 font-bold text-sm">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('searchInput');
    const strukCards = document.querySelectorAll('.struk-card');
    const strukRows = document.querySelectorAll('.struk-row');

    searchInput.addEventListener('input', function () {
        const keyword = this.value.toLowerCase();

        // Search for Mobile Cards
        strukCards.forEach(card => {
            card.classList.toggle('hidden', !card.dataset.nama.includes(keyword));
        });

        // Search for Desktop Rows
        strukRows.forEach(row => {
            row.classList.toggle('hidden', !row.dataset.nama.includes(keyword));
        });
    });
});
</script>
@endpush