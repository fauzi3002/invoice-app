@extends('layouts.app')

@section('content')
<div id="pageWrapper" class="container mx-auto px-4 pt-20 md:pt-6 pb-24">
    <form action="{{ route('buat_struk.update', $struk->id) }}" method="POST" id="createStrukForm">
        @csrf
        @method('PUT')

        {{-- Header Section --}}
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">
            <div>
                <h2 class="text-2xl font-extrabold text-gray-900 tracking-tight">Edit Struk</h2>
                <p class="text-sm mt-1 text-gray-500 mt-1">Sesuaikan rincian pesanan dan pembayaran pelanggan</p>
            </div>
            
            <div class="flex items-center gap-3 bg-blue-50 px-4 py-3 rounded-xl border border-blue-100">
                <div class="w-2 h-2 bg-blue-500 rounded-full animate-pulse"></div>
                <p class="text-xs font-bold text-blue-600 uppercase tracking-widest">
                    @if($struk->status_pembayaran === 'pending')
                        Mode: Edit Item & Pembayaran
                    @else
                        Mode: Pelunasan Tagihan
                    @endif
                </p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {{-- KOLOM KIRI: FORM UTAMA --}}
            <div class="lg:col-span-2 space-y-6">
                
                {{-- JIKA STATUS PENDING: BISA UBAH PRODUK --}}
                @if($struk->status_pembayaran === 'pending')
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 space-y-4">
                        <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Langkah 1: Pilih Produk</label>
                        <button type="button" id="openModalBtn" class="w-full flex flex-col items-center justify-center gap-3 px-6 py-8 bg-gray-50 border-2 border-dashed border-blue-200 rounded-xl hover:border-blue-400 hover:bg-blue-50 transition-all group">
                            <span class="bg-blue-900 text-white w-10 h-10 flex items-center justify-center rounded-full group-hover:scale-110 transition font-bold text-xl">+</span>
                            <span class="font-bold text-blue-900 uppercase text-sm">Ubah / Tambah Item Produk</span>
                        </button>
                    </div>

                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <div class="space-y-2">
                            <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Langkah 2: Update Jumlah Bayar (Rp)</label>
                            <input type="hidden" name="status" value="pending">
                            <input type="text" id="amountPaidVisual" class="w-full px-4 py-4 bg-blue-50/50 border-2 border-blue-100 rounded-xl focus:border-blue-500 outline-none transition text-2xl font-bold text-blue-900" value="{{ $struk->jumlah_bayar }}">
                            <input type="hidden" name="jumlah_bayar" id="amountPaidActual" value="{{ $struk->jumlah_bayar }}">
                        </div>
                    </div>
                @endif

                {{-- JIKA STATUS PARTIAL: HANYA PELUNASAN --}}
                @if($struk->status_pembayaran === 'partial')
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                        <div class="p-5 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
                            <h2 class="text-xs font-black text-gray-500 uppercase tracking-widest">Input Pelunasan</h2>
                            <span class="px-3 py-1 bg-orange-100 text-orange-600 text-[10px] font-bold rounded-full uppercase">Belum Lunas</span>
                        </div>
                        <div class="p-6">
                            <div class="space-y-2">
                                <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Tambah Pembayaran (Rp)</label>
                                <input type="text" id="amountPaidVisual" class="w-full px-4 py-4 bg-green-50/50 border-2 border-green-100 rounded-xl focus:border-green-500 outline-none transition text-2xl font-bold text-green-700" placeholder="0">
                                <input type="hidden" name="tambah_bayar" id="amountPaidActual" value="0">
                                <p class="text-[10px] text-gray-400 mt-2 italic font-medium uppercase tracking-tight">*Maksimal pelunasan: Rp {{ number_format($struk->sisa_tagihan, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- JIKA LUNAS --}}
                @if($struk->status_pembayaran === 'lunas')
                    <div class="bg-green-50 border-2 border-green-100 rounded-2xl p-10 text-center">
                        <div class="inline-flex items-center justify-center w-20 h-20 bg-green-100 text-green-600 rounded-full mb-4">
                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                        <h3 class="text-xl font-bold text-green-800">Struk Telah Lunas</h3>
                        <p class="text-gray-600 mt-2">Transaksi ini sudah selesai dan tidak dapat diubah kembali.</p>
                        <a href="{{ route('buat_struk.index') }}" class="inline-block mt-8 px-8 py-3 bg-green-600 text-white rounded-xl font-bold uppercase text-xs tracking-widest hover:bg-green-700 transition shadow-lg shadow-green-200">Kembali ke Daftar</a>
                    </div>
                @endif
            </div>

            {{-- KOLOM KANAN: RINGKASAN & ACTION --}}
            <div class="lg:col-span-1">
                @if($struk->status_pembayaran !== 'lunas')
                <div class="sticky top-6 space-y-4">
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                        <div class="p-5 bg-gray-50 border-b border-gray-100">
                            <h3 class="text-xs font-black text-gray-400 uppercase tracking-widest">Ringkasan Tagihan</h3>
                        </div>
                        <div class="p-6 space-y-4">
                            @if($struk->status_pembayaran === 'pending')
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-gray-500">Total Item</span>
                                    <span id="cartTotalItem" class="font-bold text-gray-800">0 Item</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-gray-500">Total Harga</span>
                                    <span id="cartTotalPrice" class="text-xl font-black text-blue-900">Rp 0</span>
                                </div>
                            @else
                                <div class="space-y-3">
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-500">Total Tagihan:</span>
                                        <span class="font-bold">Rp {{ number_format($struk->total_harga, 0, ',', '.') }}</span>
                                    </div>
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-500">Sudah Bayar:</span>
                                        <span class="font-bold text-blue-600">Rp {{ number_format($struk->jumlah_bayar, 0, ',', '.') }}</span>
                                    </div>
                                    <div class="flex justify-between pt-3 border-t border-dashed">
                                        <span class="text-sm font-bold text-gray-700">Sisa:</span>
                                        <span class="text-lg font-black text-red-600">Rp {{ number_format($struk->sisa_tagihan, 0, ',', '.') }}</span>
                                    </div>
                                </div>
                            @endif

                            <button type="submit" id="btnSubmitEdit" class="w-full mt-4 py-4 bg-blue-900 text-white rounded-xl font-bold uppercase text-xs tracking-widest shadow-lg shadow-blue-200 hover:bg-blue-800 transition active:scale-95">
                                {{ $struk->status_pembayaran === 'pending' ? 'Update Struk' : 'Simpan Pelunasan' }}
                            </button>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>

        {{-- MODAL PRODUK (Perbaikan posisi tengah) --}}
        @if($struk->status_pembayaran === 'pending')
        <div id="productModal" class="fixed inset-0 z-[100] hidden items-center justify-center p-0 md:p-6">
            <div class="absolute inset-0 bg-gray-900/70 backdrop-blur-sm" onclick="this.parentElement.classList.replace('flex', 'hidden')"></div>
            <div class="relative bg-white w-full h-full md:h-[90%] md:max-w-4xl md:rounded-2xl shadow-2xl flex flex-col overflow-hidden mx-auto">
                <div class="flex items-center justify-between p-5 border-b">
                    <h2 class="text-lg font-bold text-gray-800 uppercase">Daftar Produk</h2>
                    <button type="button" id="closeModalBtn" class="text-3xl text-gray-400 hover:text-red-500">&times;</button>
                </div>
                <div class="p-4 bg-gray-50">
                    <input type="search" id="searchProduct" class="w-full px-4 py-3 bg-white border border-gray-200 rounded-xl outline-none" placeholder="Cari produk...">
                </div>
                <div id="modalBody" class="flex-1 overflow-y-auto p-4 space-y-3">
                    @foreach ($produk as $p)
                        @php
                            $existing = $struk->items->firstWhere('produk_id', $p->id);
                            $qty = $existing ? $existing->qty : 0;
                        @endphp
                        <div class="product-item flex flex-col sm:flex-row sm:items-center justify-between bg-white border border-gray-100 p-4 rounded-xl shadow-sm" data-price="{{ $p->harga_satuan }}" data-id="{{ $p->id }}" data-stock="{{ $p->stok }}">
                            <div class="flex-1">
                                <h4 class="product-name font-bold text-gray-800">{{ $p->nama_produk }}</h4>
                                <div class="flex items-center gap-3">
                                    <span class="text-blue-600 font-bold text-sm">Rp {{ number_format($p->harga_satuan, 0, ',', '.') }}</span>
                                    <span class="text-[10px] px-2 py-0.5 rounded border bg-gray-50 text-gray-400 font-bold uppercase">Stok: <span class="stock-number">{{ $p->stok }}</span></span>
                                </div>
                            </div>
                            <div class="flex items-center justify-center bg-gray-50 rounded-lg p-1 border mt-3 sm:mt-0">
                                <button type="button" class="btn-minus w-8 h-8 bg-white border rounded shadow-sm hover:bg-red-500 hover:text-white transition">-</button>
                                <input type="number" name="items[{{ $p->id }}][qty]" value="{{ $qty }}" min="0" class="product-qty w-12 text-center font-bold bg-transparent border-none focus:ring-0">
                                <input type="hidden" name="items[{{ $p->id }}][price]" value="{{ $p->harga_satuan }}">
                                <button type="button" class="btn-plus w-8 h-8 bg-white border rounded shadow-sm hover:bg-blue-600 hover:text-white transition">+</button>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="p-4 border-t">
                    <button type="button" onclick="document.getElementById('productModal').classList.replace('flex', 'hidden')" class="w-full py-3 bg-blue-900 text-white rounded-xl font-bold uppercase text-xs tracking-widest">Selesai & Tutup</button>
                </div>
            </div>
        </div>
        @endif
    </form>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const statusAwal = "{{ $struk->status_pembayaran }}";
    const sisaTagihanAwal = {{ (int)$struk->sisa_tagihan }};
    
    // Elements
    const amountPaidVisual = document.getElementById("amountPaidVisual");
    const amountPaidActual = document.getElementById("amountPaidActual");
    const cartTotalItem = document.getElementById("cartTotalItem");
    const cartTotalPrice = document.getElementById("cartTotalPrice");
    const modal = document.getElementById("productModal");
    const openBtn = document.getElementById("openModalBtn");
    const closeBtn = document.getElementById("closeModalBtn");

    let totalGlobalPrice = 0;

    // Format Rupiah
    function formatRupiah(angka) {
        let number_string = angka.replace(/[^,\d]/g, '').toString(),
            split = number_string.split(','),
            sisa = split[0].length % 3,
            rupiah = split[0].substr(0, sisa),
            ribuan = split[0].substr(sisa).match(/\d{3}/gi);
        if (ribuan) {
            let separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }
        return rupiah;
    }

    function cleanNumber(value) {
        return parseInt(value.replace(/\./g, '')) || 0;
    }

    // Modal Control
    if(openBtn) openBtn.onclick = () => modal.classList.replace("hidden", "flex");
    if(closeBtn) closeBtn.onclick = () => modal.classList.replace("flex", "hidden");

    // Cart Logic
    function updateCart() {
        if (statusAwal !== 'pending') return;
        
        let totalItem = 0;
        totalGlobalPrice = 0;

        document.querySelectorAll(".product-item").forEach(item => {
            const qty = parseInt(item.querySelector(".product-qty").value) || 0;
            const price = parseInt(item.dataset.price);
            totalItem += qty;
            totalGlobalPrice += (qty * price);
        });

        if(cartTotalItem) cartTotalItem.textContent = totalItem + " Item";
        if(cartTotalPrice) cartTotalPrice.textContent = "Rp " + totalGlobalPrice.toLocaleString("id-ID");

        // Limit bayar agar tidak melebihi total baru
        let currentPay = cleanNumber(amountPaidVisual.value);
        if (currentPay > totalGlobalPrice) {
            amountPaidVisual.value = formatRupiah(totalGlobalPrice.toString());
            amountPaidActual.value = totalGlobalPrice;
        }
    }

    // Event listeners for +/- buttons
    document.querySelectorAll(".product-item").forEach(item => {
        const qtyInput = item.querySelector(".product-qty");
        const btnPlus = item.querySelector(".btn-plus");
        const btnMinus = item.querySelector(".btn-minus");
        const dbStock = parseInt(item.dataset.stock);
        const initialQty = parseInt(qtyInput.value) || 0;
        const maxStock = dbStock + initialQty;

        const syncStock = () => {
            const q = parseInt(qtyInput.value) || 0;
            item.querySelector(".stock-number").textContent = maxStock - q;
        };

        btnPlus.onclick = () => { if(qtyInput.value < maxStock) { qtyInput.value++; syncStock(); updateCart(); } };
        btnMinus.onclick = () => { if(qtyInput.value > 0) { qtyInput.value--; syncStock(); updateCart(); } };
        syncStock();
    });

    // Payment Limit Logic
    if (amountPaidVisual) {
        amountPaidVisual.value = formatRupiah(amountPaidVisual.value);
        amountPaidVisual.addEventListener("keyup", function() {
            this.value = formatRupiah(this.value);
            let val = cleanNumber(this.value);
            let limit = (statusAwal === 'partial') ? sisaTagihanAwal : totalGlobalPrice;

            if (val > limit) {
                val = limit;
                this.value = formatRupiah(val.toString());
            }
            amountPaidActual.value = val;
        });
    }

    // Search Logic
    const searchInput = document.getElementById("searchProduct");
    if (searchInput) {
        searchInput.oninput = function() {
            const k = this.value.toLowerCase();
            document.querySelectorAll(".product-item").forEach(item => {
                const name = item.querySelector(".product-name").textContent.toLowerCase();
                item.style.display = name.includes(k) ? "flex" : "none";
            });
        };
    }

    updateCart();

    // --- LOGIKA LOADING SUBMIT ---
    const createStrukForm = document.getElementById('createStrukForm');
    const btnSubmit = document.getElementById('btnSubmitEdit');

    if (createStrukForm && btnSubmit) {
        createStrukForm.onsubmit = function(e) {
            // 1. Validasi khusus jika status masih PENDING (Harus ada produk)
            if (statusAwal === 'pending') {
                let totalQty = 0;
                document.querySelectorAll(".product-qty").forEach(input => {
                    totalQty += (parseInt(input.value) || 0);
                });

                if (totalQty <= 0) {
                    e.preventDefault();
                    toastr.error("Pilih minimal 1 produk terlebih dahulu!");
                    return false;
                }
            }

            // 2. Efek Loading
            btnSubmit.classList.add('btn-loading');
            btnSubmit.disabled = true;

            // Optional: Jika ingin tombol Batal di modal juga mati saat proses
            const closeBtnModal = document.getElementById('closeModalBtn');
            if(closeBtnModal) closeBtnModal.disabled = true;

            return true;
        };
    }
});
</script>
@endsection