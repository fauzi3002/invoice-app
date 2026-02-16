
@extends('layouts.app')

@section('content')
<form action="{{ route('buat_struk.update', $struk->id) }}" method="POST">
    @csrf
    @method('PUT')

    {{-- Jika Pending --}}
    @if($struk->status_pembayaran === 'pending')

            <div id="pageWrapper" class="pb-40 md:pb-24 min-h-screen">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
            <div>
                <h2 class="text-2xl font-semibold text-gray-800">Edit Struk</h2>
                <p class="text-sm text-gray-500 mt-1">Edit struk pembayaran pelanggan Anda</p>
            </div>
            <div class="text-center bg-blue-50 p-2 rounded-lg text-blue-500">
                <span class="">Status : Pending hanya dapat mengubah atau menambahkan item dan jumlah bayar</span>
            </div>
        </div>

        <div class="mb-4">
            <button type="button" id="openModalBtn" class="px-6 py-6 bg-gray-50 w-full border-blue-900 border-2 border-dashed text-blue-900 font-medium rounded-xl hover:bg-blue-100 transition">
                Pilih Produk
            </button>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 mb-4 p-6 mt-6">
            <div class="mb-2 space-y-2">
                <input type="hidden" name="status" value="pending">
            </div>
            <div class="mb-2 space-y-2">
                <label class="text-sm font-semibold text-gray-700">Jumlah Bayar</label>
                <input name="jumlah_bayar"
       type="number"
       id="amountPaid" 
       class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl">

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
    @php
        $existing = $struk->items->firstWhere('produk_id', $p->id);
        $qty = $existing ? $existing->qty : 0;
    @endphp

    <div class="product-item flex justify-between items-center border-b border-gray-200 p-2"
         data-price="{{ $p->harga_satuan }}"
         data-id="{{ $p->id }}"
         data-stock="{{ $p->stok }}">

        <div>
            <h1 class="product-name">{{ $p->nama_produk }}</h1>
            <span class="text-sm text-blue-500 font-semibold">
                Rp {{ number_format($p->harga_satuan, 0, ',', '.') }}
            </span>
            <span class="text-xs product-stock {{ $p->stok <= 0 ? 'text-red-500' : 'text-gray-400' }}">
                Stok: <span class="stock-number">{{ $p->stok }}</span>
            </span>
        </div>

        <div class="flex items-center gap-2">
            <button type="button" class="btn-minus px-3 py-1 bg-red-500 text-white rounded-lg">-</button>

            <input type="number"
                   name="items[{{ $p->id }}][qty]"
                   value="{{ $qty }}"
                   min="0"
                   class="product-qty w-12 px-1 py-1 bg-gray-50 border rounded-lg text-center">

            <input type="hidden"
                   name="items[{{ $p->id }}][price]"
                   value="{{ $p->harga_satuan }}">

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
                <div>
                    <button type="submit" id="mainSubmitBtn" class="px-8 py-3 bg-blue-900 text-center text-white rounded-lg font-bold shadow-lg shadow-blue-200 hover:bg-blue-700 hover:shadow-blue-300 transition active:scale-95">
                    Simpan
                </button>
                </div>

        </div>
</div>


    @endif



    {{-- Jika Partial --}}
    @if($struk->status_pembayaran === 'partial')
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
            <div>
                <h2 class="text-2xl font-semibold text-gray-800">Edit Struk</h2>
                <p class="text-sm text-gray-500 mt-1">Edit struk pembayaran pelanggan Anda</p>
            </div>
            <div class="text-center bg-blue-50 p-2 rounded-lg text-blue-500">
                <span class="">Status : Partial hanya dapat menambahkan jumlah bayar</span>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 mb-4 p-6 mt-6">

        <div class="text-right">

        <p class="text-gray-400 text-[10px] uppercase font-bold mb-1">Total Harga</p>
            <p class="font-bold text-green-700 text-sm">
                Rp {{ number_format($struk->total_harga, 0, ',', '.') }}
            </p>

            <p class="text-gray-400 text-[10px] uppercase font-bold mb-1">Sudah dibayar</p>
            <p class="font-bold text-blue-700 text-sm">
                Rp {{ number_format($struk->jumlah_bayar, 0, ',', '.') }}
            </p>

            <p class="text-gray-400 text-[10px] uppercase font-bold mb-1">Sisa Tagihan</p>
            <p class="font-bold text-red-500 text-sm">
                Rp {{ number_format($struk->sisa_tagihan, 0, ',', '.') }}
            </p>

            </div>

            <div class="mb-2 space-y-2">
                <input type="hidden" name="status" value="pending">
            </div>
            <div class="mb-2 space-y-2">
                <label class="text-sm font-semibold text-gray-700">Jumlah Bayar</label>
                <input name="tambah_bayar"
       type="number"
       id="amountPaid" 
       min="0"
       max="{{ $struk->sisa_tagihan }}"
       class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl">

            </div>
        </div>


    @endif


    {{-- Jika Lunas --}}
    @if($struk->status_pembayaran === 'lunas')
        <div class="bg-gray-100 p-3">
            Struk sudah lunas dan tidak dapat diubah.
        </div>
    @endif

</form>

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