<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Invoice App</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-50 min-h-screen flex flex-col items-center justify-center p-6">

    <div class="max-w-md w-full">
        
        <div class="bg-white border border-gray-200 rounded-lg p-8 shadow-sm">
            
            <div class="flex justify-center mb-8">
                <div class="w-12 h-12 bg-blue-900 rounded-lg flex items-center justify-center text-white font-bold text-xl">
                    IP
                </div>
            </div>

            <div class="text-center mb-10">
                <h1 class="text-2xl font-bold text-gray-900 mb-3">
                    Invoice App
                </h1>
                <p class="text-gray-500 text-sm leading-relaxed">
                    Kelola produk, stok, dan cetak invoice dalam satu platform sederhana.
                </p>
            </div>

            <div class="space-y-3">
                <a href="{{ route('login') }}"
                   class="block w-full bg-blue-900 text-white text-center font-semibold py-3 rounded-lg hover:bg-blue-800 transition">
                    Masuk ke Akun
                </a>
                <p class="text-[10px] text-center text-gray-400 uppercase tracking-widest pt-2">
                    Sistem Manajemen Kasir v1.0
                </p>
            </div>
        </div>

        <footer class="mt-8 flex justify-between items-center px-2">
            <span class="text-xs text-gray-400">© {{ date('Y') }} Invoice App</span>
            <div class="flex gap-4">
                <span class="text-xs text-green-600 font-medium flex items-center gap-1">
                    <span class="w-1.5 h-1.5 bg-green-500 rounded-full"></span> System Online
                </span>
            </div>
        </footer>
    </div>

</body>
</html>