<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Owner - V-Guest</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="bg-slate-900 text-slate-100 font-sans min-h-screen">

    <nav class="bg-slate-950 border-b border-slate-800 px-6 py-4 flex justify-between items-center">
        <div class="flex items-center gap-3">
            <span class="text-xl font-black text-indigo-500 tracking-wider">V-GUEST</span>
            <span class="text-[10px] font-bold text-slate-400 border border-slate-700 px-2 py-0.5 rounded-md uppercase">Client Area</span>
        </div>
        <div class="flex items-center gap-4">
            <span class="text-xs text-slate-300 font-medium hidden sm:inline">Penyelenggara: <b>{{ auth()->guard('pengguna')->user()->name }}</b></span>
            <form action="{{ route('pengguna.logout') }}" method="POST">
                @csrf
                <button type="submit" class="bg-rose-600/10 hover:bg-rose-600 text-rose-400 hover:text-white text-xs font-bold px-3 py-2 rounded-lg border border-rose-500/20 transition-all cursor-pointer">
                    Keluar Sesi
                </button>
            </form>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto p-6 space-y-6">

        @if(session('success'))
            <div class="bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 p-4 rounded-xl text-xs font-bold">
                ✓ {{ session('success') }}
            </div>
        @endif

        <div class="grid md:grid-cols-3 gap-6">
            <div class="bg-slate-950 p-6 rounded-2xl border border-slate-800 flex flex-col justify-between">
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Pengunjung Terdaftar</p>
                    <h3 class="text-4xl font-black text-white mt-2">{{ $totalTamu }} <span class="text-xs font-normal text-slate-500">Orang</span></h3>
                </div>
                <p class="text-[11px] text-slate-500 mt-4 leading-normal">Jumlah data tamu terisolasi yang berhasil terekam sistem.</p>
            </div>

            <div class="bg-gradient-to-br from-indigo-950 to-slate-950 p-6 rounded-2xl border border-indigo-500/20 md:col-span-2 flex flex-col justify-between">
                <div>
                    <h4 class="text-base font-bold text-white uppercase tracking-wide">Deployment Meja Registrasi (Kiosk Mode)</h4>
                    <p class="text-xs text-slate-400 mt-2 leading-relaxed">Gunakan satu laptop atau tablet tersentralisasi di meja resepsionis gedung acara agar seluruh tamu undangan mengetik data kedatangan mereka secara mandiri.</p>
                </div>
                <div class="mt-4">
                    <a href="{{ route('pengguna.kiosk') }}" target="_blank" class="inline-block bg-indigo-600 hover:bg-indigo-500 text-white font-black text-xs px-5 py-3 rounded-xl shadow-lg shadow-indigo-600/20 uppercase tracking-wider transition-colors">
                        🖥 Buka Layar Resepsionis Kiosk
                    </a>
                </div>
            </div>
        </div>

        <div class="bg-slate-950 p-6 rounded-2xl border border-slate-800">
            <h3 class="text-base font-bold text-white uppercase tracking-wider mb-4">Pengaturan Visual & Notifikasi Gateway</h3>
            <form action="{{ route('pengguna.settings.update') }}" method="POST" class="grid md:grid-cols-3 gap-6">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wide mb-2">Tema Warna Kiosk</label>
                    <select name="theme_id" class="w-full bg-slate-900 border border-slate-800 rounded-xl px-4 py-3 text-xs text-white focus:outline-none focus:border-indigo-500">
                        @foreach($themes as $theme)
                            <option value="{{ $theme->id }}" {{ $guestbookCheck->theme_id == $theme->id ? 'selected' : '' }}>{{ $theme->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wide mb-2">Judul Banner Kiosk</label>
                    <input type="text" name="custom_kiosk_title" value="{{ $setting->custom_kiosk_title }}" required class="w-full bg-slate-900 border border-slate-800 rounded-xl px-4 py-3 text-xs text-white focus:outline-none focus:border-indigo-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wide mb-2">Template Pesan WhatsApp</label>
                    <textarea name="whatsapp_template" rows="3" required class="w-full bg-slate-900 border border-slate-800 rounded-xl px-4 py-2 text-xs text-white focus:outline-none focus:border-indigo-500">{{ $setting->whatsapp_template }}</textarea>
                    <span class="text-[10px] text-slate-500 block mt-1 leading-normal">Gunakan shortcut tag <b>{name}</b> untuk menyisipkan nama tamu otomatis.</span>
                </div>
                <div class="md:col-span-3 text-right">
                    <button type="submit" class="bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs px-5 py-2.5 rounded-xl transition-colors cursor-pointer uppercase tracking-wider">
                        Simpan Setelan Kontrol
                    </button>
                </div>
            </form>
        </div>

        <div class="bg-slate-950 rounded-2xl border border-slate-800 overflow-hidden">
            <div class="p-6 border-b border-slate-800 flex justify-between items-center bg-slate-950">
                <h3 class="text-sm font-bold text-white uppercase tracking-wider">Log Riwayat Kehadiran Tamu</h3>
                
                <a href="{{ route('pengguna.guests.export') }}" class="bg-slate-900 hover:bg-slate-800 text-amber-400 hover:text-amber-300 font-bold text-xs px-4 py-2 rounded-xl border border-amber-500/20 flex items-center gap-2 transition-all tracking-wide uppercase">
                    💾 Unduh Laporan Excel (CSV)
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-900 text-[10px] font-bold text-slate-400 uppercase tracking-wider border-b border-slate-800">
                            <th class="p-4">Nama Tamu</th>
                            <th class="p-4">WhatsApp</th>
                            <th class="p-4">Email</th>
                            <th class="p-4">Alamat Rumah</th>
                            <th class="p-4">Catatan</th>
                            <th class="p-4">Waktu Hadir</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800/40 text-xs text-slate-300">
                        @forelse($daftarTamu as $tamu)
                            <tr class="hover:bg-slate-900/30 transition-colors">
                                <td class="p-4 font-bold text-white">{{ $tamu->name }}</td>
                                <td class="p-4 tracking-wide">{{ $tamu->phone }}</td>
                                <td class="p-4 text-slate-400">{{ $tamu->email ?? '-' }}</td>
                                <td class="p-4 text-slate-400">{{ $tamu->address ?? '-' }}</td>
                                <td class="p-4 italic text-slate-400">{{ $tamu->notes ?? '-' }}</td>
                                <td class="p-4 text-slate-500 font-mono">{{ $tamu->created_at->format('d/m/Y H:i') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="p-8 text-center text-slate-500 italic text-xs">Belum ada aktivitas kunjungan tamu di meja resepsionis.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </main>
</body>
</html>