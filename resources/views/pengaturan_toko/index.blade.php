@extends('layouts.app')

@section('content')
{{-- pageWrapper disamakan dengan Buat Struk --}}
<div id="pageWrapper" class="pb-40 md:pb-24 min-h-screen">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
        <div>
            <h2 class="text-2xl font-semibold text-gray-800">Pengaturan Toko</h2>
            <p class="text-sm text-gray-500 mt-1">Kelola identitas dan informasi bisnis Anda</p>
        </div>
    </div>

    <form action="{{ route('pengaturan_toko.update', $toko->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="bg-white rounded-2xl border border-gray-200 mb-4 overflow-hidden">
            <div class="p-6 border-b border-gray-100 bg-gray-50">
                <h2 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
                    <svg class="w-5 h-5 text-blue-900" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    Identitas Visual
                </h2>
            </div>
            
            <div class="p-6 flex flex-col items-center justify-center" 
                 x-data="{ logoPreview: '{{ $toko?->logo_toko ? asset('storage/' . $toko->logo_toko) : '/storage/logo1.png' }}' }">
                
                <div class="relative group">
                    <div class="w-20 h-20 rounded-2xl border-2 border-dashed border-gray-300 flex items-center justify-center overflow-hidden bg-gray-50 transition-all group-hover:border-blue-400 relative">
                        <template x-if="logoPreview">
                            <img :src="logoPreview" class="w-full h-full object-cover">
                        </template>
                        
                        <div @click="$refs.fileInput.click()" class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity cursor-pointer">
                            <span class="text-white text-[10px] font-medium bg-blue-900 px-3 py-1.5 rounded-full">Ganti Logo</span>
                        </div>
                    </div>
                </div>

                <input type="file" name="logo_toko" x-ref="fileInput" accept="image/*" class="hidden"
                       @change="const file = $event.target.files[0]; if (file) { const reader = new FileReader(); reader.onload = (e) => { logoPreview = e.target.result; }; reader.readAsDataURL(file); }">
                
                <div class="text-center mt-4">
                    <p class="text-sm font-semibold text-gray-700">Logo Utama Toko</p>
                    <p class="text-[11px] text-gray-400 uppercase mt-1">Rekomendasi: 500x500px • Max 2MB</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 mb-4">
            <div class="p-6 border-b border-gray-100 bg-gray-50">
                <h2 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
                    <svg class="w-5 h-5 text-blue-900" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Informasi Umum
                </h2>
            </div>
            
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label class="text-sm font-semibold text-gray-700">Nama Toko</label>
                    <input type="text" name="nama_toko" value="{{ $toko->nama_toko ?? '' }}" 
                           class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-4 focus:ring-blue-100 focus:border-blue-400 outline-none transition"
                           placeholder="Nama Brand Anda">
                </div>

                <div class="space-y-2">
                    <label class="text-sm font-semibold text-gray-700">No. Telepon / WhatsApp</label>
                    <input type="text" name="no_telepon" value="{{ $toko->no_telepon ?? '' }}" 
                           class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-4 focus:ring-blue-100 focus:border-blue-400 outline-none transition"
                           placeholder="0812xxxx">
                </div>

                <div class="space-y-2 md:col-span-2">
                    <label class="text-sm font-semibold text-gray-700">Email Bisnis</label>
                    <input type="email" name="email" value="{{ $toko->email ?? '' }}" 
                           class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-4 focus:ring-blue-100 focus:border-blue-400 outline-none transition"
                           placeholder="halo@tokokamu.com">
                </div>

                <div class="space-y-2 md:col-span-2">
                    <label class="text-sm font-semibold text-gray-700">Alamat Lengkap</label>
                    <textarea name="alamat_toko" rows="3" 
                              class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-4 focus:ring-blue-100 focus:border-blue-400 outline-none transition resize-none"
                              placeholder="Nama jalan, gedung, No. Rumah...">{{ $toko->alamat_toko ?? '' }}</textarea>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 mb-6">
            <div class="p-6 border-b border-gray-100 bg-gray-50">
                <h2 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
                    <svg class="w-5 h-5 text-blue-900" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                    Rekening Bank
                </h2>
            </div>
            
            <div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="space-y-2">
                    <label class="text-sm font-semibold text-gray-700">Nama Bank</label>
                    <input type="text" name="nama_bank" value="{{ $toko->nama_bank ?? '' }}" 
                           class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-4 focus:ring-blue-100 outline-none transition"
                           placeholder="BCA / Mandiri">
                </div>

                <div class="space-y-2">
                    <label class="text-sm font-semibold text-gray-700">No. Rekening</label>
                    <input type="text" name="no_rekening" value="{{ $toko->no_rekening ?? '' }}" 
                           class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-4 focus:ring-blue-100 outline-none transition"
                           placeholder="12345678">
                </div>

                <div class="space-y-2">
                    <label class="text-sm font-semibold text-gray-700">Nama Pemilik</label>
                    <input type="text" name="pemilik_rekening" value="{{ $toko->pemilik_rekening ?? '' }}" 
                           class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-4 focus:ring-blue-100 outline-none transition"
                           placeholder="Nama Pemilik">
                </div>
            </div>
        </div>

        {{-- Bottom Action Bar disamakan dengan Buat Struk --}}
        <div class="p-4">
            <div class="max-w-6xl mx-auto flex justify-end gap-3">
                <a href="{{ route('dashboard.index') }}" class="px-8 py-3 bg-gray-400 text-gray-600 rounded-lg hover:bg-gray-600 hover:text-gray-400 font-semibold hover:shadow-blue-300 transition active:scale-95">
                    <span>Batal</span>
                </a>
                <button type="submit" class="px-8 py-3 bg-blue-900 text-center text-white rounded-lg font-bold shadow-lg shadow-blue-200 hover:bg-blue-700 hover:shadow-blue-300 transition active:scale-95">
                    Simpan Perubahan
                </button>
            </div>
        </div>
    </form>
</div>

@endsection