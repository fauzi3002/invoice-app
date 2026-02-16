@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto pb-10 print:pb-0 print:m-0">
    
    <div class="flex justify-between items-center mb-6 no-print px-4 md:px-0">
        <a href="{{ route('buat_struk.index') }}" class="text-sm font-bold text-gray-500 hover:text-blue-900 flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Kembali
        </a>
        <button onclick="window.print()" class="bg-blue-900 text-white px-6 py-2 rounded-xl font-bold shadow-lg shadow-blue-200 hover:bg-blue-800 transition flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
            Cetak Invoice
        </button>
    </div>
    

    {{-- Perbaikan: Menghilangkan shadow & border saat print agar background bersih putih --}}
    <div class="bg-white shadow-2xl md:rounded-3xl overflow-hidden border border-gray-100 print:shadow-none print:border-none print:m-0">
        <h1 class="text-2xl font-bold mt-6 text-center text-blue-900 print:mt-2 print:text-xl">INVOICE</h1>
        
        <div class="p-6 md:p-12 print:p-4 print:pt-0">
            
            {{-- HEADER --}}
            <div class="header-section flex justify-between items-start print:mb-4">
                <div class="flex-1">
                    <p class="text-xl font-bold text-gray-800 print:text-lg">{{ $toko->nama_toko }}</p>
                    <p class="text-sm text-gray-500 max-w-xs print:text-[11px] print:leading-tight">{{ $toko->alamat_toko }}</p>
                    <p class="text-sm text-gray-600 font-medium pt-1 print:text-[11px]">WA: {{ $toko->no_telepon }}</p>
                </div>
                
                @if($toko->logo_toko)
                    <img src="{{ asset('storage/'.$toko->logo_toko) }}" class="logo-toko">
                @endif

            </div>

            <hr class="border-black mb-6 print:mb-3">

            {{-- CLIENT & DETAILS --}}
            <div class="flex justify-between items-end mb-6 print:mb-4 print:flex-row print:flex">
                <div class="flex-1">
                    <h3 class="text-[10px] uppercase tracking-widest font-black text-blue-900 mb-1">Tagihan Untuk:</h3>
                    <p class="text-lg font-bold text-gray-800 print:text-sm leading-tight">{{ $struk->nama_pelanggan }}</p>
                    <p class="text-sm text-gray-500 print:text-[11px]">{{ $struk->no_telepon }}</p>
                </div>

                <div class="text-right flex-1">
                    <p class="text-[10px] uppercase tracking-widest font-black text-gray-400">Nomor & Tanggal</p>
                    <p class="text-base font-bold text-gray-800 print:text-sm">#INV-{{ str_pad($struk->id, 5, '0', STR_PAD_LEFT) }}</p>
                    <p class="text-xs font-bold text-gray-700 print:text-[11px]">{{ $struk->created_at->format('d/m/Y') }}</p>
                </div>
            </div>

            {{-- TABLE --}}
            <div class="mb-6 print:mb-4">
                <table class="w-full border-collapse">
                    <thead>
                        <tr class="bg-blue-900 text-white print:bg-blue-900 print:text-white">
                            <th class="py-2 px-2 text-left text-[10px] uppercase">SL</th>
                            <th class="py-2 px-2 text-left text-[10px] uppercase">Item</th>
                            <th class="py-2 text-center text-[10px] uppercase">Qty</th>
                            <th class="py-2 text-right px-2 text-[10px] uppercase">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($struk->items as $item)
                        <tr class="print:border-b print:border-gray-50">
                            <td class="py-3 px-2 print:py-1.5 font-bold text-gray-800 text-sm print:text-xs">
                                {{ $loop->iteration }}
                            </td>
                            <td class="py-3 px-2 print:py-1.5 font-bold text-gray-800 text-sm print:text-xs">
                                {{ $item->produk->nama_produk }}
                            </td>
                            <td class="py-3 text-center text-sm print:text-xs print:py-1.5">{{ $item->qty }}</td>
                            <td class="py-3 text-right text-sm font-bold text-blue-900 px-2 print:text-xs print:py-1.5">
                                Rp{{ number_format($item->subtotal,0,',','.') }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <hr class="border-black mb-6 print:mb-3">

            {{-- SUMMARY & PAYMENT --}}
            <div class="flex justify-between items-start print:flex print:flex-row">
                <div class="flex-1 space-y-0.5">
                    <h3 class="text-[10px] uppercase font-black text-blue-900 italic">Pembayaran</h3>
                    <p class="text-xs font-bold text-gray-700 print:text-[10px]">{{ $toko->nama_bank }} - {{ $toko->no_rekening }}</p>
                    <p class="text-[10px] text-gray-500 uppercase">A/N {{ $toko->pemilik_rekening }}</p>
                </div>

                <div class="flex-1 max-w-[200px] space-y-1">
                    <div class="flex justify-between text-xs">
                        <span class="text-gray-400 font-bold uppercase text-[9px]">Total</span>
                        <span class="font-bold text-gray-800">Rp{{ number_format($struk->total_harga,0,',','.') }}</span>
                    </div>
                    <div class="flex justify-between text-xs">
                        <span class="text-gray-400 font-bold uppercase text-[9px]">Sudah Dibayar</span>
                        <span class="font-bold text-gray-800">Rp{{ number_format($struk->jumlah_bayar,0,',','.') }}</span>
                    </div>
                    <div class="pt-1 border-t-2 border-blue-900 flex justify-between items-center">
                        <span class="text-blue-900 font-black text-[10px] italic">SISA</span>
                        <span class="text-xl font-black text-blue-900 print:text-lg">Rp{{ number_format($struk->sisa_tagihan,0,',','.') }}</span>
                    </div>
                </div>
            </div>

            {{-- SIGNATURE --}}
            <div class="mt-8 flex justify-end text-center print:mt-6">
                <div class="w-40">
                    <p class="text-[9px] uppercase font-black text-gray-400 mb-12 print:mb-10">Hormat Kami,</p>
                    <p class="font-black text-sm text-gray-800 border-b border-gray-200 pb-1 uppercase">{{ $toko->nama_toko }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.logo-toko {
    max-height: 140px;      /* batas tinggi */
    width: auto;           /* biar proporsional */
    height: auto;
    object-fit: contain;
}


@media print {
    @page {
        size: A4;
        margin: 1cm;
    }
    
    /* Reset background body menjadi putih total */
    body { 
        background-color: white !important; 
        color: black !important;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }

    /* Hilangkan nav/sidebar */
    aside, header, nav, .no-print { display: none !important; }

    /* Pastikan layout utama bersih */
    main, div { 
        background-color: white !important;
        box-shadow: none !important;
    }

    /* Paksa Flexbox bekerja di kertas */
    .print\:flex { display: flex !important; }
    .print\:flex-row { flex-direction: row !important; }
    
    .max-w-4xl { max-width: 100% !important; width: 100% !important; margin: 0 !important; }
}
</style>
@endsection