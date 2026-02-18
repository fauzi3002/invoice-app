@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto pb-10 print:pb-0 print:m-0 px-4 md:px-0">
    
    {{-- Action Buttons --}}
    <div class="flex flex-col md:flex-row justify-between items-center mb-6 no-print gap-4">
        <a href="{{ route('buat_struk.index') }}" class="text-sm font-bold text-gray-500 hover:text-blue-900 flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Kembali
        </a>
        
        <div class="flex items-center gap-2">
            <button onclick="window.print()" class="bg-white text-gray-700 border border-gray-200 px-6 py-2 rounded-lg font-bold hover:bg-gray-50 transition flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                Unduh PDF
            </button>

            <button onclick="window.print()" class="bg-blue-900 text-white px-6 py-2 rounded-lg font-bold shadow-lg shadow-blue-200 hover:bg-blue-800 transition flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                Cetak Invoice
            </button>
        </div>
    </div>
    
    {{-- Invoice Card --}}
    <div class="bg-white rounded-lg border border-gray-200 overflow-hidden print:border-none print:m-0 print:shadow-none">
        {{-- Judul Invoice - SEKARANG IKUT TERCETAK --}}
        <div class="bg-gray-50/50 border-b border-gray-100 py-4 print:bg-transparent print:border-gray-200">
            <h1 class="text-center text-sm font-black text-blue-900 uppercase tracking-[0.2em]">Invoice Pembayaran</h1>
        </div>

        <div class="p-6 md:p-12 print:p-4">
            
            {{-- HEADER SECTION --}}
            <div class="flex justify-between items-start mb-8 print:mb-4">
                <div class="flex-1">
                    <p class="text-2xl font-black text-gray-800 tracking-tight print:text-lg">{{ $toko->nama_toko ?? 'Nama Toko' }}</p>
                    <div class="mt-2 space-y-1">
                        <p class="text-xs text-gray-500 max-w-xs leading-relaxed print:text-[10px]">{{ $toko->alamat_toko }}</p>
                        <p class="text-xs font-bold text-blue-900 print:text-[10px]">WhatsApp: {{ $toko->no_telepon }}</p>
                    </div>
                </div>
                
                @if($toko->logo_toko)
                    <img src="{{ asset('storage/'.$toko->logo_toko) }}" class="logo-toko w-24 h-24 object-contain">
                @endif
            </div>

            <div class="grid grid-cols-2 gap-8 mb-10 print:mb-6 print:grid-cols-2">
                <div>
                    <h3 class="text-[10px] uppercase tracking-widest font-black text-gray-400 mb-2">Ditujukan Untuk:</h3>
                    <p class="text-base font-bold text-gray-800 leading-tight print:text-sm">{{ $struk->nama_pelanggan }}</p>
                    <p class="text-xs text-gray-500 mt-1 print:text-[10px]">{{ $struk->no_telepon }}</p>
                </div>
                <div class="text-right">
                    <h3 class="text-[10px] uppercase tracking-widest font-black text-gray-400 mb-2">Detail Invoice:</h3>
                    <p class="text-base font-bold text-blue-900 print:text-sm">#INV-{{ str_pad($struk->id, 5, '0', STR_PAD_LEFT) }}</p>
                    <p class="text-xs font-medium text-gray-600 mt-1 print:text-[10px]">{{ $struk->created_at->format('d F Y') }}</p>
                </div>
            </div>

            {{-- TABLE SECTION --}}
            <div class="border border-gray-200 rounded-lg overflow-hidden mb-8 print:border-gray-200">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-200 text-left print:bg-gray-100">
                            <th class="py-3 px-4 text-[10px] font-black text-gray-500 uppercase">Item Description</th>
                            <th class="py-3 px-4 text-center text-[10px] font-black text-gray-500 uppercase">Qty</th>
                            <th class="py-3 px-4 text-right text-[10px] font-black text-gray-500 uppercase">Amount</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($struk->items as $item)
                        <tr>
                            <td class="py-4 px-4">
                                <p class="text-sm font-bold text-gray-800 print:text-xs">{{ $item->produk->nama_produk }}</p>
                                <p class="text-[10px] text-gray-400">Harga: Rp{{ number_format($item->produk->harga_satuan,0,',','.') }}</p>
                            </td>
                            <td class="py-4 px-4 text-center text-sm font-medium text-gray-600 print:text-xs">{{ $item->qty }}</td>
                            <td class="py-4 px-4 text-right text-sm font-bold text-gray-800 print:text-xs">
                                Rp{{ number_format($item->subtotal,0,',','.') }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- SUMMARY SECTION --}}
            <div class="flex flex-col md:flex-row justify-between items-start gap-8 print:flex-row print:gap-4">
                <div class="flex-1 bg-blue-50/50 p-4 rounded-lg border border-blue-100 w-full md:w-auto print:bg-transparent print:border-gray-200">
                    <h3 class="text-[10px] uppercase font-black text-blue-900 mb-2">Informasi Pembayaran:</h3>
                    <div class="space-y-1">
                        <p class="text-xs font-bold text-gray-700 print:text-[10px]">{{ $toko->nama_bank }}</p>
                        <p class="text-sm font-black text-blue-900 print:text-xs">{{ $toko->no_rekening }}</p>
                        <p class="text-[10px] text-gray-500 uppercase font-medium print:text-[9px]">A/N {{ $toko->pemilik_rekening }}</p>
                    </div>
                </div>

                <div class="w-full md:w-64 space-y-3 print:w-48">
                    <div class="flex justify-between items-center">
                        <span class="text-[10px] font-bold text-gray-400 uppercase">Subtotal</span>
                        <span class="text-sm font-bold text-gray-800 print:text-xs">Rp{{ number_format($struk->total_harga,0,',','.') }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-[10px] font-bold text-gray-400 uppercase">Sudah Dibayar</span>
                        <span class="text-sm font-bold text-green-600 print:text-xs">Rp{{ number_format($struk->jumlah_bayar,0,',','.') }}</span>
                    </div>
                    <div class="pt-3 border-t-2 border-gray-800 flex justify-between items-center">
                        <span class="text-xs font-black text-gray-800 uppercase italic print:text-[10px]">Sisa Tagihan</span>
                        <span class="text-xl font-black text-blue-900 print:text-lg">Rp{{ number_format($struk->sisa_tagihan,0,',','.') }}</span>
                    </div>
                </div>
            </div>

            {{-- FOOTER --}}
            <div class="mt-16 flex justify-end print:mt-10">
                <div class="text-center w-48">
                    <p class="text-[9px] font-black text-gray-400 uppercase mb-16 print:mb-12">Hormat Kami,</p>
                    <div class="border-b border-gray-200 mb-1"></div>
                    <p class="text-xs font-black text-gray-800 uppercase print:text-[10px]">{{ $toko->nama_toko }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.logo-toko { max-height: 100px; width: auto; object-fit: contain; }

@media print {
    /* 1. Paksa SEMUA elemen memiliki background putih dan teks hitam */
    html, body {
        background-color: white !important;
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
        margin: 0;
        padding: 0;
    }

    body * {
        background-color: white !important;
    }

    /* 2. Sembunyikan elemen navigasi */
    nav, aside, header, footer, .no-print, [role="navigation"] { 
        display: none !important; 
    }

    /* 3. Hilangkan bayangan dan border abu-abu luar pada card */
    .max-w-4xl {
        max-width: 100% !important;
        width: 100% !important;
        margin: 0 !important;
        padding: 0 !important;
    }

    .bg-white {
        background-color: white !important;
        box-shadow: none !important;
        border: none !important;
    }

    /* 4. Pastikan area judul invoice tetap muncul tapi tanpa background abu-abu */
    .bg-gray-50\/50 {
        background-color: transparent !important;
        border-bottom: 1px solid #e5e7eb !important;
    }

    /* 5. Atur margin kertas */
    @page {
        size: A4;
        margin: 1.5cm;
    }

    /* 6. Perbaiki grid agar tidak berantakan saat diprint */
    .grid {
        display: flex !important;
        justify-content: space-between !important;
    }
}
</style>
@endsection