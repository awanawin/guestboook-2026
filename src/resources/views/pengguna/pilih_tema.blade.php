<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Autentikasi Tenant - V-Guest</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="bg-slate-950 text-slate-100 min-h-screen flex items-center justify-center font-sans p-4">

    <div class="w-full max-w-md bg-slate-900 border border-slate-800 rounded-2xl shadow-2xl p-6 space-y-6 backdrop-blur-md">
        
        <div class="text-center space-y-1">
            <h1 class="text-2xl font-black text-indigo-500 tracking-wider">V-GUEST PLATFORM</h1>
            <p class="text-xs text-slate-400">Silakan masuk atau buat akun baru untuk event Anda</p>
        </div>

        <div class="flex border-b border-slate-800 text-xs font-bold tracking-wider">
            <button id="tab-login" onclick="switchForm('login')" class="w-1/2 pb-3 text-indigo-400 border-b-2 border-indigo-500 cursor-pointer text-center transition-all">MASUK</button>
            <button id="tab-register" onclick="switchForm('register')" class="w-1/2 pb-3 text-slate-400 border-b-2 border-transparent cursor-pointer text-center transition-all">DAFTAR BARU</button>
        </div>

        @if($errors->any())
            <div class="bg-rose-500/10 border border-rose-500/30 text-rose-400 p-3 rounded-xl text-xs font-semibold">
                ⚠️ {{ $errors->first() }}
            </div>
        @endif

        <div id="form-login" class="block">
            <form action="{{ route('login.manual') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wide mb-1">Email Klien</label>
                    <input type="email" name="email" required placeholder="nama@email.com" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-3 text-xs text-white focus:outline-none focus:border-indigo-500 transition-colors">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wide mb-1">Kata Sandi</label>
                    <input type="password" name="password" required placeholder="••••••••" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-3 text-xs text-white focus:outline-none focus:border-indigo-500 transition-colors">
                </div>
                <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-500 text-white font-bold py-3.5 px-4 rounded-xl text-xs shadow-lg transition-colors cursor-pointer">
                    Masuk Ke Ruang Kerja
                </button>
            </form>
        </div>

        <div id="form-register" class="hidden">
            <form action="{{ route('register.manual') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wide mb-1">Nama Lengkap / EO / Perusahaan</label>
                    <input type="text" name="name" required placeholder="John Doe Organizer" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-3 text-xs text-white focus:outline-none focus:border-indigo-500 transition-colors">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wide mb-1">Alamat Email</label>
                    <input type="email" name="email" required placeholder="admin@eo.com" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-3 text-xs text-white focus:outline-none focus:border-indigo-500 transition-colors">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wide mb-1">Sandi Baru</label>
                    <input type="password" name="password" required placeholder="Minimal 6 Karakter" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-3 text-xs text-white focus:outline-none focus:border-indigo-500 transition-colors">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wide mb-1">Konfirmasi Sandi Baru</label>
                    <input type="password" name="password_confirmation" required placeholder="Ulangi sandi" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-3 text-xs text-white focus:outline-none focus:border-indigo-500 transition-colors">
                </div>
                <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-500 text-white font-bold py-3.5 px-4 rounded-xl text-xs shadow-lg transition-colors cursor-pointer">
                    Daftar Akun Baru
                </button>
            </form>
        </div>

        <div class="relative flex py-2 items-center text-slate-700">
            <div class="flex-grow border-t border-slate-800"></div>
            <span class="flex-shrink mx-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Atau Pilihan Lain</span>
            <div class="flex-grow border-t border-slate-800"></div>
        </div>

        <a href="{{ route('google.login') }}" class="w-full bg-white hover:bg-slate-100 text-slate-950 font-bold py-3 px-4 rounded-xl text-xs flex items-center justify-center gap-2 transition-colors shadow-lg cursor-pointer">
            <svg class="w-4 h-4" viewBox="0 0 24 24">
                <path fill="#EA4335" d="M12.24 10.285V14.4h6.887c-.275 1.565-1.88 4.604-6.887 4.604-4.33 0-7.866-3.577-7.866-8s3.536-8 7.866-8c2.46 0 4.105 1.025 5.047 1.926l3.256-3.133C18.332 2.138 15.519 1 12.24 1 6.033 1 12.24s5.033 11.24 11.24 11.24c6.478 0 10.793-4.537 10.793-10.986 0-.743-.08-1.313-.177-1.709H12.24z"/>
            </svg>
            Masuk dengan Akun Google
        </a>

    </div>

    <script>
        function switchForm(type) {
            const loginF = document.getElementById('form-login');
            const registerF = document.getElementById('form-register');
            const tabL = document.getElementById('tab-login');
            const tabR = document.getElementById('tab-register');

            if (type === 'login') {
                loginF.classList.replace('hidden', 'block');
                registerF.classList.replace('block', 'hidden');
                tabL.className = "w-1/2 pb-3 text-indigo-400 border-b-2 border-indigo-500 cursor-pointer text-center transition-all";
                tabR.className = "w-1/2 pb-3 text-slate-400 border-b-2 border-transparent cursor-pointer text-center transition-all";
            } else {
                loginF.classList.replace('block', 'hidden');
                registerF.classList.replace('hidden', 'block');
                tabL.className = "w-1/2 pb-3 text-slate-400 border-b-2 border-transparent cursor-pointer text-center transition-all";
                tabR.className = "w-1/2 pb-3 text-emerald-400 border-b-2 border-emerald-500 cursor-pointer text-center transition-all";
            }
        }
    </script>
</body>
</html>