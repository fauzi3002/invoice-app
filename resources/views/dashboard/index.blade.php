@extends('layouts.app')

@section('content')
<div class="pb-24 px-4 md:px-0 max-w-full mx-auto">
    
    {{-- Header --}}
    <div class="mb-8">
        <h2 class="text-2xl font-bold text-gray-800 tracking-tight">Dashboard</h2>
        <p class="text-sm text-gray-500 mt-1">Ringkasan bisnis dan performa invoice Anda.</p>
    </div>

    {{-- Statistik Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200 flex items-center gap-4 transition hover:border-blue-200">
            <div class="p-3 bg-green-50 rounded-lg border border-green-100">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <p class="text-[10px] text-gray-400 uppercase font-bold tracking-widest">Total Pendapatan</p>
                <p class="text-lg font-bold text-gray-800 tracking-tight">Rp {{ number_format($total_pendapatan, 0, ',', '.') }}</p>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200 flex items-center gap-4 transition hover:border-blue-200">
            <div class="p-3 bg-red-50 rounded-lg border border-red-100">
                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
            </div>
            <div>
                <p class="text-[10px] text-gray-400 uppercase font-bold tracking-widest">Total Piutang</p>
                <p class="text-lg font-bold text-gray-800 tracking-tight">Rp {{ number_format($total_piutang, 0, ',', '.') }}</p>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200 flex items-center gap-4 transition hover:border-blue-200">
            <div class="p-3 bg-blue-50 rounded-lg border border-blue-100">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <p class="text-[10px] text-gray-400 uppercase font-bold tracking-widest">Invoice Pending</p>
                <p class="text-lg font-bold text-gray-800 tracking-tight">{{ $invoice_pending }} Struk</p>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200 flex items-center gap-4 transition hover:border-blue-200">
            <div class="p-3 bg-yellow-50 rounded-lg border border-yellow-100">
                <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
            </div>
            <div>
                <p class="text-[10px] text-gray-400 uppercase font-bold tracking-widest">Stok Menipis</p>
                <p class="text-lg font-bold text-gray-800 tracking-tight">{{ $stok_menipis }} Item</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        {{-- Invoice Table --}}
        <div class="lg:col-span-2">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-bold text-gray-800 uppercase text-xs tracking-widest">Invoice Terbaru</h3>
                <a href="{{ route('buat_struk.index') }}" class="text-blue-600 text-[11px] font-bold hover:underline uppercase tracking-tighter">Lihat Semua</a>
            </div>
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                <table class="w-full text-left text-sm">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-4 font-bold text-[11px] text-gray-500 uppercase tracking-wider">Pelanggan</th>
                            <th class="px-6 py-4 font-bold text-[11px] text-gray-500 uppercase tracking-wider">Total</th>
                            <th class="px-6 py-4 font-bold text-[11px] text-gray-500 uppercase tracking-wider text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($recent_struks as $struk)
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="px-6 py-4 uppercase text-xs font-semibold text-gray-700">{{ $struk->nama_pelanggan }}</td>
                            <td class="px-6 py-4 font-bold text-blue-900">Rp {{ number_format($struk->total_harga, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 text-center">
                                <span class="px-2.5 py-1 rounded-lg text-[9px] font-black uppercase border
                                    {{ $struk->status_pembayaran == 'Lunas' ? 'bg-green-50 text-green-700 border-green-100' : 'bg-red-50 text-red-700 border-red-100' }}">
                                    {{ $struk->status_pembayaran }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="3" class="p-10 text-center text-gray-400 text-xs italic uppercase tracking-widest">Belum ada transaksi</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Aksi Cepat & Alert --}}
        <div class="space-y-6">
            <h3 class="font-bold text-gray-800 uppercase text-xs tracking-widest">Aksi Cepat</h3>
            <div class="grid grid-cols-1 gap-3">
                <a href="{{ route('buat_struk.create') }}" class="flex items-center gap-4 p-4 bg-blue-900 text-white rounded-lg hover:bg-blue-800 transition shadow-md shadow-blue-900/10 group">
                    <div class="p-2 bg-blue-800 rounded-lg group-hover:scale-110 transition">+</div>
                    <span class="font-bold text-xs uppercase tracking-wide">Buat Invoice Baru</span>
                </a>
                <a href="{{ route('produk.create') }}" class="flex items-center gap-4 p-4 bg-white border border-gray-200 text-gray-700 rounded-lg hover:bg-gray-50 transition group">
                    <div class="p-2 bg-gray-100 rounded-lg border border-gray-200 group-hover:bg-white transition">+</div>
                    <span class="font-bold text-xs uppercase tracking-wide">Tambah Produk Baru</span>
                </a>
            </div>

            @if($stok_menipis > 0)
            <div class="bg-red-50 border border-red-200 p-5 rounded-lg relative overflow-hidden">
                <div class="absolute top-0 right-0 p-2 opacity-10">
                    <svg class="w-12 h-12 text-red-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                </div>
                <div class="flex items-center gap-3 mb-2 text-red-700">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    <span class="font-black text-[10px] uppercase tracking-widest">Peringatan Stok</span>
                </div>
                <p class="text-[11px] text-red-600 leading-relaxed font-medium">Ada <strong class="underline">{{ $stok_menipis }} produk</strong> yang hampir habis. Segera lakukan pengadaan barang.</p>
            </div>
            @endif
        </div>

    </div>
</div>
@endsection