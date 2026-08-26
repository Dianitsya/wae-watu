<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login — Wae Watu Reef Resort</title>
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
                        }
                    }
                }
            }
        }
    </script>
    <link
        href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;1,400&family=Inter:wght@300;400;500;600&display=swap"
        rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        .font-serif {
            font-family: 'Cormorant Garamond', serif;
        }
    </style>
</head>

<body class="bg-brand-dark text-white min-h-screen flex items-center justify-center p-4">

    <div
        class="max-w-md w-full bg-stone-900/90 border border-brand-gold/30 rounded-3xl p-8 sm:p-10 shadow-2xl backdrop-blur-md space-y-8">

        <!-- Header Logo -->
        <div class="text-center space-y-2">
            <div
                class="w-16 h-16 rounded-full border border-brand-gold/50 mx-auto flex items-center justify-center bg-brand-dark shadow-inner">
                <span class="font-serif italic text-brand-gold text-3xl font-bold">W</span>
            </div>
            <h1 class="font-serif text-2xl tracking-widest text-stone-100 uppercase">WAE WATU</h1>
            <p class="text-[10px] tracking-[0.3em] text-brand-gold uppercase font-semibold">ADMINISTRATOR CONTROL PANEL
            </p>
        </div>

        @if (session('error'))
            <div class="bg-rose-900/60 border border-rose-500/50 text-rose-200 text-xs p-4 rounded-xl text-center">
                {{ session('error') }}
            </div>
        @endif

        @if (session('success'))
            <div
                class="bg-emerald-900/60 border border-emerald-500/50 text-emerald-200 text-xs p-4 rounded-xl text-center">
                {{ session('success') }}
            </div>
        @endif

        <!-- Login Form -->
        <form action="{{ url('/admin/login') }}" method="POST" class="space-y-5">
            @csrf

            <div>
                <label class="block text-[11px] font-semibold text-stone-400 uppercase tracking-wider mb-1.5">Email
                    Admin</label>
                <input type="email" name="email" value="{{ old('email', 'admin@waewatu.com') }}" required autofocus
                    class="w-full bg-stone-800 border border-stone-700 rounded-xl px-4 py-3 text-xs text-stone-100 focus:outline-none focus:border-brand-gold focus:ring-1 focus:ring-brand-gold transition-colors">
            </div>

            <div>
                <label class="block text-[11px] font-semibold text-stone-400 uppercase tracking-wider mb-1.5">Kata Sandi
                    (Password)</label>
                <input type="password" name="password" value="adminpassword123" required
                    class="w-full bg-stone-800 border border-stone-700 rounded-xl px-4 py-3 text-xs text-stone-100 focus:outline-none focus:border-brand-gold focus:ring-1 focus:ring-brand-gold transition-colors">
            </div>

            <button type="submit"
                class="w-full bg-brand-gold hover:bg-amber-500 text-brand-dark font-bold py-3.5 rounded-xl text-xs uppercase tracking-widest transition-all shadow-lg">
                🔒 LOGIN SEBAGAI ADMIN
            </button>
        </form>

    </div>

</body>

</html>
