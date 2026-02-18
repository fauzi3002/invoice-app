@extends('layouts.app')

@section('content')
<div id="pageWrapper" class="pb-40 md:pb-24 min-h-screen max-w-full mx-auto px-4">
    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">
        <div>
            <h2 class="text-2xl font-bold text-gray-800 tracking-tight">Pengaturan Toko</h2>
            <p class="text-sm text-gray-500 mt-1">Kelola identitas visual dan informasi operasional bisnis Anda</p>
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

    <form action="{{ route('pengaturan_toko.update', $toko->id ?? 1) }}" 
          enctype="multipart/form-data" 
          method="POST" 
          class="space-y-6">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            {{-- Sidebar Kiri: Identitas Visual --}}
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg border border-gray-200 overflow-hidden sticky top-6">
                    <div class="p-4 border-b border-gray-100 bg-gray-50/50">
                        <h3 class="text-sm font-bold text-gray-700 uppercase tracking-wider">Identitas Visual</h3>
                    </div>
                    <div class="p-6 flex flex-col items-center" 
                         x-data="{ logoPreview: '{{ $toko?->logo_toko ? asset('storage/' . $toko->logo_toko) : '' }}' }">
                        
                        <div class="relative group w-32 h-32 mb-4">
                            <div class="w-full h-full rounded-lg border-2 border-dashed {{ $errors->has('logo_toko') ? 'border-red-300' : 'border-gray-200' }} flex items-center justify-center overflow-hidden bg-gray-50 transition-all group-hover:border-blue-400">
                                <template x-if="logoPreview">
                                    <img :src="logoPreview" class="w-full h-full object-cover">
                                </template>
                                <template x-if="!logoPreview">
                                    <div class="text-center p-2">
                                        <svg class="w-8 h-8 text-gray-300 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4" stroke-width="2" stroke-linecap="round"/></svg>
                                    </div>
                                </template>
                                
                                <div @click="$refs.fileInput.click()" class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center cursor-pointer">
                                    <span class="text-white text-xs font-medium">Ubah Logo</span>
                                </div>
                            </div>
                        </div>

                        <input type="file" name="logo_toko" x-ref="fileInput" accept="image/*" class="hidden"
                               @change="const file = $event.target.files[0]; if (file) { const reader = new FileReader(); reader.onload = (e) => { logoPreview = e.target.result; }; reader.readAsDataURL(file); }">
                        
                        <div class="text-center">
                            <p class="text-xs text-gray-500 leading-relaxed">Format: JPG, PNG atau WebP.<br>Maksimal 2MB</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Kolom Kanan: Form Detail --}}
            <div class="lg:col-span-2 space-y-6">
                
                {{-- Informasi Umum --}}
                <div class="bg-white rounded-lg border border-gray-200 shadow-sm">
                    <div class="p-5 border-b border-gray-100 flex items-center gap-3">
                        <div class="p-2 bg-blue-50 rounded-lg">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        </div>
                        <h3 class="font-bold text-gray-800">Informasi Bisnis</h3>
                    </div>
                    
                    <div class="p-6 space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="space-y-1.5">
                                <label class="text-xs font-bold text-gray-600 uppercase">Nama Toko <span class="text-red-500">*</span></label>
                                <input type="text" name="nama_toko" value="{{ old('nama_toko', $toko->nama_toko ?? '') }}" required
                                       class="w-full px-4 py-2.5 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition text-sm">
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-xs font-bold text-gray-600 uppercase">No. Telepon <span class="text-red-500">*</span></label>
                                <input type="text" name="no_telepon" value="{{ old('no_telepon', $toko->no_telepon ?? '') }}" required
                                       class="w-full px-4 py-2.5 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition text-sm">
                            </div>
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-xs font-bold text-gray-600 uppercase">Email Resmi <span class="text-red-500">*</span></label>
                            <input type="email" name="email" value="{{ old('email', $toko->email ?? '') }}" required
                                   class="w-full px-4 py-2.5 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition text-sm">
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-xs font-bold text-gray-600 uppercase">Alamat Lengkap <span class="text-red-500">*</span></label>
                            <textarea name="alamat_toko" rows="3" required 
                                      class="w-full px-4 py-2.5 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition text-sm resize-none">{{ old('alamat_toko', $toko->alamat_toko ?? '') }}</textarea>
                        </div>
                    </div>
                </div>

                {{-- Rekening --}}
                <div class="bg-white rounded-lg border border-gray-200 shadow-sm">
                    <div class="p-5 border-b border-gray-100 flex items-center gap-3">
                        <div class="p-2 bg-emerald-50 rounded-lg">
                            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                        </div>
                        <h3 class="font-bold text-gray-800">Informasi Pembayaran</h3>
                    </div>
                    
                    <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-1.5">
                            <label class="text-xs font-bold text-gray-600 uppercase">Bank <span class="text-red-500">*</span></label>
                            <input type="text" name="nama_bank" value="{{ old('nama_bank', $toko->nama_bank ?? '') }}" required
                                   placeholder="Contoh: BCA / Mandiri"
                                   class="w-full px-4 py-2.5 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none transition text-sm">
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-xs font-bold text-gray-600 uppercase">No. Rekening <span class="text-red-500">*</span></label>
                            <input type="text" name="no_rekening" value="{{ old('no_rekening', $toko->no_rekening ?? '') }}" required
                                   class="w-full px-4 py-2.5 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none transition text-sm">
                        </div>
                        <div class="space-y-1.5 md:col-span-2">
                            <label class="text-xs font-bold text-gray-600 uppercase">Nama Pemilik Rekening <span class="text-red-500">*</span></label>
                            <input type="text" name="pemilik_rekening" value="{{ old('pemilik_rekening', $toko->pemilik_rekening ?? '') }}" required
                                   class="w-full px-4 py-2.5 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none transition text-sm">
                        </div>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="flex items-center justify-end gap-3 pt-2">
                    <a href="{{ route('dashboard.index') }}" 
                       class="px-6 py-2.5 text-sm font-bold text-gray-500 hover:text-gray-700 transition">
                        Batal
                    </a>
                    <button type="submit" 
                            class="px-8 py-2.5 bg-blue-900 text-white rounded-lg text-sm font-bold shadow-md hover:bg-blue-800 focus:ring-4 focus:ring-blue-900/20 transition-all active:scale-95">
                        Simpan Perubahan
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection