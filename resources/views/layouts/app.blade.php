<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles

    <style>
        /* Container Utama Toastr */
    #toast-container {
        pointer-events: auto !important;
    }

    /* Posisi: Turunkan lebih banyak (misal: 70px) agar tidak menutupi navbar/menu */
    .toast-top-center {
        top: 70px !important; 
        left: 50% !important;
        transform: translateX(-50%) !important;
        display: flex !important;
        flex-direction: column !important;
        align-items: center !important;
        width: 100% !important;
    }

    /* Kustomisasi Desain Toast */
    #toast-container > div {
        background-color: #1e3a8a !important; /* blue-900 */
        color: #ffffff !important;
        border-radius: 12px !important; /* Disesuaikan agar lebih halus */
        opacity: 1 !important;
        padding: 16px 45px 16px 50px !important; /* Padding kanan ditambah untuk tombol close */
        width: 90% !important;
        max-width: 400px !important;
        margin: 0 auto 10px auto !important;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05) !important;
        border: 1px solid rgba(255, 255, 255, 0.1) !important;
    }

    /* Tombol Close (X) yang lebih halus */
    .toast-close-button {
        top: 50% !important;
        right: 15px !important;
        transform: translateY(-50%);
        position: absolute !important;
        font-size: 20px !important;
        font-weight: 300 !important; /* Tipis agar elegan */
        line-height: 1 !important;
        color: #ffffff !important;
        opacity: 0.6 !important;
        transition: opacity 0.2s ease;
    }

    .toast-close-button:hover {
        opacity: 1 !important;
    }

    /* Responsif untuk Mobile */
    @media (max-width: 640px) {
        .toast-top-center {
            top: 60px !important; /* Jarak aman dari tombol menu mobile */
        }
        #toast-container > div {
            width: 85% !important; 
            font-size: 13px !important;
        }
    }

    /* Ikon Success & Error tetap sama namun disesuaikan posisinya */
    #toast-container > .toast-success::before, 
    #toast-container > .toast-error::before {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        width: 22px;
        height: 22px;
    }

        .btn-loading {
            position: relative;
            color: transparent !important;
            pointer-events: none;
        }
        .btn-loading::after {
            content: "";
            position: absolute;
            width: 20px;
            height: 20px;
            top: 0; left: 0; right: 0; bottom: 0;
            margin: auto;
            border: 3px solid rgba(255,255,255,0.3);
            border-radius: 50%;
            border-top-color: white;
            animation: spin 0.8s ease-in-out infinite;
        }
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
    </style>
</head>

<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-200">
        @include('layouts.navigation')
        {{ $slot ?? '' }}
    </div>

    {{-- TOAST COMPONENT --}}
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <script>
            toastr.options = {
            "closeButton": true,
            "closeHtml": '<button>&times;</button>', // Gunakan entitas HTML standar
            "debug": false,
            "newestOnTop": true,
            "progressBar": false,
            "positionClass": "toast-top-center",
            "preventDuplicates": true,
            "onclick": null,
            "showDuration": "400", // Sedikit diperlambat agar lebih halus
            "hideDuration": "1000",
            "timeOut": "5000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        };

        @if(Session::has('success'))
            toastr.success("{{ Session::get('success') }}");
        @endif

        @if(Session::has('error'))
            toastr.error("{{ Session::get('error') }}");
        @endif
    </script>

    @stack('scripts')
    @livewireScripts
</body>
</html>
