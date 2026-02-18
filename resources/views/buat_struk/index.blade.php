@extends('layouts.app')

@section('content')
<div id="pageWrapper" class="pb-24 max-w-full mx-auto px-4">
    {{-- HEADER SECTION --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">
        <div>
            <h2 class="text-2xl font-bold text-gray-800 tracking-tight">Daftar Struk</h2>
            <p class="text-sm text-gray-500 mt-1">Kelola dan pantau semua riwayat transaksi Anda secara real-time</p>
        </div>

        <div class="flex flex-col md:flex-row gap-3">
            <div class="relative">
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center">
                    <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </span>
                <input type="search" id="searchInput" placeholder="Cari nama pelanggan..."
                       class="w-full md:w-64 pl-10 pr-4 py-2.5 bg-white border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition">
            </div>

            <a href="{{ route('buat_struk.create') }}"
               class="inline-flex items-center justify-center px-6 py-2.5 bg-blue-900 text-white rounded-lg text-sm font-bold shadow-md shadow-blue-900/10 hover:bg-blue-800 transition active:scale-95">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Buat Struk Baru
            </a>
        </div>
    </div>

    {{-- MOBILE VIEW (Cards) --}}
    <div id="strukList" class="space-y-4 md:hidden">
        @forelse($struks as $struk)
            @php
                $statusConfig = [
                    'lunas'   => 'bg-green-50 text-green-700 border-green-100',
                    'partial' => 'bg-orange-50 text-orange-700 border-orange-100',
                    'pending' => 'bg-red-50 text-red-700 border-red-100'
                ][$struk->status_pembayaran] ?? 'bg-gray-50 text-gray-700 border-gray-100';
            @endphp

            <div class="struk-card bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden" 
                 data-nama="{{ strtolower($struk->nama_pelanggan) }}"
                 x-data="{ openModal: false, deleteUrl: '', customerName: '' }">
                
                <div class="p-4 border-b border-gray-50 flex items-center justify-between bg-gray-50/30">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-white border border-gray-200 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-800 text-sm leading-tight">{{ $struk->nama_pelanggan }}</h3>
                            <p class="text-[10px] text-gray-400 font-medium uppercase tracking-wider">{{ $struk->created_at->format('d M Y') }}</p>
                        </div>
                    </div>
                    <span class="px-2.5 py-1 rounded text-[10px] font-bold uppercase border {{ $statusConfig }}">
                        {{ $struk->status_pembayaran }}
                    </span>
                </div>

                <div class="p-4 bg-white grid grid-cols-2 gap-y-3">
                    <div>
                        <p class="text-[10px] text-gray-400 uppercase font-bold tracking-tighter">Total</p>
                        <p class="text-sm font-bold text-gray-800">Rp {{ number_format($struk->total_harga, 0, ',', '.') }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-[10px] text-gray-400 uppercase font-bold tracking-tighter">Sisa</p>
                        <p class="text-sm font-bold text-red-600">Rp {{ number_format($struk->sisa_tagihan, 0, ',', '.') }}</p>
                    </div>
                </div>

                <div class="p-3 bg-gray-50/50 border-t border-gray-100 flex gap-2">
                    <a href="{{ route('buat_struk.show', $struk->id) }}" class="flex-1 bg-white py-2 rounded-lg border border-gray-200 text-center text-xs font-bold text-gray-600 hover:bg-gray-100 transition">Detail</a>
                    <a href="{{ route('buat_struk.edit', $struk->id) }}" class="flex-1 bg-white py-2 rounded-lg border border-gray-200 text-center text-xs font-bold text-blue-600 hover:bg-blue-50 transition">Edit</a>
                    <button @click="openModal = true; deleteUrl = '{{ route('buat_struk.destroy', $struk->id) }}'; customerName = '{{ $struk->nama_pelanggan }}'"
                            class="flex-1 bg-white py-2 rounded-lg border border-gray-200 text-center text-xs font-bold text-red-600 hover:bg-red-50 transition">
                        Hapus
                    </button>
                </div>

                {{-- REUSABLE DELETE MODAL (Mobile) --}}
                <div x-show="openModal" x-transition.opacity class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-sm" x-cloak>
                    <div @click.away="openModal = false" class="bg-white rounded-lg max-w-xs w-full p-6 shadow-xl border border-gray-200">
                        <div class="text-center">
                            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-50 mb-4 border border-red-100">
                                <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                            </div>
                            <h3 class="text-base font-bold text-gray-900">Konfirmasi Hapus</h3>
                            <p class="text-xs text-gray-500 mt-2 leading-relaxed">Hapus struk <span class="font-bold text-gray-800" x-text="customerName"></span>? Tindakan ini tidak bisa dibatalkan.</p>
                        </div>
                        <div class="mt-6 flex gap-2">
                            <button @click="openModal = false" class="flex-1 px-4 py-2 bg-gray-100 text-gray-700 rounded-lg text-xs font-bold hover:bg-gray-200 transition">Batal</button>
                            <form :action="deleteUrl" method="POST" class="flex-1">
                                @csrf @method('DELETE')
                                <button type="submit" class="w-full px-4 py-2 bg-red-600 text-white rounded-lg text-xs font-bold hover:bg-red-700 transition shadow-lg shadow-red-200">Hapus</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-lg border border-gray-200 border-dashed p-12 text-center">
                <p class="text-gray-400 text-sm italic">Belum ada data struk.</p>
            </div>
        @endforelse

        {{-- Pesan pencarian tidak ditemukan (Mobile) --}}
        <div id="emptySearchMobile" style="display: none;"
            class="bg-white rounded-lg border border-gray-200 border-dashed p-12 text-center">
            <p class="text-gray-400 text-sm italic">
                Data tidak ditemukan.
            </p>
        </div>

    </div>

    {{-- DESKTOP VIEW (Table) --}}
    <div class="hidden md:block bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
        <table class="w-full">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-200">
                    <th class="px-6 py-4 text-left text-[11px] font-bold text-gray-500 uppercase tracking-widest">No</th>
                    <th class="px-6 py-4 text-left text-[11px] font-bold text-gray-500 uppercase tracking-widest">Pelanggan</th>
                    <th class="px-6 py-4 text-left text-[11px] font-bold text-gray-500 uppercase tracking-widest">Tanggal</th>
                    <th class="px-6 py-4 text-left text-[11px] font-bold text-gray-500 uppercase tracking-widest">Total Harga</th>
                    <th class="px-6 py-4 text-left text-[11px] font-bold text-gray-500 uppercase tracking-widest">Sisa Tagihan</th>
                    <th class="px-6 py-4 text-left text-[11px] font-bold text-gray-500 uppercase tracking-widest">Status</th>
                    <th class="px-6 py-4 text-center text-[11px] font-bold text-gray-500 uppercase tracking-widest">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100" id="desktopList">
                @foreach($struks as $struk)
                    @php
                        $badgeClasses = [
                            'lunas'   => 'bg-green-50 text-green-700 border-green-100',
                            'partial' => 'bg-orange-50 text-orange-700 border-orange-100',
                            'pending' => 'bg-red-50 text-red-700 border-red-100'
                        ][$struk->status_pembayaran] ?? 'bg-gray-50 text-gray-700 border-gray-100';
                    @endphp
                    <tr class="hover:bg-blue-50/30 transition-colors struk-row" data-nama="{{ strtolower($struk->nama_pelanggan) }}">
                        <td class="px-6 py-4 text-sm text-gray-400">{{ $loop->iteration }}</td>
                        <td class="px-6 py-4">
                            <p class="font-bold text-gray-800 text-sm">{{ $struk->nama_pelanggan }}</p>
                            <p class="text-[11px] text-gray-400">{{ $struk->no_telepon ?? '-' }}</p>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $struk->created_at->format('d M Y') }}</td>
                        <td class="px-6 py-4 font-bold text-gray-800 text-sm">Rp {{ number_format($struk->total_harga, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 font-bold text-red-600 text-sm">Rp {{ number_format($struk->sisa_tagihan, 0, ',', '.') }}</td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 rounded border {{ $badgeClasses }} text-[10px] font-bold uppercase tracking-wider">
                                {{ $struk->status_pembayaran }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex justify-center gap-2">
                                <a href="{{ route('buat_struk.show', $struk->id) }}" class="p-2 text-gray-400 hover:text-green-600 transition-colors" title="Detail">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </a>
                                <a href="{{ route('buat_struk.edit', $struk->id) }}" class="p-2 text-gray-400 hover:text-blue-600 transition-colors" title="Edit">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </a>
                                <form action="{{ route('buat_struk.destroy', $struk->id) }}" method="POST" onsubmit="return confirm('Hapus struk ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-2 text-gray-400 hover:text-red-600 transition-colors" title="Hapus">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach

                {{-- Pesan pencarian tidak ditemukan (Desktop) --}}
                <tr id="emptySearchDesktop" style="display: none;">
                    <td colspan="7" class="px-6 py-12 text-center text-gray-400 italic text-sm">
                        Data tidak ditemukan.
                    </td>
                </tr>

            </tbody>
        </table>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    const searchInput = document.getElementById('searchInput');
    const emptyMobile = document.getElementById('emptySearchMobile');
    const emptyDesktop = document.getElementById('emptySearchDesktop');

    if (!searchInput) return;

    searchInput.addEventListener('input', function () {

        const searchTerm = this.value.toLowerCase().trim();

        const desktopRows = document.querySelectorAll('.struk-row');
        const mobileCards = document.querySelectorAll('.struk-card');

        let desktopMatch = 0;
        let mobileMatch = 0;

        // ===== DESKTOP =====
        desktopRows.forEach(row => {
            const name = row.dataset.nama || '';
            const match = name.includes(searchTerm);

            row.style.display = match ? '' : 'none';

            if (match) desktopMatch++;
        });

        // ===== MOBILE =====
        mobileCards.forEach(card => {
            const name = card.dataset.nama || '';
            const match = name.includes(searchTerm);

            card.style.display = match ? '' : 'none';

            if (match) mobileMatch++;
        });

        // ===== EMPTY MESSAGE CONTROL =====

        if (searchTerm !== '' && desktopMatch === 0 && desktopRows.length > 0) {
            emptyDesktop.style.display = 'table-row';
        } else {
            emptyDesktop.style.display = 'none';
        }

        if (searchTerm !== '' && mobileMatch === 0 && mobileCards.length > 0) {
            emptyMobile.style.display = 'block';
        } else {
            emptyMobile.style.display = 'none';
        }

    });

});
</script>
@endpush


@endsection