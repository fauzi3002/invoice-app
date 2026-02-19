@extends('layouts.app')

@section('content')
<div id="pageWrapper" class="pb-40 md:pb-24 min-h-screen max-w-full mx-auto px-4 md:px-0">
    <form method="POST" action="{{ route('buat_struk.store') }}" id="createStrukForm">
        @csrf
        @method('POST')
        
        {{-- Header --}}
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">
            <div>
                <h2 class="text-2xl font-bold text-gray-800 tracking-tight">Buat Struk</h2>
                <p class="text-sm text-gray-500 mt-1">Buat struk pembayaran untuk pelanggan Anda</p>
            </div>
        </div>

        {{-- Button Pilih Produk --}}
        <div class="mb-6">
            <button type="button" id="openModalBtn" class="px-6 py-8 bg-gray-50 w-full border-gray-200 border-2 border-dashed text-blue-900 font-bold rounded-lg hover:bg-blue-50 hover:border-blue-300 transition group">
                <div class="flex flex-col items-center gap-2">
                    <span class="bg-blue-900 text-white px-2 py-1 rounded-lg group-hover:scale-110 transition">+</span>
                    PILIH PRODUK DARI DAFTAR
                </div>
            </button>
        </div>

        {{-- Form Informasi Pelanggan --}}
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6 overflow-hidden">
            <div class="p-5 border-b border-gray-200 bg-gray-50">
                <h2 class="text-xs font-black text-gray-500 uppercase tracking-widest">Informasi Pelanggan</h2>
            </div>
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label class="text-[11px] font-bold text-gray-400 uppercase tracking-wider">Nama Pelanggan</label>
                    <input name="customer_name" type="text" class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg focus:ring-4 focus:ring-blue-100 focus:border-blue-400 outline-none transition placeholder:text-gray-300" placeholder="Contoh: Budi Santoso" required>
                </div>
                <div class="space-y-2">
                    <label class="text-[11px] font-bold text-gray-400 uppercase tracking-wider">No. Telp</label>
                    <input name="customer_phone" type="text" class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg focus:ring-4 focus:ring-blue-100 focus:border-blue-400 outline-none transition placeholder:text-gray-300" placeholder="0812xxxx" required>
                </div>
                <div class="md:col-span-2 space-y-2">
                    <label class="text-[11px] font-bold text-gray-400 uppercase tracking-wider">Alamat</label>
                    <textarea name="customer_address" rows="3" class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg focus:ring-4 focus:ring-blue-100 focus:border-blue-400 outline-none transition resize-none placeholder:text-gray-300" placeholder="Masukan alamat lengkap" required></textarea>
                </div>
            </div>
        </div>

        {{-- Pembayaran --}}
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6 p-6">
            <div class="hidden mb-2 space-y-2">
                <label class="text-sm font-semibold text-gray-700">Status:</label>
                <select name="status" id="paymentStatus" class="ml-2 px-4 py-2 w-full border border-gray-200 rounded-lg outline-none transition" required>
                    <option value="pending">Pending</option>
                    <option value="partial">Partial</option>
                    <option value="lunas">Lunas</option>
                </select>
            </div>
            <div class="space-y-2">
                <label class="text-[11px] font-bold text-gray-400 uppercase tracking-wider">Jumlah Bayar (Rp)</label>
                <input name="amount_paid" type="number" id="amountPaid" class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg focus:ring-4 focus:ring-blue-100 focus:border-blue-400 outline-none transition font-bold text-blue-900" placeholder="0" value="0">
            </div>
        </div>

        {{-- MODAL PRODUK --}}
        <div id="productModal" class="fixed inset-0 md:left-64 bg-gray-900/60 backdrop-blur-sm hidden items-center justify-center z-[60]">
            <div class="bg-white w-full h-full md:h-[85%] md:w-[80%] md:rounded-lg shadow-2xl flex flex-col border border-gray-200 overflow-hidden mx-4">
                <div class="flex items-center justify-between p-5 border-b border-gray-200">
                    <h2 class="text-lg font-bold text-gray-800 uppercase tracking-tight">Pilih Produk</h2>
                    <button type="button" id="closeModalBtn" class="text-3xl text-gray-400 hover:text-red-500 transition">&times;</button>
                </div>

                <div class="p-5 border-b border-gray-100 bg-gray-50/50">
                    <input type="search" id="searchProduct" class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg focus:ring-4 focus:ring-blue-100 focus:border-blue-400 outline-none transition text-sm" placeholder="Cari berdasarkan nama produk...">
                </div>

                <div id="modalBody" class="p-6 overflow-y-auto flex-1 space-y-2">
                    @foreach ($produk as $p)
                        <div class="product-item flex justify-between items-center border border-gray-100 p-4 rounded-lg hover:bg-gray-50 transition shadow-sm" data-price="{{ $p->harga_satuan }}" data-id="{{ $p->id }}" data-stock="{{ $p->stok }}">
                            <div class="flex flex-col">
                                <h1 class="product-name font-bold text-gray-800">{{ $p->nama_produk }}</h1>
                                <div class="flex items-center mt-1">
                                    <span class="text-sm text-blue-600 font-bold font-mono">Rp {{ number_format($p->harga_satuan, 0, ',', '.') }}</span>
                                </div>
                                <span class="text-[10px] uppercase font-black px-2 py-0.5 rounded border {{ $p->stok <= 0 ? 'bg-red-50 text-red-600 border-red-100' : 'bg-gray-50 text-gray-400 border-gray-200' }}">
                                        Stok: <span class="stock-number">{{ $p->stok }}</span>
                                </span>
                            </div>

                            <div class="flex items-center bg-white rounded-lg border border-gray-200">
                                <button type="button" class="btn-minus w-8 h-8 flex items-center justify-center bg-gray-100 text-gray-600 rounded-md hover:bg-red-500 hover:text-white transition font-bold">-</button>
                                {{-- Ganti baris input Anda dengan ini --}}
<input type="number" 
       name="items[{{ $p->id }}][qty]" 
       value="0" 
       min="0" 
       class="product-qty w-12 text-center font-bold text-sm text-gray-800 outline-none bg-white border-none focus:ring-0 appearance-none" 
       style="-moz-appearance: textfield;">
                                <input type="hidden" name="items[{{ $p->id }}][price]" value="{{ $p->harga_satuan }}">
                                <button type="button" class="btn-plus w-8 h-8 flex items-center justify-center bg-gray-100 text-gray-600 rounded-md hover:bg-blue-600 hover:text-white transition font-bold">+</button>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- STICKY CART BAR --}}
        <div id="cartBar" class="fixed bottom-0 left-0 right-0 md:left-64 bg-white border-t border-gray-200 shadow-[0_-10px_15px_-3px_rgba(0,0,0,0.05)] p-5 z-50">
            <div class="max-w-7xl mx-auto flex items-center justify-between">
                <div class="flex flex-col">
                    <span id="cartTotalItem" class="text-[10px] font-black uppercase text-gray-400 tracking-widest">0 Item Terpilih</span>
                    <span id="cartTotalPrice" class="text-xl font-black text-blue-900 tracking-tight">Rp 0</span>
                </div>
                <button type="submit" id="mainSubmitBtn" class="px-10 py-3 bg-blue-900 text-white rounded-lg font-bold uppercase text-xs tracking-widest shadow-lg shadow-blue-200 hover:bg-blue-800 transition active:scale-95">
                    Simpan Struk
                </button>
            </div>
        </div>
    </form>
</div>

<!-- JS Modal Pilih Produk  -->
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
    const statusSelect = document.getElementById("paymentStatus");
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
            mainSubmitBtn.type = "button";
            mainSubmitBtn.innerText = "Simpan & Lanjut";
            mainSubmitBtn.onclick = closeModal;
        }
    }

    function closeModal() {
        modal.classList.add("hidden");
        modal.classList.remove("flex");

        if (mainSubmitBtn) {
            mainSubmitBtn.type = "submit";
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
       PAYMENT LOGIC
    ================================= */

    function autoSetStatus() {
        const amount = parseInt(amountPaidInput.value) || 0;

        if (amount <= 0) {
            statusSelect.value = "pending";
        } else if (amount < totalPrice) {
            statusSelect.value = "partial";
        } else {
            statusSelect.value = "lunas";
            amountPaidInput.value = totalPrice;
        }
    }

    amountPaidInput.addEventListener("input", function () {
        let amount = parseInt(this.value) || 0;

        if (amount > totalPrice) {
            this.value = totalPrice;
        }

        autoSetStatus();
    });

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
        cartTotalPrice.textContent =
            "Rp " + totalPrice.toLocaleString("id-ID");

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

        const originalStock = parseInt(item.dataset.stock);

        function updateStockDisplay() {
            const qty = parseInt(qtyInput.value) || 0;
            const remainingStock = originalStock - qty;

            stockElement.textContent = remainingStock;

            if (remainingStock <= 0) {
                stockElement.parentElement.classList.remove("text-gray-400");
                stockElement.parentElement.classList.add("text-red-500");
                btnPlus.disabled = true;
            } else {
                stockElement.parentElement.classList.remove("text-red-500");
                stockElement.parentElement.classList.add("text-gray-400");
                btnPlus.disabled = false;
            }
        }

        btnPlus.addEventListener("click", () => {
            let qty = parseInt(qtyInput.value) || 0;

            if (qty < originalStock) {
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

            if (qty > originalStock) qtyInput.value = originalStock;
            if (qty < 0) qtyInput.value = 0;

            updateStockDisplay();
            updateCart();
        });

        updateStockDisplay();
    });

    /* ================================
       SEARCH PRODUCT
    ================================= */

    searchInput.addEventListener("input", function () {
        const keyword = this.value.toLowerCase();

        document.querySelectorAll(".product-item").forEach(item => {
            const productName = item
                .querySelector(".product-name")
                .textContent.toLowerCase();

            item.style.display = productName.includes(keyword)
                ? "flex"
                : "none";
        });
    });

});
</script>


@endsection
