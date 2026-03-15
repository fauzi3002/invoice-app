@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 pt-20 md:pt-6 pb-24">
    
    {{-- Action Buttons --}}
    <div class="flex flex-col md:flex-row justify-between items-center mb-6 no-print gap-4">
        <a href="{{ route('buat_struk.index') }}" class="text-sm font-bold text-gray-500 hover:text-blue-900 flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Kembali
        </a>
        
        <div class="flex items-center gap-3">
            {{-- Tombol Cetak --}}
            <button onclick="window.print()" class="bg-blue-900 text-white px-5 py-2.5 rounded-xl font-bold hover:bg-blue-800 transition flex items-center gap-2 shadow-lg shadow-blue-100">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                </svg>
                Cetak Invoice
            </button>

            {{-- Tombol Download --}}
            <button onclick="openDownloadModal('{{ asset('storage/struks/struk-'.$struk->id.'.pdf') }}')" class="bg-emerald-600 text-white px-5 py-2.5 rounded-xl font-bold hover:bg-emerald-700 transition flex items-center gap-2 shadow-lg shadow-emerald-100">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                </svg>
                Download PDF
            </button>
        </div>
    </div>
    
    {{-- Invoice Card --}}
    <div id="invoice-card" class="bg-white rounded-lg border border-gray-200 overflow-hidden print:border-none print:m-0 print:shadow-none">
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
                <div class="text-center w-48 relative">
                    <p class="text-[9px] font-black text-gray-400 uppercase mb-4 print:mb-2">Hormat Kami,</p>
                    
                    {{-- Area Tanda Tangan --}}
                    <div class="flex justify-center items-center h-24 mb-2">
                        @if($toko->tanda_tangan)
                            {{-- mix-blend-multiply agar background putih di ttd transparan menyatu dengan kertas --}}
                            <img src="{{ asset('storage/'.$toko->tanda_tangan) }}" 
                                class="max-h-24 w-auto object-contain mix-blend-multiply" 
                                alt="Tanda Tangan">
                        @else
                            {{-- Space kosong jika ttd belum diatur --}}
                            <div class="h-20"></div>
                        @endif
                    </div>

                    <div class="border-b border-gray-200 mb-1"></div>
                    <p class="text-xs font-black text-gray-800 uppercase print:text-[10px]">{{ $toko->nama_toko }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- MODAL DOWNLOAD --}}
<div id="downloadModal" class="fixed inset-0 z-[99] hidden items-center justify-center p-4">
    <div class="fixed inset-0 bg-black/50 backdrop-blur-sm transition-opacity" onclick="closeModal()"></div>
    <div class="relative bg-white rounded-xl shadow-xl max-w-sm w-full overflow-hidden border border-gray-200 transform transition-all">
        <div class="p-6 text-center">
            <div class="w-16 h-16 bg-blue-50 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                </svg>
            </div>
            <h3 class="text-lg font-bold text-gray-800 mb-2">Simpan Struk</h3>
            <p class="text-sm text-gray-500 mb-6">Silakan masukkan nama file sebelum mengunduh.</p>
            <div class="mb-6">
                <input type="text" id="customFileName" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-sm font-medium focus:outline-none focus:ring-2 focus:ring-blue-500 text-center">
            </div>
            <div class="flex gap-3">
                <button type="button" onclick="closeModal()" class="flex-1 px-4 py-2.5 bg-gray-100 text-gray-600 rounded-lg text-sm font-bold hover:bg-gray-200 transition">Batal</button>
                <button type="button" id="confirmDownload" class="flex-1 px-4 py-2.5 bg-blue-900 text-white rounded-lg text-sm font-bold hover:bg-blue-700 shadow-md transition active:scale-95">Download</button>
            </div>
        </div>
    </div>
</div>

<script>
    let currentFileUrl = '';

    function openDownloadModal(url) {
        currentFileUrl = url + '?t=' + new Date().getTime();
        const modal = document.getElementById('downloadModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.getElementById('customFileName').value = "Struk_{{ $struk->nama_pelanggan }}_{{ date('dmY') }}";
        setTimeout(() => document.getElementById('customFileName').focus(), 100);
    }

    function closeModal() {
        const modal = document.getElementById('downloadModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    document.getElementById('confirmDownload').addEventListener('click', function() {
        const fileName = document.getElementById('customFileName').value || 'struk';
        const link = document.createElement('a');
        link.href = currentFileUrl;
        link.download = fileName + ".pdf";
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        closeModal();
    });

    window.onclick = function(event) {
        const modal = document.getElementById('downloadModal');
        if (event.target == modal) closeModal();
    }
</script>

<style>
.logo-toko { max-height: 100px; width: auto; object-fit: contain; }

@media print {

    @page {
        size: A4;
        margin: 1cm;
    }

    html, body {
        background: white !important;
    }

    body * {
        background-color: transparent !important;
    }

    #invoice-card {
        background: white !important;
    }

    nav, aside, header, footer, .no-print, [role="navigation"], #downloadModal { 
        display: none !important; 
    }

    .container {
        width: 100% !important;
        max-width: none !important;
        padding: 0 !important;
        margin: 0 !important;
    }

}

</style>
@endsection