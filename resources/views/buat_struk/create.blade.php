@extends('layouts.app')

@section('content')
<div id="pageWrapper" class="pb-40 md:pb-24 min-h-screen">
    <form method="POST" action="{{ route('buat_struk.store') }}" id="createStrukForm">
        @csrf
        @method('POST')
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
            <div>
                <h2 class="text-2xl font-semibold text-gray-800">Buat Struk</h2>
                <p class="text-sm text-gray-500 mt-1">Buat struk pembayaran untuk pelanggan Anda</p>
            </div>
        </div>

        <div class="mb-4">
            <button type="button" id="openModalBtn" class="px-6 py-6 bg-gray-50 w-full border-blue-900 border-2 border-dashed text-blue-900 font-medium rounded-xl hover:bg-blue-100 transition">
                Pilih Produk
            </button>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 mb-4">
            <div class="p-6 border-b border-gray-100 bg-gray-50">
                <h2 class="text-lg font-semibold text-gray-800">Informasi Pelanggan</h2>
            </div>
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label class="text-sm font-semibold text-gray-700">Nama Pelanggan</label>
                    <input name="customer_name" type="text" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-4 focus:ring-blue-100 focus:border-blue-400 outline-none transition" placeholder="Masukan nama pelanggan" required>
                </div>
                <div class="space-y-2">
                    <label class="text-sm font-semibold text-gray-700">No. Telp</label>
                    <input name="customer_phone" type="text" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-4 focus:ring-blue-100 focus:border-blue-400 outline-none transition" placeholder="0812xxxx" required>
                </div>
                <div class="md:col-span-2 space-y-2">
                    <label class="text-sm font-semibold text-gray-700">Alamat</label>
                    <textarea name="customer_address" rows="3" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-4 focus:ring-blue-100 focus:border-blue-400 outline-none transition resize-none" placeholder="Masukan alamat pelanggan" required></textarea>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 mb-4 p-6 mt-6">
            <div class="hidden  mb-2 space-y-2">
                <label class="text-sm font-semibold text-gray-700">Status:</label>
                <select name="status" id="paymentStatus" class="ml-2 px-4 py-2 w-full border border-gray-200 rounded-lg focus:ring-4 focus:ring-blue-100 focus:border-blue-400 outline-none transition" required>
                    <option value="pending">-- Pilih Status --</option>
                    <option value="pending">Pending</option>
                    <option value="partial">Partial</option>
                    <option value="lunas">Lunas</option>
                </select>
            </div>
            <div class="mb-2 space-y-2">
                <label class="text-sm font-semibold text-gray-700">Jumlah Bayar</label>
                <input name="amount_paid" type="number" id="amountPaid" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-4 focus:ring-blue-100 focus:border-blue-400 outline-none transition" placeholder="Masukan jumlah bayar" value="0">
            </div>
        </div>

        <div id="productModal"
     class="fixed inset-0 
       md:left-64 
       md:bottom-24
       bg-black/50 backdrop-blur-sm 
       hidden items-center justify-center z-50"
        >

            <div class="bg-white w-full h-full md:h-[90%] md:w-[90%] md:rounded-2xl shadow-xl flex flex-col">
                <div class="flex items-center justify-between p-5 border-b">
                    <h2 class="text-xl font-semibold">Pilih Produk</h2>
                    <button type="button" id="closeModalBtn" class="text-2xl hover:text-red-500">&times;</button>
                </div>

                <div class="mt-2 p-6 border-b border-gray-200">
                    <input type="search" id="searchProduct" class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-lg focus:bg-white focus:ring-4 focus:ring-blue-100 focus:border-blue-400 outline-none transition" placeholder="Cari produk...">
                </div>

                <div id="modalBody" class="p-6 overflow-y-auto flex-1">
                    <div>
                        @foreach ($produk as $p)
                            <div class="product-item flex justify-between items-center border-b border-gray-200 p-2" data-price="{{ $p->harga_satuan }}" data-id="{{ $p->id }}" data-stock="{{ $p->stok }}">
                                <div>
                                    <h1 class="product-name">{{ $p->nama_produk }}</h1>
                                    <span class="text-sm text-blue-500 font-semibold">Rp {{ number_format($p->harga_satuan, 0, ',', '.') }}</span>
                                    <span class="text-xs product-stock {{ $p->stok <= 0 ? 'text-red-500' : 'text-gray-400' }}">
                                        Stok: <span class="stock-number">{{ $p->stok }}</span>
                                    </span>
                                </div>

                                <div class="flex items-center gap-2">
                                    <button type="button" class="btn-minus px-3 py-1 bg-red-500 text-white rounded-lg">-</button>
                                    <input type="number" name="items[{{ $p->id }}][qty]" value="0" min="0" class="product-qty w-12 px-1 py-1 bg-gray-50 border rounded-lg text-center">
                                    <input type="hidden" name="items[{{ $p->id }}][price]" value="{{ $p->harga_satuan }}">
                                    <button type="button" class="btn-plus px-3 py-1 bg-blue-500 text-white rounded-lg">+</button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <div id="cartBar"
     class="fixed bottom-0 left-0 right-0 md:left-64 bg-white border-t shadow-xl p-4 z-50">

            <div class="flex items-center justify-between">
                <div>
                    <span id="cartTotalItem" class="text-sm font-semibold text-gray-700">0 Item</span>
                </div>
                <div>
                    <span id="cartTotalPrice" class="text-lg font-bold text-blue-900">Rp 0</span>
                </div>

            <div class="justify-end flex mt-4">
                <button type="submit" id="mainSubmitBtn" class="px-8 py-3 bg-blue-900 text-center text-white rounded-lg font-bold shadow-lg shadow-blue-200 hover:bg-blue-700 hover:shadow-blue-300 transition active:scale-95">
                    Simpan
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
