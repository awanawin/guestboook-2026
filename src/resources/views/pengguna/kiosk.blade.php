<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Layar Kiosk - {{ $setting->custom_kiosk_title }}</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <style>
        :root {
            --primary: {{ $guestbook->theme->primary_color ?? '#4f46e5' }};
            --bg-gradient: {{ $guestbook->theme->background_gradient ?? 'linear-gradient(to bottom right, #1e1b4b, #0f172a)' }};
        }
        body { background: var(--bg-gradient); }
        .btn-theme { background-color: var(--primary); }
        .focus-theme:focus { border-color: var(--primary); }
    </style>
</head>
<body class="text-slate-100 min-h-screen flex flex-col justify-between items-center p-4 antialiased selection:bg-white selection:text-slate-950">

    <main class="max-w-xl w-full my-auto space-y-6">
        <div class="text-center space-y-2">
            <span class="text-[10px] font-black uppercase tracking-widest text-indigo-300 bg-black/40 px-4 py-2 rounded-full border border-white/10 shadow-md">
                🖥️ REGISTRASI TAMU MANDIRI (KIOSK MODE)
            </span>
            <h1 class="text-3xl md:text-4xl font-black text-white mt-2 uppercase tracking-tight drop-shadow-xl">
                {{ $setting->custom_kiosk_title }}
            </h1>
        </div>

        <div class="bg-slate-950/80 p-6 md:p-8 rounded-2xl border border-white/10 shadow-2xl backdrop-blur-xl">
            @if(session('success'))
                <div class="bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 p-4 rounded-xl text-xs font-bold text-center mb-4">
                    ✓ {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('pengguna.kiosk.submit') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-1">Nama Lengkap *</label>
                    <input type="text" name="name" required placeholder="Ketik nama lengkap Anda..." class="w-full bg-slate-900/60 border border-slate-800 rounded-xl px-4 py-3 text-xs text-white focus:outline-none focus-theme transition-all">
                </div>
                <div>
                    <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-1">Nomor WhatsApp Aktif *</label>
                    <input type="tel" name="phone" required placeholder="Contoh: 628123xxxxxxx" class="w-full bg-slate-900/60 border border-slate-800 rounded-xl px-4 py-3 text-xs text-white focus:outline-none focus-theme transition-all">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-1">Email</label>
                        <input type="email" name="email" placeholder="nama@email.com" class="w-full bg-slate-900/60 border border-slate-800 rounded-xl px-4 py-3 text-xs text-white focus:outline-none focus-theme transition-all">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-1">Foto Kunjungan (Kamera/File)</label>
                        <input type="file" name="photo" accept="image/*" class="w-full bg-slate-900/60 border border-slate-800 rounded-xl p-2 text-[11px] text-slate-400 file:bg-slate-800 file:text-white file:border-0 file:rounded-lg file:px-2 file:py-1 cursor-pointer">
                    </div>
                </div>
                <div>
                    <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-1">Asal Kota / Instansi</label>
                    <input type="text" name="address" placeholder="Kota asal atau instansi Anda..." class="w-full bg-slate-900/60 border border-slate-800 rounded-xl px-4 py-3 text-xs text-white focus:outline-none focus-theme transition-all">
                </div>
                <div>
                    <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-1">Ucapan & Catatan Pesan</label>
                    <textarea name="notes" rows="2" placeholder="Berikan ucapan atau pesan Anda..." class="w-full bg-slate-900/60 border border-slate-800 rounded-xl px-4 py-2 text-xs text-white focus:outline-none focus-theme transition-all"></textarea>
                </div>
                <button type="submit" class="btn-theme w-full text-white font-black py-3.5 px-4 rounded-xl text-xs uppercase tracking-widest shadow-xl hover:opacity-90 transition-opacity cursor-pointer">
                    Check-In Kehadiran Selesai
                </button>
            </form>
        </div>
    </main>

    <footer class="text-center text-[10px] text-white/40 pt-4">
        Powered by V-Guest Platform &bull; Terisolasi Secure Multi-Tenant
    </footer>

    @if(session('wa_redirect'))
        <script>
            window.open("{{ session('wa_redirect') }}", '_blank');
        </script>
    @endif

</body>
</html>