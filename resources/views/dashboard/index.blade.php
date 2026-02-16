@extends('layouts.app')

@section('content')
<div class="pb-24 px-4 md:px-0">
    
    <div class="mb-8">
        <h2 class="text-2xl font-semibold text-gray-800">Dashboard</h2>
        <p class="text-sm text-gray-500 mt-1">Ringkasan bisnis dan performa invoice Anda.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-4">
            <div class="p-3 bg-green-100 rounded-xl">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <p class="text-[10px] text-gray-400 uppercase font-bold tracking-widest">Total Pendapatan</p>
                <p class="text-lg font-bold text-gray-800">Rp {{ number_format($total_pendapatan, 0, ',', '.') }}</p>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-4">
            <div class="p-3 bg-red-100 rounded-xl">
                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
            </div>
            <div>
                <p class="text-[10px] text-gray-400 uppercase font-bold tracking-widest">Total Piutang</p>
                <p class="text-lg font-bold text-gray-800">Rp {{ number_format($total_piutang, 0, ',', '.') }}</p>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-4">
            <div class="p-3 bg-blue-100 rounded-xl">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <p class="text-[10px] text-gray-400 uppercase font-bold tracking-widest">Invoice Pending</p>
                <p class="text-lg font-bold text-gray-800">{{ $invoice_pending }} Struk</p>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-4">
            <div class="p-3 bg-yellow-100 rounded-xl">
                <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
            </div>
            <div>
                <p class="text-[10px] text-gray-400 uppercase font-bold tracking-widest">Stok Menipis</p>
                <p class="text-lg font-bold text-gray-800">{{ $stok_menipis }} Item</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <div class="lg:col-span-2">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-bold text-gray-800">Invoice Terbaru</h3>
                <a href="{{ route('buat_struk.index') }}" class="text-blue-600 text-xs font-bold hover:underline">Lihat Semua</a>
            </div>
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <table class="w-full text-left text-sm">
                    <thead class="bg-gray-50 border-b border-gray-100">
                        <tr>
                            <th class="px-6 py-4 font-bold text-gray-600">Pelanggan</th>
                            <th class="px-6 py-4 font-bold text-gray-600">Total</th>
                            <th class="px-6 py-4 font-bold text-gray-600 text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($recent_struks as $struk)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 uppercase font-medium text-gray-700">{{ $struk->nama_pelanggan }}</td>
                            <td class="px-6 py-4 font-bold text-blue-900">Rp {{ number_format($struk->total_harga, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 text-center">
                                <span class="px-3 py-1 rounded-full text-[9px] font-bold uppercase
                                    {{ $struk->status_pembayaran == 'Lunas' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                    {{ $struk->status_pembayaran }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="3" class="p-6 text-center text-gray-400 italic">Belum ada transaksi</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="space-y-6">
            <h3 class="font-bold text-gray-800">Aksi Cepat</h3>
            <div class="grid grid-cols-1 gap-3">
                <a href="{{ route('buat_struk.create') }}" class="flex items-center gap-4 p-4 bg-blue-900 text-white rounded-2xl hover:bg-blue-800 transition shadow-lg shadow-blue-200">
                    <div class="p-2 bg-blue-800 rounded-lg">+</div>
                    <span class="font-bold text-sm">Buat Invoice Baru</span>
                </a>
                <a href="{{ route('produk.create') }}" class="flex items-center gap-4 p-4 bg-white border border-gray-200 text-gray-700 rounded-2xl hover:bg-gray-50 transition">
                    <div class="p-2 bg-gray-100 rounded-lg">+</div>
                    <span class="font-bold text-sm">Tambah Produk Baru</span>
                </a>
            </div>

            @if($stok_menipis > 0)
            <div class="bg-red-50 border border-red-100 p-5 rounded-2xl">
                <div class="flex items-center gap-3 mb-3 text-red-700">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    <span class="font-bold text-sm">Peringatan Stok</span>
                </div>
                <p class="text-xs text-red-600 leading-relaxed">Ada <strong>{{ $stok_menipis }} produk</strong> yang hampir habis. Segera lakukan pengadaan barang.</p>
            </div>
            @endif
        </div>

    </div>
</div>
@endsection