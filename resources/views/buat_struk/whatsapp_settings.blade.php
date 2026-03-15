@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-10">
    <div class="max-w-md mx-auto bg-white p-8 rounded-2xl shadow-xl border border-gray-100 text-center">
        <div class="mb-6">
            <h2 class="text-2xl font-black text-gray-800 tracking-tight">WhatsApp Gateway</h2>
            <p id="status-badge" class="mt-2 inline-block px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest bg-gray-100 text-gray-400">
                Mengecek Koneksi...
            </p>
        </div>

        {{-- Area QR Code --}}
        <div id="qr-container" class="hidden animate-pulse">
            <div class="flex justify-center mb-6">
                <div id="qrcode" class="p-4 border-4 border-dashed border-blue-100 rounded-2xl bg-gray-50">
                    {{-- QR Code akan muncul di sini --}}
                </div>
            </div>
            <p class="text-sm text-gray-500 leading-relaxed">
                Buka WhatsApp di HP Anda, ketuk <strong>Perangkat Tertaut</strong>, lalu arahkan kamera ke sini.
            </p>
        </div>

        {{-- Area Berhasil Terhubung --}}
        <div id="success-container" class="hidden py-10">
            <div class="bg-green-100 w-24 h-24 rounded-full flex items-center justify-center mx-auto mb-6 shadow-lg shadow-green-100">
                <svg class="w-12 h-12 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
            <h3 class="text-xl font-bold text-gray-800">WhatsApp Terhubung!</h3>
            <p class="text-sm text-gray-500 mt-2">Sistem siap mengirim struk otomatis.</p>
        </div>

        {{-- Area Gagal Koneksi --}}
        <div id="error-container" class="hidden py-10 text-red-500">
            <svg class="w-16 h-16 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
            <p class="font-bold">Gagal terhubung ke Server Bot</p>
            <p class="text-xs mt-1">Pastikan Anda sudah menjalankan <code>node index.js</code> di terminal.</p>
        </div>
    </div>
</div>

{{-- Script QR Code Generator --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>

<script>
    const qrDiv = document.getElementById("qrcode");
    const qrContainer = document.getElementById("qr-container");
    const successContainer = document.getElementById("success-container");
    const errorContainer = document.getElementById("error-container");
    const statusBadge = document.getElementById("status-badge");
    
    let lastQR = "";

    function checkStatus() {
        fetch('http://localhost:3000/whatsapp-status')
            .then(res => res.json())
            .then(data => {
                errorContainer.classList.add("hidden");
                
                if (data.status === "CONNECTED") {
                    statusBadge.innerText = "Connected";
                    statusBadge.className = "mt-2 inline-block px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest bg-green-100 text-green-600";
                    qrContainer.classList.add("hidden");
                    successContainer.classList.remove("hidden");
                } 
                else if (data.status === "WAITING_FOR_SCAN") {
                    statusBadge.innerText = "Waiting for Scan";
                    statusBadge.className = "mt-2 inline-block px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest bg-yellow-100 text-yellow-600";
                    qrContainer.classList.remove("hidden");
                    successContainer.classList.add("hidden");
                    
                    // Update QR hanya jika kodenya berubah (biar tidak flicker)
                    if (lastQR !== data.qr) {
                        lastQR = data.qr;
                        qrDiv.innerHTML = "";
                        new QRCode(qrDiv, {
                            text: data.qr,
                            width: 220,
                            height: 220,
                            colorDark: "#1e3a8a",
                            colorLight: "#ffffff",
                        });
                    }
                }
                else {
                    statusBadge.innerText = "Disconnected";
                    statusBadge.className = "mt-2 inline-block px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest bg-red-100 text-red-600";
                }
            })
            .catch(err => {
                qrContainer.classList.add("hidden");
                successContainer.classList.add("hidden");
                errorContainer.classList.remove("hidden");
                statusBadge.innerText = "Offline";
            });
    }

    // Polling setiap 3 detik
    setInterval(checkStatus, 3000);
    checkStatus();
</script>
@endsection