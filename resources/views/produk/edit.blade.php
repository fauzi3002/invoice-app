@extends('layouts.app')

@section('content')
<div id="pageWrapper" class="pb-24 min-h-screen max-w-7xl mx-auto px-4">
    <div class="mx-auto w-full max-w-4xl">
        
        {{-- HEADER SECTION --}}
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">
            <div>
                <h2 class="text-2xl font-bold text-gray-800 tracking-tight">Edit Produk</h2>
                <p class="text-sm text-gray-500 mt-1">
                    Ubah detail produk <span class="font-bold text-blue-900">"{{ $produks->nama_produk }}"</span>
                </p>
            </div>
        </div>

        <form action="{{ route('produk.update', $produks->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            {{-- EDIT GAMBAR --}}
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6 overflow-hidden">
                <div class="p-4 border-b border-gray-200 bg-gray-50/50">
                    <h2 class="text-sm font-bold text-gray-800 flex items-center gap-2 uppercase tracking-wider">
                        <svg class="w-4 h-4 text-blue-900" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        Gambar Produk
                    </h2>
                </div>
                
                <div class="p-8 flex flex-col items-center justify-center" 
                     x-data="{ photoPreview: '{{ $produks->gambar ? asset('storage/' . $produks->gambar) : null }}' }">
                    
                    <div class="relative group">
                        <div @click="$refs.fileInput.click()" 
                             class="w-44 h-44 rounded-lg border-2 border-dashed border-gray-200 flex items-center justify-center overflow-hidden bg-white transition-all group-hover:border-blue-500 cursor-pointer relative">
                            
                            <template x-if="photoPreview">
                                <img :src="photoPreview" class="w-full h-full object-cover">
                            </template>
                            
                            <template x-if="!photoPreview">
                                <div class="flex flex-col items-center p-4 text-center">
                                    <div class="w-10 h-10 bg-blue-50 rounded-full flex items-center justify-center mb-3">
                                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                        </svg>
                                    </div>
                                    <span class="text-[11px] font-bold text-gray-500 uppercase tracking-tight">Pilih Foto</span>
                                </div>
                            </template>

                            <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                <span class="bg-white text-[10px] font-bold text-gray-800 px-3 py-1.5 rounded-lg shadow-sm uppercase">Ganti Gambar</span>
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
                    
                    <p class="mt-4 text-[10px] text-gray-400 uppercase font-bold tracking-widest text-center">Biarkan kosong jika tidak ingin mengubah gambar</p>
                </div>
            </div>

            {{-- FORM INFORMASI --}}
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-8 overflow-hidden">
                <div class="p-4 border-b border-gray-200 bg-gray-50/50">
                    <h2 class="text-sm font-bold text-gray-800 flex items-center gap-2 uppercase tracking-wider">
                        <svg class="w-4 h-4 text-blue-900" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        Informasi Produk
                    </h2>
                </div>

                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2 space-y-1.5">
                        <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Nama Produk</label>
                        <input type="text" name="nama_produk" value="{{ old('nama_produk', $produks->nama_produk) }}" required
                               class="w-full px-4 py-2.5 bg-white border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition"
                               placeholder="Nama produk">
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Jumlah Stok</label>
                        <input type="number" name="stok" value="{{ old('stok', $produks->stok) }}" required
                               class="w-full px-4 py-2.5 bg-white border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition"
                               placeholder="0">
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Harga Satuan</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-gray-400 text-sm font-bold">Rp</span>
                            <input type="number" name="harga_satuan" value="{{ old('harga_satuan', $produks->harga_satuan) }}" required
                                   class="w-full pl-10 pr-4 py-2.5 bg-white border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition"
                                   placeholder="0">
                        </div>
                    </div>

                    <div class="md:col-span-2 space-y-1.5">
                        <label class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Deskripsi Produk (Opsional)</label>
                        <textarea name="deskripsi" rows="3" 
                                  class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition resize-none"
                                  placeholder="Detail produk...">{{ old('deskripsi', $produks->deskripsi) }}</textarea>
                    </div>
                </div>
            </div>

            {{-- ACTION BUTTONS --}}
            <div class="flex items-center justify-end gap-3">
                <a href="{{ route('produk.index') }}" 
                   class="px-6 py-2.5 bg-white border border-gray-200 text-gray-600 rounded-lg text-sm font-bold hover:bg-gray-50 transition">
                    Batal
                </a>
                <button type="submit" 
                        class="px-8 py-2.5 bg-blue-900 text-white rounded-lg text-sm font-bold shadow-md shadow-blue-900/10 hover:bg-blue-800 transition active:scale-95">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection