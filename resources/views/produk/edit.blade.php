@extends('layouts.app')

@section('content')
<div id="pageWrapper" class="pb-24 min-h-screen">
    <div class="mx-auto w-full max-w-4xl">
        
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
            <div>
                <h2 class="text-2xl font-semibold text-gray-800">Edit Produk</h2>
                <p class="text-sm text-gray-500 mt-1">Ubah detail produk <span class="font-semibold text-blue-900">"{{ $produks->nama_produk }}"</span> di bawah ini.</p>
            </div>
        </div>

        <form action="{{ route('produk.update', $produks->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 mb-4 overflow-hidden">
                <div class="p-6 border-b border-gray-100 bg-gray-50">
                    <h2 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-900" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        Gambar Produk
                    </h2>
                </div>
                
                <div class="p-6 flex flex-col items-center justify-center" 
                     x-data="{ photoPreview: '{{ $produks->gambar ? asset('storage/' . $produks->gambar) : null }}' }">
                    
                    <div class="relative group">
                        <div @click="$refs.fileInput.click()" 
                             class="w-48 h-48 rounded-2xl border-2 border-dashed border-gray-300 flex items-center justify-center overflow-hidden bg-gray-50 transition-all group-hover:border-blue-400 cursor-pointer relative">
                            
                            <template x-if="photoPreview">
                                <img :src="photoPreview" class="w-full h-full object-cover">
                            </template>
                            
                            <template x-if="!photoPreview">
                                <div class="flex flex-col items-center p-4 text-center">
                                    <svg class="w-8 h-8 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v16m8-8H4" />
                                    </svg>
                                    <span class="text-xs font-semibold text-gray-600">Pilih Foto</span>
                                </div>
                            </template>

                            <div class="absolute inset-0 bg-black/20 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                <span class="bg-white/90 text-[10px] font-bold text-blue-900 px-3 py-1.5 rounded-full shadow-sm">Ganti Foto</span>
                            </div>
                        </div>
                    </div>

                    <input type="file" name="gambar" x-ref="fileInput" accept="image/*" class="hidden"
                        @change="
                            const file = $event.target.files[0];
                            if (file) {
                                const reader = new FileReader();
                                reader.onload = (e) => { photoPreview = e.target.result; };
                                reader.readAsDataURL(file);
                            }
                        ">
                    
                    <p class="mt-4 text-[11px] text-gray-400 uppercase tracking-wider text-center">Biarkan kosong jika tidak ingin mengubah gambar</p>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 mb-6">
                <div class="p-6 border-b border-gray-100 bg-gray-50">
                    <h2 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-900" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        Informasi Produk
                    </h2>
                </div>

                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2 space-y-2">
                        <label class="text-sm font-semibold text-gray-700">Nama Produk</label>
                        <input type="text" name="nama_produk" value="{{ old('nama_produk', $produks->nama_produk) }}" required
                               class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-4 focus:ring-blue-100 focus:border-blue-400 transition outline-none"
                               placeholder="Masukan nama produk">
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-gray-700">Jumlah Stok</label>
                        <input type="number" name="stok" value="{{ old('stok', $produks->stok) }}" required
                               class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-4 focus:ring-blue-100 focus:border-blue-400 transition outline-none"
                               placeholder="0">
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-gray-700">Harga Satuan</label>
                        <input type="number" name="harga_satuan" value="{{ old('harga_satuan', $produks->harga_satuan) }}" required
                               class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-4 focus:ring-blue-100 focus:border-blue-400 transition outline-none"
                               placeholder="Rp 0">
                    </div>

                    <div class="md:col-span-2 space-y-2">
                        <label class="text-sm font-semibold text-gray-700">Deskripsi Produk (Opsional)</label>
                        <textarea name="deskripsi" rows="4" 
                                  class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-4 focus:ring-blue-100 focus:border-blue-400 transition outline-none resize-none"
                                  placeholder="Jelaskan detail produk Anda...">{{ old('deskripsi', $produks->deskripsi) }}</textarea>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end gap-3">
                <a href="{{ route('produk.index') }}" 
                   class="px-8 py-3 bg-gray-400 text-gray-600 rounded-lg hover:bg-gray-600 hover:text-gray-400 font-semibold hover:shadow-blue-300 transition active:scale-95">
                    Batal
                </a>
                <button type="submit" 
                        class="px-8 py-3 bg-blue-900 text-center text-white rounded-lg font-bold shadow-lg shadow-blue-200 hover:bg-blue-700 hover:shadow-blue-300 transition active:scale-95">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection 