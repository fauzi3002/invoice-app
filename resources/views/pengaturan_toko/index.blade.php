@extends('layouts.app')
<style>
    #signature-pad {
        cursor: crosshair;
        touch-action: none;
        /* Menghaluskan tampilan canvas secara visual di level browser */
        image-rendering: -webkit-optimize-contrast;
        image-rendering: crisp-edges;
        image-rendering: high-quality;
    }
</style>
@section('content')
<div id="pageWrapper" class="container mx-auto px-4 pt-20 md:pt-6 pb-24">
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

    <form id="mainForm" action="{{ route('pengaturan_toko.update', $toko->id ?? 1) }}" 
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

                {{-- Otorisasi & Tanda Tangan --}}
                <div class="bg-white rounded-lg border border-gray-200 shadow-sm">
                    <div class="p-5 border-b border-gray-100 flex items-center gap-3">
                        <div class="p-2 bg-purple-50 rounded-lg">
                            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                        </div>
                        <h3 class="font-bold text-gray-800">Otorisasi & Tanda Tangan</h3>
                    </div>
                    
                    <div class="p-6" x-data="signatureHandler()">
                        <div class="space-y-4">
                            <label class="text-xs font-bold text-gray-600 uppercase tracking-wider">Tanda Tangan Digital</label>
                            
                            {{-- Preview Tanda Tangan yang Sudah Ada --}}
                            <div x-show="existingSig && !isEditing" class="flex flex-col items-center p-6 border-2 border-gray-100 rounded-xl bg-gray-50/50">
                                <img :src="existingSig" class="max-h-40 mix-blend-multiply" alt="Tanda Tangan">
                                <button type="button" @click="startEdit()" class="mt-4 text-sm font-bold text-blue-600 hover:text-blue-800 transition">
                                    Ubah Tanda Tangan
                                </button>
                            </div>

                            {{-- Area Canvas Baru --}}
                            <div x-show="isEditing || !existingSig" x-cloak>
                                <div class="relative w-full border-2 border-dashed border-gray-300 rounded-xl bg-white overflow-hidden group hover:border-blue-400 transition-colors">
                                    {{-- Tinggi canvas ditingkatkan ke h-64 untuk ruang lebih besar --}}
                                    <canvas id="signature-pad" class="w-full h-64 touch-none cursor-crosshair"></canvas>
                                    
                                    <div class="absolute bottom-3 right-3 flex gap-2">
                                        <button type="button" @click="clear()" class="px-3 py-2 bg-white shadow-md rounded-lg text-red-500 hover:bg-red-50 border border-gray-200 transition flex items-center gap-2 text-xs font-bold">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            Hapus
                                        </button>
                                    </div>
                                </div>
                                <div class="flex justify-between mt-3">
                                    <p class="text-xs text-gray-400 italic font-medium">Silakan tanda tangan di dalam kotak area putih di atas.</p>
                                    <button x-show="existingSig" type="button" @click="cancelEdit()" class="text-xs font-bold text-gray-500 hover:text-gray-700">Batal</button>
                                </div>
                            </div>

                            {{-- Hidden input untuk dikirim ke Controller --}}
                            <input type="hidden" name="tanda_tangan" id="tanda_tangan_input">
                        </div>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="flex items-center justify-end gap-3 pt-2">
                    <a href="{{ route('dashboard.index') }}" 
                       class="px-6 py-2.5 text-sm font-bold text-gray-500 hover:text-gray-700 transition">
                        Batal
                    </a>
                    <button type="submit" id="btnSubmit"
                            class="px-8 py-2.5 bg-blue-900 text-white rounded-lg text-sm font-bold shadow-md hover:bg-blue-800 focus:ring-4 focus:ring-blue-900/20 transition-all active:scale-95">
                        Simpan Perubahan
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/fabric.js/5.3.1/fabric.min.js"></script>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('signatureHandler', () => ({
        canvas: null,
        isEditing: false,
        existingSig: "{{ $toko?->tanda_tangan ? asset('storage/' . $toko->tanda_tangan) : '' }}",

        init() {
            if (!this.existingSig) {
                this.$nextTick(() => this.initFabric());
            }
        },

        initFabric() {
            const canvasEl = document.getElementById('signature-pad');
            
            // 1. Inisialisasi Canvas dengan Retain Object Stack
            this.canvas = new fabric.Canvas('signature-pad', {
                isDrawingMode: true,
                width: canvasEl.offsetWidth,
                height: 256,
                enableRetinaScaling: true, // Super tajam di layar HP/Retina
                backgroundColor: 'rgba(255,255,255,0)'
            });

            // 2. KONFIGURASI BRUSH "RAJA KOREKSI"
            const brush = new fabric.PencilBrush(this.canvas);
            
            // Warna & Ketebalan Ramping (Elegan)
            brush.color = 'rgb(30, 58, 138)'; 
            brush.width = 1.8; 

            /** * SUPER KOREKSI LEVEL 1: Decimate
             * Menghapus titik input mouse yang terlalu rapat (penyebab garis bergerigi)
             */
            brush.decimate = 8; 

            /**
             * SUPER KOREKSI LEVEL 2: Shadow Smoothing
             * Memberikan sedikit glow tipis untuk menyamarkan pixel yang tajam
             */
            brush.shadow = new fabric.Shadow({
                blur: 1,
                offsetX: 0,
                offsetY: 0,
                affectStroke: true,
                color: 'rgba(30, 58, 138, 0.15)'
            });

            this.canvas.freeDrawingBrush = brush;

            // 3. SUPER KOREKSI LEVEL 3: Post-Drawing Path Smoothing
            // Ini adalah rahasia "KTP-el". Setelah mouse dilepas, garis dikoreksi ulang.
            this.canvas.on('path:created', (options) => {
                const path = options.path;
                
                // Mengurangi jumlah point pada path secara matematis
                path.set({
                    strokeLineCap: 'round',
                    strokeLineJoin: 'round',
                    strokeMiterLimit: 10,
                    // Efek visual halus
                    perPixelTargetFind: true
                });

                // Render ulang agar garis terlihat 'smooth' seketika
                this.canvas.renderAll();
            });

            window.fabricCanvas = this.canvas;
        },

        startEdit() {
            this.isEditing = true;
            this.$nextTick(() => {
                // Pastikan canvas di-resize ulang jika container berubah
                const canvasEl = document.getElementById('signature-pad');
                if(this.canvas) {
                    this.canvas.setDimensions({
                        width: canvasEl.offsetWidth,
                        height: 256
                    });
                } else {
                    this.initFabric();
                }
            });
        },

        clear() {
            if (this.canvas) {
                this.canvas.clear();
                this.canvas.renderAll();
            }
        },

        cancelEdit() {
            this.isEditing = false;
        }
    }));
});

// Logic Submit Form
document.getElementById('mainForm').addEventListener('submit', function (e) {
    const canvas = window.fabricCanvas;
    if (canvas) {
        // Cek apakah canvas kosong (memiliki objek atau tidak)
        if (canvas.getObjects().length > 0) {
            // Export ke format PNG dengan kualitas tinggi
            const dataURL = canvas.toDataURL({
                format: 'png',
                quality: 1
            });
            document.getElementById('tanda_tangan_input').value = dataURL;
        }
    }
});
</script>
@endpush
@endsection