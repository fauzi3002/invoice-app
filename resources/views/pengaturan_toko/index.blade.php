@extends('layouts.app')

@section('content')
<div id="pageWrapper" class="pb-40 md:pb-24 min-h-screen">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
        <div>
            <h2 class="text-2xl font-semibold text-gray-800">Pengaturan Toko</h2>
            <p class="text-sm text-gray-500 mt-1">Kelola identitas dan informasi bisnis Anda</p>
        </div>
    </div>

    {{-- Pesan Error Global --}}
    @if ($errors->any())
        <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 rounded-r-xl shadow-sm">
            <p class="font-bold">Mohon lengkapi semua data:</p>
            <ul class="list-disc list-inside text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('pengaturan_toko.update', $toko->id ?? 1) }}" 
          enctype="multipart/form-data" 
          method="POST">
        @csrf
        @method('PUT')

        {{-- Identitas Visual --}}
        {{-- Identitas Visual --}}
<div class="bg-white rounded-2xl border border-gray-200 mb-4 overflow-hidden">
    <div class="p-6 border-b border-gray-100 bg-gray-50">
        <h2 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
            <svg class="w-5 h-5 text-blue-900" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            Identitas Visual
        </h2>
    </div>
    
    <div class="p-6 flex flex-col items-center justify-center" 
         x-data="{ logoPreview: '{{ $toko?->logo_toko ? asset('storage/' . $toko->logo_toko) : '' }}' }">
        
        <div class="relative group">
            <div class="w-20 h-20 flex-shrink-0 aspect-square rounded-2xl border-2 border-dashed {{ $errors->has('logo_toko') ? 'border-red-400' : 'border-gray-300' }} flex items-center justify-center overflow-hidden bg-gray-50 transition-all group-hover:border-blue-400 relative">
                
                <template x-if="logoPreview">
                    <img :src="logoPreview" class="w-full h-full min-w-full min-h-full object-cover">
                </template>
                
                <template x-if="!logoPreview">
                    <div class="flex flex-col items-center">
                        <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4" stroke-width="2" stroke-linecap="round"/></svg>
                    </div>
                </template>
                
                {{-- Overlay Ganti Logo --}}
                <div @click="$refs.fileInput.click()" class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity cursor-pointer">
                    <span class="text-white text-[10px] font-medium bg-blue-900 px-3 py-1.5 rounded-full shadow-lg">Ganti</span>
                </div>
            </div>
        </div>

        <input type="file" name="logo_toko" x-ref="fileInput" accept="image/*" class="hidden" {{ !$toko?->logo_toko ? 'required' : '' }}
               @change="const file = $event.target.files[0]; if (file) { const reader = new FileReader(); reader.onload = (e) => { logoPreview = e.target.result; }; reader.readAsDataURL(file); }">
        
        <div class="text-center mt-4">
            <p class="text-sm font-semibold text-gray-700">Logo Utama Toko</p>
            <p class="text-[11px] {{ $errors->has('logo_toko') ? 'text-red-500 font-bold' : 'text-gray-400' }} uppercase mt-1 tracking-wider">WAJIB • Max 2MB</p>
        </div>
    </div>
</div>

        {{-- Informasi Umum --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 mb-4">
            <div class="p-6 border-b border-gray-100 bg-gray-50">
                <h2 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
                    <svg class="w-5 h-5 text-blue-900" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Informasi Umum
                </h2>
            </div>
            
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label class="text-sm font-semibold text-gray-700">Nama Toko <span class="text-red-500">*</span></label>
                    <input type="text" name="nama_toko" value="{{ old('nama_toko', $toko->nama_toko ?? '') }}" required
                           class="w-full px-4 py-3 bg-gray-50 border {{ $errors->has('nama_toko') ? 'border-red-400' : 'border-gray-200' }} rounded-xl focus:bg-white focus:ring-4 focus:ring-blue-100 outline-none transition">
                </div>

                <div class="space-y-2">
                    <label class="text-sm font-semibold text-gray-700">No. Telepon <span class="text-red-500">*</span></label>
                    <input type="text" name="no_telepon" value="{{ old('no_telepon', $toko->no_telepon ?? '') }}" required
                           class="w-full px-4 py-3 bg-gray-50 border {{ $errors->has('no_telepon') ? 'border-red-400' : 'border-gray-200' }} rounded-xl focus:bg-white focus:ring-4 focus:ring-blue-100 outline-none transition">
                </div>

                <div class="space-y-2 md:col-span-2">
                    <label class="text-sm font-semibold text-gray-700">Email Bisnis <span class="text-red-500">*</span></label>
                    <input type="email" name="email" value="{{ old('email', $toko->email ?? '') }}" required
                           class="w-full px-4 py-3 bg-gray-50 border {{ $errors->has('email') ? 'border-red-400' : 'border-gray-200' }} rounded-xl focus:bg-white focus:ring-4 focus:ring-blue-100 outline-none transition">
                </div>

                <div class="space-y-2 md:col-span-2">
                    <label class="text-sm font-semibold text-gray-700">Alamat Lengkap <span class="text-red-500">*</span></label>
                    <textarea name="alamat_toko" rows="3" required 
                              class="w-full px-4 py-3 bg-gray-50 border {{ $errors->has('alamat_toko') ? 'border-red-400' : 'border-gray-200' }} rounded-xl focus:bg-white focus:ring-4 focus:ring-blue-100 outline-none transition resize-none">{{ old('alamat_toko', $toko->alamat_toko ?? '') }}</textarea>
                </div>
            </div>
        </div>

        {{-- Rekening --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 mb-6">
            <div class="p-6 border-b border-gray-100 bg-gray-50">
                <h2 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
                    <svg class="w-5 h-5 text-blue-900" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                    Rekening Bank
                </h2>
            </div>
            
            <div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="space-y-2">
                    <label class="text-sm font-semibold text-gray-700">Nama Bank <span class="text-red-500">*</span></label>
                    <input type="text" name="nama_bank" value="{{ old('nama_bank', $toko->nama_bank ?? '') }}" required
                           class="w-full px-4 py-3 bg-gray-50 border {{ $errors->has('nama_bank') ? 'border-red-400' : 'border-gray-200' }} rounded-xl outline-none transition">
                </div>

                <div class="space-y-2">
                    <label class="text-sm font-semibold text-gray-700">No. Rekening <span class="text-red-500">*</span></label>
                    <input type="text" name="no_rekening" value="{{ old('no_rekening', $toko->no_rekening ?? '') }}" required
                           class="w-full px-4 py-3 bg-gray-50 border {{ $errors->has('no_rekening') ? 'border-red-400' : 'border-gray-200' }} rounded-xl outline-none transition">
                </div>

                <div class="space-y-2">
                    <label class="text-sm font-semibold text-gray-700">Nama Pemilik <span class="text-red-500">*</span></label>
                    <input type="text" name="pemilik_rekening" value="{{ old('pemilik_rekening', $toko->pemilik_rekening ?? '') }}" required
                           class="w-full px-4 py-3 bg-gray-50 border {{ $errors->has('pemilik_rekening') ? 'border-red-400' : 'border-gray-200' }} rounded-xl outline-none transition">
                </div>
            </div>
        </div>

        <div class="p-4">
            <div class="max-w-6xl mx-auto flex justify-end gap-3">
                <a href="{{ route('dashboard.index') }}" class="px-8 py-3 bg-white border border-gray-200 text-gray-600 rounded-lg font-semibold transition active:scale-95">
                    Batal
                </a>
                <button type="submit" class="px-8 py-3 bg-blue-900 text-white rounded-lg font-bold shadow-lg shadow-blue-200 hover:bg-blue-800 transition active:scale-95">
                    Simpan Perubahan
                </button>
            </div>
        </div>
    </form>
</div>
@endsection