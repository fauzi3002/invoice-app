<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Invoice App</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-white min-h-screen flex items-center justify-center">

    <div class="max-w-4xl w-full px-6">
        <div class="bg-blue-900 rounded-2xl shadow-lg p-10 text-white text-center">

            <!-- Judul -->
            <h1 class="text-3xl md:text-4xl font-bold mb-4">
                Aplikasi Invoice & Manajemen Produk
            </h1>

            <!-- Deskripsi -->
            <p class="text-blue-100 mb-8">
                Kelola produk, stok, dan cetak invoice dengan mudah, cepat, dan rapi.
                Cocok untuk toko kecil dan usaha pribadi.
            </p>

            <!-- Tombol Login -->
            <a href="{{ route('login') }}"
               class="inline-block bg-white text-blue-900 font-semibold px-8 py-3 rounded-lg
                      hover:bg-blue-100 transition">
                Login
            </a>

        </div>

        <!-- Footer kecil -->
        <p class="text-center text-gray-400 text-sm mt-6">
            © {{ date('Y') }} Invoice App
        </p>
    </div>

</body>
</html>
