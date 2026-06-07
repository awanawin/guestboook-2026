<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>V-Guest - SaaS Buku Tamu Digital Meja Resepsionis</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="bg-slate-950 text-slate-100 font-sans min-h-screen flex flex-col justify-between selection:bg-indigo-500 selection:text-white">

    <main class="max-w-4xl mx-auto my-auto p-6 text-center space-y-6">
        <div class="inline-flex items-center gap-2 bg-indigo-950/50 border border-indigo-500/30 text-indigo-400 text-xs font-bold tracking-widest uppercase px-4 py-2 rounded-full shadow-lg shadow-indigo-950/20">
            ✨ Platform Cloud Buku Tamu No.1 di Indonesia
        </div>

        <h1 class="text-4xl md:text-6xl font-black text-white tracking-tight leading-none uppercase">
            Ubah Meja Resepsionis Event Lo Jadi <span class="text-indigo-500 bg-gradient-to-r from-indigo-400 to-cyan-400 bg-clip-text text-transparent">Digital Kiosk</span>
        </h1>

        <p class="text-sm md:text-base text-slate-400 max-w-xl mx-auto leading-relaxed">
            Sistem buku tamu SaaS Multi-Tenant tersentralisasi. Tinggal sediakan satu tablet atau laptop di meja tamu, biarkan pengunjung check-in mandiri, dan kirim ucapan terima kasih via WhatsApp secara otomatis!
        </p>

        <div class="pt-4 flex flex-col sm:flex-row justify-center items-center gap-4">
            @if(auth()->guard('pengguna')->check())
                <a href="{{ route('pengguna.dashboard') }}" class="w-full sm:w-auto bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-sm px-8 py-4 rounded-xl shadow-xl shadow-indigo-600/20 transition-all duration-200">
                    Masuk Ke Dashboard Anda →
                </a>
            @else
                <a href="{{ route('login') }}" class="w-full sm:w-auto bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-sm px-8 py-4 rounded-xl shadow-xl shadow-indigo-600/20 transition-all duration-200">
                    Buat Buku Tamu Event Lo Sekarang
                </a>
            @endif
        </div>
    </main>

    <footer class="text-center py-6 text-xs text-slate-600 border-t border-slate-900 bg-slate-950">
        &copy; 2026 V-Guest SaaS Platform. All Rights Reserved. Built with Filament v3 & Docker.
    </footer>

</body>
</html>
