<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard') — Wae Watu Reef Resort</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            dark: '#0A1826',
                            gold: '#D4AF37',
                            teal: '#164E63',
                            cream: '#F7F5F0',
                        }
                    }
                }
            }
        }
    </script>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;1,400&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .font-serif { font-family: 'Cormorant Garamond', serif; }
    </style>
</head>
<body class="bg-[#F0F3F4] text-slate-800 antialiased min-h-screen flex flex-col">

    <!-- Top Admin Header Bar -->
    <header class="bg-brand-dark text-white shadow-lg border-b border-brand-gold/20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <a href="{{ url('/admin') }}" class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-full border border-brand-gold/40 flex items-center justify-center bg-brand-dark">
                        <span class="font-serif italic text-brand-gold text-xl font-bold">W</span>
                    </div>
                    <div>
                        <h1 class="font-serif text-xl tracking-wider text-stone-100 font-medium leading-none">WAE WATU</h1>
                        <p class="text-[9px] tracking-[0.25em] text-brand-gold uppercase mt-0.5">ADMIN CONTROL PANEL</p>
                    </div>
                </a>
            </div>

            <!-- Navigation Links -->
            <nav class="flex items-center space-x-1 md:space-x-4 text-xs uppercase tracking-wider font-medium">
                <a href="{{ url('/admin') }}" class="px-3 py-2 rounded-lg transition-colors {{ Request::is('admin') ? 'bg-brand-gold text-brand-dark font-bold' : 'text-stone-300 hover:text-white hover:bg-white/10' }}">
                    Dashboard
                </a>
                <a href="{{ url('/admin/cms') }}" class="px-3 py-2 rounded-lg transition-colors {{ Request::is('admin/cms*') ? 'bg-brand-gold text-brand-dark font-bold' : 'text-stone-300 hover:text-white hover:bg-white/10' }}">
                    ✏️ CMS Website
                </a>
                <a href="{{ url('/admin/villas') }}" class="px-3 py-2 rounded-lg transition-colors {{ Request::is('admin/villas*') ? 'bg-brand-gold text-brand-dark font-bold' : 'text-stone-300 hover:text-white hover:bg-white/10' }}">
                    🏷️ Kamar & Harga
                </a>
                <a href="{{ url('/admin/bookings') }}" class="px-3 py-2 rounded-lg transition-colors {{ Request::is('admin/bookings*') ? 'bg-brand-gold text-brand-dark font-bold' : 'text-stone-300 hover:text-white hover:bg-white/10' }}">
                    📅 Reservasi
                </a>
                <a href="{{ url('/admin/promotions') }}" class="px-3 py-2 rounded-lg transition-colors {{ Request::is('admin/promotions*') ? 'bg-brand-gold text-brand-dark font-bold' : 'text-stone-300 hover:text-white hover:bg-white/10' }}">
                    🖼️ Banner Iklan
                </a>

                <!-- Logout Button -->
                <form action="{{ url('/admin/logout') }}" method="POST" class="inline-block pl-2">
                    @csrf
                    <button type="submit" class="bg-rose-900/60 hover:bg-rose-800 text-rose-200 border border-rose-700/50 px-3 py-1.5 rounded-lg text-[10px] uppercase font-bold tracking-wider transition-colors">
                        🚪 Logout
                    </button>
                </form>
            </nav>
        </div>
    </header>

    <!-- Main Content Area -->
    <main class="flex-grow max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @if(session('success'))
            <div class="mb-6 bg-emerald-50 border-l-4 border-emerald-500 p-4 rounded-r-xl shadow-sm flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <span class="text-emerald-600 text-lg">✓</span>
                    <p class="text-xs font-semibold text-emerald-800 tracking-wide">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 bg-rose-50 border-l-4 border-rose-500 p-4 rounded-r-xl shadow-sm flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <span class="text-rose-600 text-lg">✕</span>
                    <p class="text-xs font-semibold text-rose-800 tracking-wide">{{ session('error') }}</p>
                </div>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-stone-200 border-t border-stone-300 text-slate-500 text-[11px] py-4 text-center">
        Wae Watu Reef Resort & Sanctuary — Powered by Terra Ecosystem & Laravel PostgreSQL
    </footer>
</body>
</html>
