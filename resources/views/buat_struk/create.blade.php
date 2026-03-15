@extends('layouts.app')

@section('content')
<div id="pageWrapper" class="container mx-auto px-4 pt-20 md:pt-6 pb-24">
    <form method="POST" action="{{ route('buat_struk.store') }}" id="createStrukForm">
        @csrf
        @method('POST')
        
        {{-- Header --}}
        <div class="mb-8">
            <h2 class="text-2xl font-extrabold text-gray-900 tracking-tight">Buat Struk</h2>
            <p class="text-sm mt-1 text-gray-500">Input detail transaksi dan pilih produk pelanggan.</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {{-- KOLOM KIRI: FORM UTAMA --}}
            <div class="lg:col-span-2 space-y-6">
                
                {{-- Card: Informasi Pelanggan --}}
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-5 py-4 border-b border-gray-100 bg-gray-50/50">
                        <h3 class="text-sm font-bold text-gray-600 uppercase tracking-wider flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            Informasi Pelanggan
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div class="space-y-2">
                                <label class="text-xs font-semibold text-gray-500 uppercase">Nama Pelanggan</label>
                                <input name="customer_name" type="text" class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg focus:ring-4 focus:ring-blue-100 focus:border-blue-500 outline-none transition" placeholder="Nama lengkap..." required>
                            </div>
                            <div class="space-y-2">
                                <label class="text-xs font-semibold text-gray-500 uppercase">No. WhatsApp</label>
                                <input name="customer_phone" type="tel" class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg focus:ring-4 focus:ring-blue-100 focus:border-blue-500 outline-none transition" placeholder="0812..." required>
                            </div>
                            <div class="md:col-span-2 space-y-2">
                                <label class="text-xs font-semibold text-gray-500 uppercase">Alamat Pengiriman</label>
                                <textarea name="customer_address" rows="3" class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg focus:ring-4 focus:ring-blue-100 focus:border-blue-500 outline-none transition resize-none" placeholder="Alamat lengkap..." required></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Card: Pembayaran --}}
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <div class="hidden">
                        <select name="status" id="paymentStatus">
                            <option value="pending">Pending</option>
                            <option value="partial">Partial</option>
                            <option value="lunas">Lunas</option>
                        </select>
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Jumlah yang Dibayarkan (Rp)</label>
                        <input type="text" id="amountPaidVisual" class="w-full px-4 py-4 bg-blue-50/50 border-2 border-blue-100 rounded-xl focus:border-blue-500 outline-none transition text-2xl font-bold text-blue-900" placeholder="0">
                        <input type="hidden" name="amount_paid" id="amountPaidActual" value="0">
                    </div>
                </div>

                {{-- MOBILE VIEW SUMMARY --}}
                <div class="lg:hidden space-y-4">
                    <button type="button" onclick="document.getElementById('openModalBtn').click()" class="w-full flex items-center justify-center gap-3 px-6 py-8 bg-white border-2 border-dashed border-blue-200 rounded-xl">
                        <span class="font-bold text-blue-900">PILIH PRODUK (+)</span>
                    </button>
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-500">Total Tagihan</span>
                            <span class="cartTotalPrice text-xl font-black text-blue-900">Rp 0</span>
                        </div>
                        <button type="submit" class="w-full py-4 bg-blue-900 text-white rounded-xl font-bold uppercase tracking-widest">Simpan Struk</button>
                    </div>
                </div>
            </div>

            {{-- KOLOM KANAN: SUMMARY (DESKTOP) --}}
            <div class="hidden lg:block lg:col-span-1">
                <div class="sticky top-6 space-y-4">
                    <button type="button" id="openModalBtn" class="w-full flex items-center justify-center gap-3 px-6 py-8 bg-white border-2 border-dashed border-blue-200 rounded-xl hover:border-blue-400 hover:bg-blue-50 transition-all group">
                        <div class="w-10 h-10 bg-blue-900 text-white rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform font-bold text-xl">+</div>
                        <div class="text-left">
                            <span class="block font-bold text-blue-900 text-sm uppercase">Pilih Produk</span>
                            <span class="text-[10px] text-gray-400 uppercase tracking-widest text-center">Klik untuk membuka daftar</span>
                        </div>
                    </button>

                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                        <div class="p-5 bg-gray-50 border-b border-gray-100">
                            <h3 class="text-xs font-black text-gray-400 uppercase tracking-widest">Ringkasan Pembayaran</h3>
                        </div>
                        <div class="p-6 space-y-4">
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-500">Total Item</span>
                                <span id="cartTotalItem" class="font-bold text-gray-800">0 Item</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-500">Total Tagihan</span>
                                <span class="cartTotalPrice text-xl font-black text-blue-900">Rp 0</span>
                            </div>
                            <div class="flex justify-between items-center pt-4 border-t border-gray-100">
                                <span class="text-sm text-gray-500">Status</span>
                                <span id="statusBadge" class="text-[10px] font-black uppercase px-2 py-1 rounded bg-red-50 text-red-500 border border-red-100">Pending</span>
                            </div>
                            <button type="submit" class="w-full mt-4 py-4 bg-blue-900 text-white rounded-xl font-bold uppercase text-sm tracking-widest shadow-lg shadow-blue-200 hover:bg-blue-800 transition">
                                Simpan Struk
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- MODAL PRODUK --}}
        <div id="productModal" class="fixed inset-0 z-[100] hidden items-center justify-center p-0 md:p-4">
            <div class="absolute inset-0 bg-gray-900/70 backdrop-blur-sm"></div>
            <div class="relative bg-white w-full h-full md:h-[90%] md:max-w-4xl md:rounded-2xl shadow-2xl flex flex-col overflow-hidden mx-auto">
                <div class="flex items-center justify-between p-5 border-b border-gray-100">
                    <h2 class="text-xl font-bold text-gray-800">Daftar Produk</h2>
                    <button type="button" id="closeModalBtn" class="text-3xl text-gray-400 hover:text-red-500">&times;</button>
                </div>
                <div class="p-4 bg-gray-50">
                    <input type="search" id="searchProduct" class="w-full px-4 py-3 border border-gray-200 rounded-xl outline-none" placeholder="Cari produk...">
                </div>
                <div id="modalBody" class="flex-1 overflow-y-auto p-4 space-y-3">
                    @foreach ($produk as $p)
                        <div class="product-item flex flex-col sm:flex-row sm:items-center justify-between p-4 rounded-xl border {{ $p->stok <= 0 ? 'bg-gray-50 opacity-60' : 'bg-white border-gray-100' }}" 
                            data-price="{{ $p->harga_satuan }}" 
                            data-id="{{ $p->id }}" 
                            data-stock="{{ $p->stok }}">
                            
                            <div class="flex-1">
                                <h4 class="product-name font-bold text-gray-800">{{ $p->nama_produk }}</h4>
                                <div class="flex items-center gap-2">
                                    <span class="text-blue-600 font-bold text-sm">Rp {{ number_format($p->harga_satuan, 0, ',', '.') }}</span>
                                    @if($p->stok <= 0)
                                        <span class="text-[10px] bg-red-100 text-red-600 px-2 py-0.5 rounded font-bold uppercase">Habis</span>
                                    @else
                                        <span class="text-[10px] text-gray-400 font-medium">Stok: {{ $p->stok }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="flex items-center justify-center bg-gray-50 rounded-lg p-1 border mt-3 sm:mt-0 {{ $p->stok <= 0 ? 'pointer-events-none' : '' }}">
                                <button type="button" class="btn-minus w-8 h-8 bg-white border rounded shadow-sm hover:bg-red-500 hover:text-white transition">-</button>
                                
                                {{-- INPUT QUANTITY --}}
                                <input type="number" name="items[{{ $p->id }}][qty]" value="0" min="0" 
                                    class="product-qty w-12 text-center font-bold bg-transparent border-none focus:ring-0" 
                                    {{ $p->stok <= 0 ? 'disabled' : '' }}>
                                
                                {{-- INPUT HARGA (Hidden supaya terkirim ke controller) --}}
                                <input type="hidden" name="items[{{ $p->id }}][price]" value="{{ $p->harga_satuan }}">
                                
                                <button type="button" class="btn-plus w-8 h-8 bg-white border rounded shadow-sm hover:bg-blue-600 hover:text-white transition {{ $p->stok <= 0 ? 'opacity-50' : '' }}">+</button>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="p-4 border-t">
                    <button type="button" id="closeModalFinal" class="w-full py-3 bg-blue-900 text-white rounded-xl font-bold">SELESAI</button>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const modal = document.getElementById("productModal");
    const openBtn = document.getElementById("openModalBtn");
    const closeBtn = document.getElementById("closeModalBtn");
    const closeBtnFinal = document.getElementById("closeModalFinal");
    const searchInput = document.getElementById("searchProduct");
    const createStrukForm = document.getElementById('createStrukForm');
    
    // Ambil semua tombol submit (baik mobile maupun desktop)
    const submitButtons = document.querySelectorAll('button[type="submit"]');
    
    const cartTotalItem = document.getElementById("cartTotalItem");
    const cartTotalPrices = document.querySelectorAll(".cartTotalPrice");
    const statusBadge = document.getElementById("statusBadge");
    const statusSelect = document.getElementById("paymentStatus");
    const amountPaidVisual = document.getElementById("amountPaidVisual");
    const amountPaidActual = document.getElementById("amountPaidActual");

    let totalGlobalPrice = 0;

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

    // Modal Logic
    openBtn.onclick = () => { modal.classList.remove("hidden"); modal.classList.add("flex"); };
    closeBtn.onclick = closeBtnFinal.onclick = () => { modal.classList.add("hidden"); modal.classList.remove("flex"); };

    // Update Cart & Totals
    function updateCart() {
        let totalItem = 0;
        totalGlobalPrice = 0;

        document.querySelectorAll(".product-item").forEach(item => {
            const qtyInput = item.querySelector(".product-qty");
            const qty = parseInt(qtyInput.value) || 0;
            const price = parseInt(item.dataset.price);
            totalItem += qty;
            totalGlobalPrice += (qty * price);
        });

        if(cartTotalItem) cartTotalItem.textContent = totalItem + " Item";
        cartTotalPrices.forEach(el => {
            el.textContent = "Rp " + totalGlobalPrice.toLocaleString("id-ID");
        });

        autoSetStatus();
    }

    function autoSetStatus() {
        const amount = parseInt(amountPaidActual.value) || 0;
        
        if (amount <= 0) {
            statusSelect.value = "pending";
            statusBadge.innerText = "Pending";
            statusBadge.className = "text-[10px] font-black uppercase px-2 py-1 rounded bg-red-50 text-red-500 border border-red-100";
        } else if (amount < totalGlobalPrice) {
            statusSelect.value = "partial";
            statusBadge.innerText = "Partial";
            statusBadge.className = "text-[10px] font-black uppercase px-2 py-1 rounded bg-orange-50 text-orange-500 border border-orange-100";
        } else {
            statusSelect.value = "lunas";
            statusBadge.innerText = "Lunas";
            statusBadge.className = "text-[10px] font-black uppercase px-2 py-1 rounded bg-green-50 text-green-500 border border-green-100";
        }
    }

    // Button Listeners
    document.querySelectorAll(".product-item").forEach(item => {
        const btnPlus = item.querySelector(".btn-plus");
        const btnMinus = item.querySelector(".btn-minus");
        const qtyInput = item.querySelector(".product-qty");
        const stock = parseInt(item.dataset.stock);

        btnPlus.onclick = (e) => {
            e.preventDefault();
            let val = parseInt(qtyInput.value) || 0;
            if (val < stock) {
                qtyInput.value = val + 1;
                updateCart();
            }
        };

        btnMinus.onclick = (e) => {
            e.preventDefault();
            let val = parseInt(qtyInput.value) || 0;
            if (val > 0) {
                qtyInput.value = val - 1;
                updateCart();
            }
        };
    });

    // Payment Logic
    amountPaidVisual.addEventListener("keyup", function() {
        this.value = formatRupiah(this.value);
        amountPaidActual.value = cleanNumber(this.value);
        autoSetStatus();
    });

    // --- PROTEKSI SUBMIT & FIX UNDEFINED QTY ---
    createStrukForm.onsubmit = function(e) {
        let totalQty = 0;
        const allItems = document.querySelectorAll(".product-item");
        
        // Pilih tombol submit (baik yang di desktop maupun mobile)
        const btnSubmit = e.submitter || createStrukForm.querySelector('button[type="submit"]');

        // 1. Validasi & Data Pruning (Hapus 'name' qty yang 0 agar tidak crash di Controller)
        allItems.forEach(item => {
            const qtyInput = item.querySelector(".product-qty");
            const priceInput = item.querySelector('input[type="hidden"][name*="[price]"]');
            const qtyValue = parseInt(qtyInput.value) || 0;
            
            totalQty += qtyValue;

            if (qtyValue <= 0) {
                qtyInput.removeAttribute('name');
                if(priceInput) priceInput.removeAttribute('name');
            }
        });

        if (totalQty <= 0) {
            e.preventDefault();
            // Kembalikan nama input jika gagal validasi agar user bisa pilih lagi
            allItems.forEach(item => {
                const id = item.dataset.id;
                item.querySelector(".product-qty").setAttribute('name', `items[${id}][qty]`);
                const pInput = item.querySelector('input[type="hidden"]');
                if(pInput) pInput.setAttribute('name', `items[${id}][price]`);
            });
            
            if(typeof toastr !== 'undefined') {
                toastr.error("Pilih minimal 1 produk!");
            } else {
                alert("Pilih minimal 1 produk!");
            }
            return false;
        }

        // 2. EFEK LOADING (Sesuai keinginanmu)
        if (btnSubmit) {
            btnSubmit.classList.add('btn-loading');
            btnSubmit.disabled = true;
        }

        // Lock input qty agar tidak diubah saat proses kirim
        allItems.forEach(item => {
            const input = item.querySelector(".product-qty");
            if(input.hasAttribute('name')) {
                input.readOnly = true;
            }
        });

        return true;
    };

    // Search Logic
    searchInput.oninput = function() {
        const key = this.value.toLowerCase();
        document.querySelectorAll(".product-item").forEach(item => {
            const name = item.querySelector(".product-name").textContent.toLowerCase();
            item.style.display = name.includes(key) ? "flex" : "none";
        });
    };
});
</script>
@endsection