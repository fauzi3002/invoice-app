<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice App</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        [x-cloak] { display: none !important; }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>
<body class="bg-gray-50 text-gray-800" x-data="{ sidebarOpen: false }">

<div class="flex h-screen overflow-hidden">

    @persist('sidebar')
    <aside
        class="fixed inset-y-0 left-0 z-40 w-64 bg-white border-r border-gray-200 transform transition-all duration-300 ease-in-out -translate-x-full md:translate-x-0 flex flex-col"
        :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full md:translate-x-0'"
    >
        <div class="h-20 flex items-center px-6 border-b border-gray-100">
            <div class="w-10 h-10 bg-blue-900 rounded-lg flex items-center justify-center text-white font-bold text-sm">
                IP
            </div>
            <span class="ml-3 text-lg font-black tracking-tighter uppercase text-blue-900">Invoice <span class="text-blue-500">App</span></span>
        </div>

        <div class="px-4 py-6">
            <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-blue-100 border border-blue-200 overflow-hidden shrink-0 flex items-center justify-center">
                        @if (Auth::user() && Auth::user()->profile_photo_path)
                            <img src="{{ asset('storage/' . Auth::user()->profile_photo_path) }}" alt="Profile" class="w-full h-full object-cover">
                        @else
                            <span class="text-blue-900 font-bold text-sm">{{ substr(Auth::user()->name ?? 'A', 0, 1) }}</span>
                        @endif
                    </div>
                    <div class="overflow-hidden">
                        <p class="font-bold text-xs text-gray-900 truncate">{{ Auth::user() ? Auth::user()->name : 'Admin' }}</p>
                        <p class="text-[10px] text-gray-500 font-medium truncate uppercase tracking-widest">Administrator</p>
                    </div>
                </div>
            </div>
        </div>

        <nav class="flex-1 px-3 space-y-1 overflow-y-auto no-scrollbar">
            <p class="px-4 text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em] mb-3">Menu Utama</p>
            
            @php
                $menus = [
                    ['route' => 'dashboard.index', 'icon' => 'M3 13h1v7c0 1.103.897 2 2 2h12c1.103 0 2-.897 2-2v-7h1a1 1 0 0 0 .707-1.707l-9-9a.999.999 0 0 0-1.414 0l-9 9A1 1 0 0 0 3 13zm7 7v-5h4v5h-4zm2-15.586 6 6V13l.001 7H16v-5c0-1.103-.897-2-2-2h-4c-1.103 0-2 .897-2 2v5H5v-9.586l6-6z', 'label' => 'Dashboard', 'active' => 'dashboard'],
                    ['route' => 'produk.index', 'icon' => 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4', 'label' => 'Produk', 'active' => 'produk.*', 'is_svg_path' => true],
                    ['route' => 'buat_struk.create', 'icon' => 'M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm5 11h-4v4h-2v-4H7v-2h4V7h2v4h4v2z', 'label' => 'Buat Struk', 'active' => 'buat_struk.create'],
                    ['route' => 'notification.index', 'icon' => 'M12 22c1.104 0 2-.896 2-2h-4c0 1.104.896 2 2 2zm6-6V11c0-3.07-1.64-5.64-4.5-6.32V4a1.5 1.5 0 10-3 0v.68C7.64 5.36 6 7.92 6 11v5l-2 2v1h16v-1l-2-2z', 'label' => 'Notification', 'active' => 'notification.index'],
                ];
            @endphp

            @foreach($menus as $menu)
            <div class="p-1">
                <a href="{{ route($menu['route']) }}" wire:navigate
                class="flex items-center gap-3 px-4 py-2.5 rounded-lg transition-all duration-200 group {{ request()->routeIs($menu['active']) ? 'bg-blue-50 text-blue-900 font-bold' : 'hover:bg-gray-50 text-gray-600 hover:text-blue-900' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 {{ request()->routeIs($menu['active']) ? 'text-blue-900' : 'text-gray-400 group-hover:text-blue-900' }}" fill="currentColor" viewBox="0 0 24 24">
                        @if(isset($menu['is_svg_path']))
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $menu['icon'] }}" fill="none"/>
                        @else
                            <path d="{{ $menu['icon'] }}"/>
                        @endif
                    </svg>
                    <span class="text-sm tracking-tight">{{ $menu['label'] }}</span>
                </a>
            </div>
            @endforeach

            <div class="pt-4 mt-4 border-t border-gray-100">
                <p class="px-4 text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em] mb-3">Sistem</p>
                <div class="p-1">
                    <a href="{{ route('pengaturan_toko.index') }}" wire:navigate
                    class="flex items-center gap-3 px-4 py-2.5 rounded-lg transition-all duration-200 group {{ request()->routeIs('pengaturan_toko.*') ? 'bg-blue-50 text-blue-900 font-bold' : 'hover:bg-gray-50 text-gray-600 hover:text-blue-900' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 {{ request()->routeIs('pengaturan_toko.*') ? 'text-blue-900' : 'text-gray-400 group-hover:text-blue-900' }}" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 16c2.206 0 4-1.794 4-4s-1.794-4-4-4-4 1.794-4 4 1.794 4 4 4zm0-6c1.103 0 2 .897 2 2s-.897 2-2 2-2-.897-2-2 .897-2 2-2z"/><path d="m19.98 10.356-.566-2.465-2.369-.739-.231-.41a7.03 7.03 0 0 0-2.367-2.367l-.41-.231-.739-2.369-2.465-.566-2.465.566-.739 2.369-.41.231a7.03 7.03 0 0 0-2.367 2.367l-.231.41-2.369.739-.566 2.465.566 2.465 2.369.739.231.41a7.03 7.03 0 0 0 2.367 2.367l.41.231.739 2.369 2.465.566 2.465-.566.739-2.369.41-.231a7.03 7.03 0 0 0 2.367-2.367l.231-.41 2.369-.739.566-2.465zM12 19c-3.859 0-7-3.141-7-7s3.141-7 7-7 7 3.141 7 7-3.141 7-7 7z"/>
                        </svg>
                        <span class="text-sm tracking-tight">Pengaturan</span>
                    </a>
                </div>
            </div>
        </nav>

        <div class="p-4 border-t border-gray-100">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="flex items-center justify-center gap-2 w-full px-4 py-2.5 rounded-lg border border-gray-200 text-gray-600 hover:text-red-600 hover:bg-red-50 hover:border-red-100 text-xs font-bold transition-all duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    LOGOUT
                </button>
            </form>
        </div>
    </aside>
    @endpersist

    <div class="flex-1 flex flex-col md:ml-64 transition-all duration-300">
        <header class="fixed top-0 left-0 md:left-64 right-0 h-20 bg-white border-b border-gray-100 flex items-center justify-between px-6 z-30">
            <button @click="sidebarOpen = !sidebarOpen" class="md:hidden text-blue-900 p-2 bg-gray-50 border border-gray-100 rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7" /></svg>
            </button>
            
            <div class="hidden md:block text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                Sistem Manajemen Kasir v1.0
            </div>

            <div class="flex items-center gap-4">
                <div class="text-right hidden sm:block border-r pr-4 border-gray-100">
                    <p class="text-[10px] font-black text-blue-900 uppercase tracking-tighter">{{ Auth::user()->name ?? 'Admin' }}</p>
                    <p class="text-[9px] text-green-500 font-bold flex items-center justify-end gap-1">
                        <span class="w-1.5 h-1.5 bg-green-500 rounded-full"></span> ONLINE
                    </p>
                </div>
                <div class="w-9 h-9 rounded-lg bg-gray-50 flex items-center justify-center text-blue-900 border border-gray-100">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                </div>
            </div>
        </header>

        <main class="flex-1 overflow-y-auto pt-28 px-6 pb-12">
            <div class="w-full">
                @yield('content')
            </div>
        </main>
    </div>

    <div x-show="sidebarOpen" @click="sidebarOpen = false" class="fixed inset-0 bg-gray-900/20 backdrop-blur-sm z-30 md:hidden" x-cloak></div>

</div>