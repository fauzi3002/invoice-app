@extends('layouts.app')

@section('content')
<div id="pageWrapper" class="container mx-auto px-4 pt-24 md:pt-10 pb-24">
    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">
        <div>
            <h2 class="text-2xl font-bold text-gray-800 tracking-tight">Edit Produk</h2>
            <p class="text-sm text-gray-500 mt-1">
                Ubah detail produk <span class="font-bold text-blue-900">"{{ $produks->nama_produk }}"</span>
            </p>
        </div>
    </div>

    {{-- Pesan Error Global --}}
    @if ($errors->any())
        <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 rounded-lg flex items-start gap-3">
            <svg class="w-5 h-5 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
            <div>
                <p class="font-bold text-sm">Ada kesalahan pada input Anda:</p>
                <ul class="list-disc list-inside text-xs mt-1 opacity-90">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <form id="productForm" action="{{ route('produk.update', $produks->id) }}" 
          enctype="multipart/form-data" 
          method="POST" 
          class="space-y-6">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            {{-- Sidebar Kiri: Gambar Produk --}}
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg border border-gray-200 overflow-hidden sticky top-24">
                    <div class="p-4 border-b border-gray-100 bg-gray-50/50">
                        <h3 class="text-sm font-bold text-gray-700 uppercase tracking-wider">Gambar Produk</h3>
                    </div>
                    <div class="p-6 flex flex-col items-center" 
                         x-data="{ photoPreview: '{{ $produks->gambar ? asset('storage/' . $produks->gambar) : '' }}' }">
                        
                        <div class="relative group w-44 h-44 mb-4">
                            <div class="w-full h-full rounded-lg border-2 border-dashed {{ $errors->has('gambar') ? 'border-red-300' : 'border-gray-200' }} flex items-center justify-center overflow-hidden bg-gray-50 transition-all group-hover:border-blue-400">
                                <template x-if="photoPreview">
                                    <img :src="photoPreview" class="w-full h-full object-cover">
                                </template>
                                <template x-if="!photoPreview">
                                    <div class="text-center p-2 text-gray-400">
                                        <svg class="w-12 h-12 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                        <span class="text-[10px] font-bold uppercase tracking-widest">Tidak Ada Foto</span>
                                    </div>
                                </template>
                                
                                <div @click="$refs.fileInput.click()" class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center cursor-pointer">
                                    <span class="text-white text-xs font-bold uppercase tracking-widest">Ubah Gambar</span>
                                </div>
                            </div>
                        </div>

                        <input type="file" name="gambar" x-ref="fileInput" accept="image/*" class="hidden"
                               @change="const file = $event.target.files[0]; if (file) { const reader = new FileReader(); reader.onload = (e) => { photoPreview = e.target.result; }; reader.readAsDataURL(file); }">
                        
                        <div class="text-center">
                            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest leading-relaxed">
                                Biarkan kosong jika tidak<br>ingin mengubah gambar
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Kolom Kanan: Form Detail --}}
            <div class="lg:col-span-2 space-y-6">
                
                {{-- Informasi Utama --}}
                <div class="bg-white rounded-lg border border-gray-200 shadow-sm">
                    <div class="p-5 border-b border-gray-100 flex items-center gap-3">
                        <div class="p-2 bg-blue-50 rounded-lg">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        </div>
                        <h3 class="font-bold text-gray-800">Edit Rincian Produk</h3>
                    </div>
                    
                    <div class="p-6 space-y-5">
                        <div class="space-y-1.5">
                            <label class="text-xs font-bold text-gray-600 uppercase">Nama Produk <span class="text-red-500">*</span></label>
                            <input type="text" name="nama_produk" value="{{ old('nama_produk', $produks->nama_produk) }}" required
                                   class="w-full px-4 py-2.5 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition text-sm">
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div class="space-y-1.5">
                                <label class="text-xs font-bold text-gray-600 uppercase">Jumlah Stok <span class="text-red-500">*</span></label>
                                <input type="number" name="stok" value="{{ old('stok', $produks->stok) }}" required
                                       class="w-full px-4 py-2.5 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition text-sm">
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-xs font-bold text-gray-600 uppercase">Harga Satuan <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-gray-400 font-bold text-sm">Rp</span>
                                    
                                    {{-- Input Visual: Biarkan kosong, akan diisi oleh JS --}}
                                    <input id="rupiah_visual" type="text" required
                                        class="w-full pl-11 pr-4 py-2.5 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition text-sm font-bold"
                                        placeholder="0">
                                    
                                    {{-- Hidden Input: Pastikan hanya angka murni dari database --}}
                                    <input type="hidden" name="harga_satuan" id="harga_satuan_asli" value="{{ (int)$produks->harga_satuan }}">
                                </div>
                            </div>
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-xs font-bold text-gray-600 uppercase">Deskripsi (Opsional)</label>
                            <textarea name="deskripsi" rows="4" 
                                      class="w-full px-4 py-2.5 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition text-sm resize-none tracking-wide leading-relaxed">{{ old('deskripsi', $produks->deskripsi) }}</textarea>
                        </div>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="flex items-center justify-end gap-3 pt-2">
                    <a href="{{ route('produk.index') }}" 
                       class="px-6 py-2.5 text-sm font-bold text-gray-500 hover:text-gray-700 transition">
                        Batal
                    </a>
                    <button id="btnSubmitProduct" type="submit" 
                            class="px-10 py-3 bg-blue-900 text-white rounded-lg text-sm font-bold shadow-lg hover:bg-blue-800 focus:ring-4 focus:ring-blue-900/20 transition-all active:scale-95">
                        Simpan Perubahan
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const visual = document.getElementById('rupiah_visual');
        const asli = document.getElementById('harga_satuan_asli');

        // --- Proteksi Submit & Loading ---
        const productForm = document.getElementById('productForm');
        const btnSubmit = document.getElementById('btnSubmitProduct');

        if (productForm && btnSubmit) {
            productForm.onsubmit = function() {
                // Cek validasi HTML5 bawaan (required fields)
                if (!productForm.checkValidity()) {
                    return true; // Biarkan browser menunjukkan field mana yang kosong
                }

                // Tambahkan class loading (spinner)
                btnSubmit.classList.add('btn-loading');
                
                // Disable tombol agar tidak diklik dua kali
                btnSubmit.disabled = true;
                btnSubmit.innerHTML = 'Menyimpan...'; // Opsional: Ubah teks tombol
                
                return true;
            };
        }

        // Fungsi membersihkan angka dari apapun selain digit
        function cleanToNumber(str) {
            return str.toString().replace(/[^0-9]/g, '');
        }

        // Fungsi memformat ke Rupiah
        function formatRupiah(angka) {
            let number_string = cleanToNumber(angka);
            if (!number_string) return '';
            
            let sisa = number_string.length % 3,
                rupiah = number_string.substr(0, sisa),
                ribuan = number_string.substr(sisa).match(/\d{3}/g);

            if (ribuan) {
                let separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }
            return rupiah;
        }

        // INIT: Ambil nilai asli, bersihkan, format, lalu tampilkan
        if (asli.value) {
            // Kita paksa ambil angka murninya saja
            let initialValue = cleanToNumber(asli.value);
            visual.value = formatRupiah(initialValue);
        }

        // EVENT: Saat user mengetik
        visual.addEventListener('input', function(e) {
            let rawValue = cleanToNumber(this.value);
            this.value = formatRupiah(rawValue);
            asli.value = rawValue;
        });
    });
</script>
@endsection