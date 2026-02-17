@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto pb-10 print:pb-0 print:m-0">
    
    {{-- Navigasi Atas --}}
    <div class="flex justify-between items-center mb-6 no-print px-4 md:px-0">
        <a href="{{ route('buat_struk.index') }}" class="text-sm font-bold text-gray-500 hover:text-blue-900 flex items-center gap-2 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Kembali
        </a>
        <button onclick="window.print()" class="bg-blue-900 text-white px-6 py-2.5 rounded-lg font-bold shadow-sm hover:bg-blue-800 transition flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
            Cetak Invoice
        </button>
    </div>

    {{-- Alert jika pengaturan toko kosong --}}
    @if(!$toko->exists)
    <div class="no-print mb-6 bg-amber-50 border border-amber-200 text-amber-700 px-4 py-3 rounded-lg text-sm flex items-center gap-3">
        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
        <div>
            <span class="font-bold">Perhatian:</span> Data toko belum diatur. <a href="{{ route('pengaturan_toko.index') }}" class="underline font-bold">Atur sekarang</a> agar struk terlihat profesional.
        </div>
    </div>
    @endif

    {{-- KARTU INVOICE --}}
    <div class="bg-white border border-gray-200 rounded-lg overflow-hidden shadow-sm print:shadow-none print:border-none">
        
        <div class="p-8 md:p-12 print:p-0">
            
            {{-- HEADER --}}
            <div class="flex justify-between items-start mb-8">
                <div>
                    <h1 class="text-3xl font-black text-blue-900 mb-4 print:text-2xl">INVOICE</h1>
                    <div class="space-y-1">
                        <p class="text-lg font-bold text-gray-900">{{ $toko->nama_toko ?? 'Nama Toko Belum Diatur' }}</p>
                        <p class="text-sm text-gray-500 max-w-xs leading-relaxed">{{ $toko->alamat_toko ?? 'Alamat belum diisi' }}</p>
                        <p class="text-sm text-blue-900 font-bold italic">{{ $toko->no_telepon ?? '-' }}</p>
                    </div>
                </div>
                
                @if($toko->logo_toko)
                    <img src="{{ asset('storage/'.$toko->logo_toko) }}" class="w-24 h-24 object-contain rounded-lg border border-gray-100 p-2">
                @else
                    <div class="w-20 h-20 bg-gray-50 rounded-lg border border-gray-200 flex items-center justify-center text-gray-300 font-bold text-xs">NO LOGO</div>
                @endif
            </div>

            <div class="grid grid-cols-2 gap-8 py-8 border-t border-b border-gray-100 mb-8">
                <div>
                    <p class="text-[10px] uppercase tracking-widest font-black text-gray-400 mb-2">Tagihan Untuk:</p>
                    <p class="text-base font-bold text-gray-900 leading-tight">{{ $struk->nama_pelanggan }}</p>
                    <p class="text-sm text-gray-500 mt-1">{{ $struk->no_telepon }}</p>
                </div>
                <div class="text-right">
                    <p class="text-[10px] uppercase tracking-widest font-black text-gray-400 mb-2">Detail Transaksi:</p>
                    <p class="text-base font-bold text-gray-900">#INV-{{ str_pad($struk->id, 5, '0', STR_PAD_LEFT) }}</p>
                    <p class="text-sm text-gray-500">{{ $struk->created_at->format('d M Y') }}</p>
                </div>
            </div>

            {{-- TABLE --}}
            <div class="mb-8">
                <table class="w-full">
                    <thead>
                        <tr class="border-b-2 border-gray-900">
                            <th class="py-3 text-left text-[11px] font-black uppercase text-gray-900 px-2">Item</th>
                            <th class="py-3 text-center text-[11px] font-black uppercase text-gray-900">Qty</th>
                            <th class="py-3 text-right text-[11px] font-black uppercase text-gray-900 px-2">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($struk->items as $item)
                        <tr>
                            <td class="py-4 px-2">
                                <p class="font-bold text-gray-900 text-sm">{{ $item->produk->nama_produk }}</p>
                                <p class="text-[10px] text-gray-400 uppercase tracking-tighter">ID: {{ $item->produk_id }}</p>
                            </td>
                            <td class="py-4 text-center text-sm font-medium text-gray-600">{{ $item->qty }}</td>
                            <td class="py-4 text-right text-sm font-bold text-gray-900 px-2">
                                Rp{{ number_format($item->subtotal,0,',','.') }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- SUMMARY --}}
            <div class="flex flex-col md:flex-row justify-between items-start gap-8 pt-6 border-t border-gray-100">
                <div class="flex-1 bg-gray-50 p-4 rounded-lg border border-gray-100 w-full md:w-auto">
                    <h3 class="text-[10px] uppercase font-black text-blue-900 mb-2 tracking-widest">Informasi Pembayaran</h3>
                    <p class="text-xs font-bold text-gray-800">{{ $toko->nama_bank ?? '-' }}</p>
                    <p class="text-sm font-black text-blue-900 tracking-tight my-1">{{ $toko->no_rekening ?? '-' }}</p>
                    <p class="text-[10px] text-gray-500 uppercase font-medium">A/N {{ $toko->pemilik_rekening ?? '-' }}</p>
                </div>

                <div class="w-full md:w-64 space-y-2">
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-gray-400 font-bold uppercase text-[10px]">Total Tagihan</span>
                        <span class="font-bold text-gray-900">Rp{{ number_format($struk->total_harga,0,',','.') }}</span>
                    </div>
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-gray-400 font-bold uppercase text-[10px]">Telah Dibayar</span>
                        <span class="font-bold text-green-600">Rp{{ number_format($struk->jumlah_bayar,0,',','.') }}</span>
                    </div>
                    <div class="pt-3 border-t border-gray-200 flex justify-between items-center">
                        <span class="text-blue-900 font-black text-xs uppercase tracking-tighter">Sisa Tagihan</span>
                        <span class="text-2xl font-black text-blue-900">Rp{{ number_format($struk->sisa_tagihan,0,',','.') }}</span>
                    </div>
                </div>
            </div>

            {{-- FOOTER --}}
            <div class="mt-16 flex justify-between items-end">
                <div class="text-[10px] text-gray-400 italic">
                    * Terima kasih telah berbelanja di {{ $toko->nama_toko ?? 'toko kami' }}.
                </div>
                <div class="text-center w-48">
                    <p class="text-[10px] uppercase font-black text-gray-400 mb-10">Hormat Kami,</p>
                    <div class="border-b border-gray-200 w-full mb-1"></div>
                    <p class="font-black text-xs text-gray-900 uppercase tracking-tighter">{{ $toko->nama_toko ?? 'Admin' }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
@media print {
    body { background: white !important; }
    .no-print { display: none !important; }
    .shadow-sm { box-shadow: none !important; }
    .border { border: none !important; }
    .bg-gray-50 { background-color: #f9fafb !important; -webkit-print-color-adjust: exact; }
}
</style>
@endsection