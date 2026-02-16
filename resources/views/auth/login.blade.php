<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login | Invoice App</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-white min-h-screen flex items-center justify-center">

    <div class="w-full max-w-md px-6">
        <div class="bg-blue-900 text-white rounded-2xl shadow-lg p-8">

            <!-- Judul -->
            <h2 class="text-2xl font-bold text-center mb-2">
                Login
            </h2>
            <p class="text-blue-100 text-center mb-6 text-sm">
                Masuk untuk mengelola produk dan invoice
            </p>

            <!-- Error -->
            @if ($errors->any())
                <div class="bg-red-500/20 text-red-200 text-sm rounded-lg p-3 mb-4">
                    {{ $errors->first() }}
                </div>
            @endif

            <!-- Form -->
            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Email -->
                <div class="mb-4">
                    <label class="block text-sm mb-1">Email</label>
                    <input
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        autofocus
                        class="w-full px-4 py-2 rounded-lg text-blue-900
                               focus:outline-none focus:ring-2 focus:ring-blue-300"
                    >
                </div>

                <!-- Password -->
                <div class="mb-6">
                    <label class="block text-sm mb-1">Password</label>
                    <input
                        type="password"
                        name="password"
                        required
                        class="w-full px-4 py-2 rounded-lg text-blue-900
                               focus:outline-none focus:ring-2 focus:ring-blue-300"
                    >
                </div>

                <!-- Button -->
                <button
                    type="submit"
                    class="w-full bg-white text-blue-900 font-semibold py-2 rounded-lg
                           hover:bg-blue-100 transition"
                >
                    Login
                </button>
            </form>
        </div>

        <!-- Footer -->
        <p class="text-center text-gray-400 text-sm mt-6">
            © {{ date('Y') }} Invoice App
        </p>
    </div>

</body>
</html>
