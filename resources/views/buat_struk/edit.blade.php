
@extends('layouts.app')

@section('content')
<div id="pageWrapper" class="pb-40 md:pb-24 min-h-screen max-w-full mx-auto px-4 md:px-0">
    <form action="{{ route('buat_struk.update', $struk->id) }}" method="POST" id="createStrukForm">
        @csrf
        @method('PUT')

        {{-- Header & Info Status --}}
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">
            <div>
                <h2 class="text-2xl font-bold text-gray-800 tracking-tight">Edit Struk</h2>
                <p class="text-sm text-gray-500 mt-1">Sesuaikan rincian pesanan dan pembayaran pelanggan</p>
            </div>
            
            <div class="flex items-center gap-3 bg-blue-50 px-4 py-3 rounded-lg border border-blue-100">
                <div class="w-2 h-2 bg-blue-500 rounded-full animate-pulse"></div>
                <p class="text-xs font-bold text-blue-600 uppercase tracking-wide">
                    @if($struk->status_pembayaran === 'pending')
                        Mode: Edit Item & Pembayaran
                    @else
                        Mode: Pelunasan Tagihan
                    @endif
                </p>
            </div>
        </div>

        {{-- JIKA STATUS PENDING --}}
        @if($struk->status_pembayaran === 'pending')
            {{-- Button Pilih Produk --}}
            <div class="mb-6">
                <button type="button" id="openModalBtn" class="px-6 py-8 bg-gray-50 w-full border-gray-200 border-2 border-dashed text-blue-900 font-bold rounded-lg hover:bg-blue-50 hover:border-blue-300 transition group">
                    <div class="flex flex-col items-center gap-2">
                        <span class="bg-blue-900 text-white p-2 rounded-lg group-hover:scale-110 transition">+</span>
                        UBAH / TAMBAH ITEM PRODUK
                    </div>
                </button>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6 p-6">
                <div class="space-y-2">
                    <label class="text-[11px] font-bold text-gray-400 uppercase tracking-wider">Jumlah Bayar Baru (Rp)</label>
                    <input type="hidden" name="status" value="pending">
                    <input name="jumlah_bayar" type="number" id="amountPaid" 
                           class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg focus:ring-4 focus:ring-blue-100 focus:border-blue-400 outline-none transition font-bold text-blue-900" 
                           value="{{ $struk->jumlah_bayar }}">
                </div>
            </div>

            {{-- MODAL PRODUK (Hanya untuk Pending) --}}
            <div id="productModal" class="fixed inset-0 md:left-64 bg-gray-900/60 backdrop-blur-sm hidden items-center justify-center z-[60]">
                <div class="bg-white w-full h-full md:h-[85%] md:w-[80%] md:rounded-lg shadow-2xl flex flex-col border border-gray-200 overflow-hidden mx-4">
                    <div class="flex items-center justify-between p-5 border-b border-gray-200">
                        <h2 class="text-lg font-bold text-gray-800 uppercase tracking-tight">Daftar Produk</h2>
                        <button type="button" id="closeModalBtn" class="text-3xl text-gray-400 hover:text-red-500 transition">&times;</button>
                    </div>

                    <div class="p-5 border-b border-gray-100 bg-gray-50/50">
                        <input type="search" id="searchProduct" class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg focus:ring-4 focus:ring-blue-100 focus:border-blue-400 outline-none transition text-sm" placeholder="Cari produk...">
                    </div>

                    <div id="modalBody" class="p-6 overflow-y-auto flex-1 space-y-2">
                        @foreach ($produk as $p)
                            @php
                                $existing = $struk->items->firstWhere('produk_id', $p->id);
                                $qty = $existing ? $existing->qty : 0;
                            @endphp
                            <div class="product-item flex justify-between items-center border border-gray-100 p-4 rounded-lg hover:bg-gray-50 transition shadow-sm" 
                                 data-price="{{ $p->harga_satuan }}" data-id="{{ $p->id }}" data-stock="{{ $p->stok }}">
                                <div class="flex flex-col">
                                    <h1 class="product-name font-bold text-gray-800">{{ $p->nama_produk }}</h1>
                                    <div class="flex items-center gap-3 mt-1">
                                        <span class="text-sm text-blue-600 font-bold font-mono">Rp {{ number_format($p->harga_satuan, 0, ',', '.') }}</span>
                                        <span class="text-[10px] uppercase font-black px-2 py-0.5 rounded border {{ $p->stok <= 0 ? 'bg-red-50 text-red-600 border-red-100' : 'bg-gray-50 text-gray-400 border-gray-200' }}">
                                            Stok: <span class="stock-number">{{ $p->stok }}</span>
                                        </span>
                                    </div>
                                </div>

                                <div class="flex items-center gap-3 bg-white p-1 rounded-lg border border-gray-200">
                                    <button type="button" class="btn-minus w-8 h-8 flex items-center justify-center bg-gray-100 text-gray-600 rounded-md hover:bg-red-500 hover:text-white transition font-bold">-</button>
                                    <input type="number" name="items[{{ $p->id }}][qty]" value="{{ $qty }}" min="0" 
                                           class="product-qty w-16 text-center font-bold text-sm text-gray-800 outline-none bg-white border-none focus:ring-0 appearance-none" style="-moz-appearance: textfield;">
                                    <input type="hidden" name="items[{{ $p->id }}][price]" value="{{ $p->harga_satuan }}">
                                    <button type="button" class="btn-plus w-8 h-8 flex items-center justify-center bg-gray-100 text-gray-600 rounded-md hover:bg-blue-600 hover:text-white transition font-bold">+</button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- STICKY CART BAR (Hanya untuk Pending) --}}
            <div id="cartBar" class="fixed bottom-0 left-0 right-0 md:left-64 bg-white border-t border-gray-200 shadow-[0_-10px_15px_-3px_rgba(0,0,0,0.05)] p-5 z-50">
                <div class="max-w-7xl mx-auto flex items-center justify-between">
                    <div class="flex flex-col">
                        <span id="cartTotalItem" class="text-[10px] font-black uppercase text-gray-400 tracking-widest">0 Item</span>
                        <span id="cartTotalPrice" class="text-xl font-black text-blue-900 tracking-tight">Rp 0</span>
                    </div>
                    <button type="submit" id="mainSubmitBtn" class="px-10 py-3 bg-blue-900 text-white rounded-lg font-bold uppercase text-xs tracking-widest shadow-lg shadow-blue-200 hover:bg-blue-800 transition active:scale-95">
                        Update Struk
                    </button>
                </div>
            </div>
        @endif

        {{-- JIKA STATUS PARTIAL --}}
        @if($struk->status_pembayaran === 'partial')
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6 overflow-hidden">
                <div class="p-5 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
                    <h2 class="text-xs font-black text-gray-500 uppercase tracking-widest">Ringkasan Tagihan</h2>
                    <span class="px-3 py-1 bg-orange-100 text-orange-600 text-[10px] font-bold rounded-full uppercase">Belum Lunas</span>
                </div>
                <div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="p-4 rounded-lg bg-gray-50 border border-gray-100">
                        <p class="text-[10px] font-bold text-gray-400 uppercase mb-1">Total Harga</p>
                        <p class="font-bold text-gray-800">Rp {{ number_format($struk->total_harga, 0, ',', '.') }}</p>
                    </div>
                    <div class="p-4 rounded-lg bg-blue-50 border border-blue-100">
                        <p class="text-[10px] font-bold text-blue-400 uppercase mb-1">Sudah Dibayar</p>
                        <p class="font-bold text-blue-700">Rp {{ number_format($struk->jumlah_bayar, 0, ',', '.') }}</p>
                    </div>
                    <div class="p-4 rounded-lg bg-red-50 border border-red-100">
                        <p class="text-[10px] font-bold text-red-400 uppercase mb-1">Sisa Tagihan</p>
                        <p class="font-bold text-red-600">Rp {{ number_format($struk->sisa_tagihan, 0, ',', '.') }}</p>
                    </div>
                </div>
                
                <div class="p-6 border-t border-gray-100">
                    <div class="space-y-2">
                        <label class="text-[11px] font-bold text-gray-400 uppercase tracking-wider">Tambah Pembayaran (Rp)</label>
                        <input name="tambah_bayar" type="number" id="amountPaid" 
                               max="{{ $struk->sisa_tagihan }}" min="0"
                               class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg focus:ring-4 focus:ring-blue-100 focus:border-blue-400 outline-none transition font-bold text-green-600" 
                               placeholder="Masukkan nominal pelunasan...">
                        <p class="text-[10px] text-gray-400 mt-1 italic">*Maksimal pembayaran sesuai sisa tagihan</p>
                    </div>
                    
                    <button type="submit" class="w-full mt-6 py-4 bg-blue-900 text-white rounded-lg font-bold uppercase text-xs tracking-widest shadow-lg hover:bg-blue-800 transition">
                        Simpan Pelunasan
                    </button>
                </div>
            </div>
        @endif

        {{-- JIKA STATUS LUNAS --}}
        @if($struk->status_pembayaran === 'lunas')
            <div class="bg-green-50 border border-green-200 rounded-lg p-8 text-center">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-green-100 text-green-600 rounded-full mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                </div>
                <h3 class="text-lg font-bold text-green-800">Struk Telah Lunas</h3>
                <p class="text-sm text-green-600 mt-1">Transaksi ini sudah selesai dan tidak dapat diubah kembali.</p>
                <a href="{{ route('buat_struk.index') }}" class="inline-block mt-6 px-6 py-2 border border-green-600 text-green-600 rounded-lg text-xs font-bold uppercase hover:bg-green-600 hover:text-white transition">Kembali ke Daftar</a>
            </div>
        @endif
    </form>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {

    /* ================================
        ELEMENT REFERENCES
    ================================= */
    const modal = document.getElementById("productModal");
    const openBtn = document.getElementById("openModalBtn");
    const closeBtn = document.getElementById("closeModalBtn");
    const cartBar = document.getElementById("cartBar");
    const wrapper = document.getElementById("pageWrapper");
    const modalBody = document.getElementById("modalBody");

    const cartTotalItem = document.getElementById("cartTotalItem");
    const cartTotalPrice = document.getElementById("cartTotalPrice");
    
    // Sesuaikan selector status dengan name="status" karena ID paymentStatus mungkin tidak ada
    const statusSelect = document.querySelector('input[name="status"]'); 
    const amountPaidInput = document.getElementById("amountPaid");
    const searchInput = document.getElementById("searchProduct");

    const mainSubmitBtn =
        document.querySelector('#cartBar button[type="submit"]') ||
        document.querySelector("#cartBar button");

    let totalPrice = 0;

    /* ================================
        MODAL LOGIC
    ================================= */
    function openModal() {
        modal.classList.remove("hidden");
        modal.classList.add("flex");

        if (mainSubmitBtn) {
            mainSubmitBtn.setAttribute("type", "button");
            mainSubmitBtn.innerText = "Simpan & Lanjut";
            mainSubmitBtn.onclick = function(e) {
                e.preventDefault();
                closeModal();
            };
        }
    }

    function closeModal() {
        modal.classList.add("hidden");
        modal.classList.remove("flex");

        if (mainSubmitBtn) {
            mainSubmitBtn.setAttribute("type", "submit");
            mainSubmitBtn.innerText = "Simpan";
            mainSubmitBtn.onclick = null;
        }
    }

    openBtn.addEventListener("click", openModal);
    closeBtn.addEventListener("click", closeModal);

    modal.addEventListener("click", (e) => {
        if (e.target === modal) closeModal();
    });

    document.addEventListener("keydown", (e) => {
        if (e.key === "Escape") closeModal();
    });

    /* ================================
        RESPONSIVE PADDING
    ================================= */
    function adjustPadding() {
        const cartHeight = cartBar.offsetHeight;
        wrapper.style.paddingBottom = cartHeight + "px";
        modalBody.style.paddingBottom = cartHeight + "px";
    }

    adjustPadding();
    window.addEventListener("resize", adjustPadding);

    /* ================================
        PAYMENT LOGIC (REVISED)
    ================================= */
    
    // 1. Ambil data sisa tagihan & status awal dari PHP untuk referensi logic
    const sisaTagihanAwal = {{ (int)$struk->sisa_tagihan }};
    const statusAwal = "{{ $struk->status_pembayaran }}";

    function autoSetStatus() {
        if (!statusSelect || statusAwal === 'partial') return;

        const amount = parseInt(amountPaidInput.value) || 0;

        if (amount <= 0) {
            statusSelect.value = "pending";
        } else if (amount < totalPrice) {
            statusSelect.value = "partial";
        } else {
            statusSelect.value = "lunas";
        }
    }

    if (amountPaidInput) {
        amountPaidInput.addEventListener("input", function () {
            let amount = parseInt(this.value) || 0;

            if (statusAwal === 'partial') {
                // PAKSA ke sisa tagihan jika input lebih besar
                if (amount > sisaTagihanAwal) {
                    this.value = sisaTagihanAwal;
                }
            } else {
                // Untuk status Pending, gunakan totalPrice dari keranjang
                if (amount > totalPrice) {
                    this.value = totalPrice;
                }
                autoSetStatus();
            }
        });
    }

    /* ================================
        CART CALCULATION
    ================================= */
    function updateCart() {
        let totalItem = 0;
        totalPrice = 0;

        document.querySelectorAll(".product-item").forEach(item => {
            const qty = parseInt(item.querySelector(".product-qty").value) || 0;
            const price = parseInt(item.dataset.price);

            totalItem += qty;
            totalPrice += qty * price;
        });

        cartTotalItem.textContent = totalItem + " Item";
        cartTotalPrice.textContent = "Rp " + totalPrice.toLocaleString("id-ID");

        autoSetStatus();
    }

    /* ================================
        PRODUCT & STOCK LOGIC
    ================================= */
    document.querySelectorAll(".product-item").forEach(item => {
        const btnPlus = item.querySelector(".btn-plus");
        const btnMinus = item.querySelector(".btn-minus");
        const qtyInput = item.querySelector(".product-qty");
        const stockElement = item.querySelector(".stock-number");

        // Logika Edit: Stok Maksimal = Stok Database + Qty yang sudah dipesan sebelumnya
        const initialQty = parseInt(qtyInput.value) || 0;
        const dbStock = parseInt(item.dataset.stock); 
        const maxAvailableStock = dbStock + initialQty; 

        function updateStockDisplay() {
            const currentQty = parseInt(qtyInput.value) || 0;
            const remainingStock = maxAvailableStock - currentQty;

            stockElement.textContent = remainingStock;

            // Atur status tombol dan warna teks stok
            if (remainingStock <= 0) {
                btnPlus.disabled = true;
                stockElement.parentElement.classList.remove("text-gray-400");
                stockElement.parentElement.classList.add("text-red-500");
            } else {
                btnPlus.disabled = false;
                stockElement.parentElement.classList.remove("text-red-500");
                stockElement.parentElement.classList.add("text-gray-400");
            }
        }

        btnPlus.addEventListener("click", () => {
            let qty = parseInt(qtyInput.value) || 0;
            if (qty < maxAvailableStock) {
                qtyInput.value = qty + 1;
                updateStockDisplay();
                updateCart();
            }
        });

        btnMinus.addEventListener("click", () => {
            let qty = parseInt(qtyInput.value) || 0;
            if (qty > 0) {
                qtyInput.value = qty - 1;
                updateStockDisplay();
                updateCart();
            }
        });

        qtyInput.addEventListener("input", () => {
            let qty = parseInt(qtyInput.value) || 0;
            if (qty > maxAvailableStock) qtyInput.value = maxAvailableStock;
            if (qty < 0) qtyInput.value = 0;

            updateStockDisplay();
            updateCart();
        });

        // Jalankan saat pertama kali untuk menyesuaikan tampilan tiap produk
        updateStockDisplay();
    });

    /* ================================
        SEARCH PRODUCT
    ================================= */
    searchInput.addEventListener("input", function () {
        const keyword = this.value.toLowerCase();

        document.querySelectorAll(".product-item").forEach(item => {
            const productName = item.querySelector(".product-name").textContent.toLowerCase();
            item.style.display = productName.includes(keyword) ? "flex" : "none";
        });
    });

    // --- KUNCI PERBAIKAN ---
    // Jalankan kalkulasi keranjang saat halaman pertama kali dibuka
    updateCart();

});
</script>
@endsection