<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login | Invoice App</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center p-6">

    <div class="w-full max-w-md">
        <div class="flex flex-col items-center mb-8">
            <div class="w-12 h-12 bg-blue-900 rounded-lg flex items-center justify-center text-white font-bold text-xl mb-4">
                IP
            </div>
            <h1 class="text-xl font-extrabold text-blue-900 tracking-tighter uppercase">
                Invoice <span class="text-blue-500">App</span>
            </h1>
        </div>

        <div class="bg-white border border-gray-200 rounded-lg p-8 shadow-sm">
            <div class="mb-6 text-center">
                <h2 class="text-xl font-bold text-gray-900">Selamat Datang</h2>
                <p class="text-gray-500 text-sm">Silakan masuk untuk mengelola toko Anda.</p>
            </div>

            @if ($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-600 text-sm rounded-lg p-3 mb-5">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="mb-4">
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Alamat Email</label>
                    <input
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        placeholder="nama@perusahaan.com"
                        required
                        autofocus
                        class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-gray-900 text-sm 
                               focus:outline-none focus:ring-2 focus:ring-blue-900/10 focus:border-blue-900 transition"
                    >
                </div>

                <div class="mb-6">
                    <div class="flex justify-between items-center mb-1.5">
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider">Password</label>
                    </div>
                    <input
                        type="password"
                        name="password"
                        placeholder="••••••••"
                        required
                        class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-gray-900 text-sm 
                               focus:outline-none focus:ring-2 focus:ring-blue-900/10 focus:border-blue-900 transition"
                    >
                </div>

                <button
                    type="submit"
                    class="w-full bg-blue-900 text-white font-bold py-3 rounded-lg hover:bg-blue-800 transition shadow-sm active:transform active:scale-[0.98]"
                >
                    Masuk Sekarang
                </button>
            </form>
        </div>

        <div class="mt-8 flex justify-between items-center px-2">
            <p class="text-xs text-gray-400">
                &copy; {{ date('Y') }} Invoice App.
            </p>
            <a href="/" class="text-xs text-gray-500 hover:text-blue-900 font-medium transition">
                &larr; Kembali ke Home
            </a>
        </div>
    </div>

</body>
</html>