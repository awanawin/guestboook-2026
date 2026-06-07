<?php

namespace App\Http\Controllers;

use App\Models\Guest;
use App\Models\Setting;
use App\Models\Theme;
use App\Models\Guestbook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GuestbookController extends Controller
{
    private function getPenggunaId()
    {
        return Auth::guard('pengguna')->id();
    }

    public function showThemePicker()
    {
        $themes = Theme::all();
        return view('pengguna.pilih_tema', compact('themes'));
    }

    public function saveInitialTheme(Request $request)
    {
        $request->validate(['theme_id' => 'required|exists:themes,id']);
        $penggunaId = $this->getPenggunaId();

        Guestbook::updateOrCreate(
            ['pengguna_id' => $penggunaId],
            [
                'name' => 'Buku Tamu Utama ' . Auth::guard('pengguna')->user()->name,
                'slug' => 'event-' . $penggunaId,
                'theme_id' => $request->theme_id,
                'is_active' => true
            ]
        );

        return redirect()->route('pengguna.dashboard')->with('success', 'Akun Anda siap digunakan!');
    }

    public function index()
    {
        $penggunaId = $this->getPenggunaId();

        $guestbookCheck = Guestbook::where('pengguna_id', $penggunaId)->first();
        if (!$guestbookCheck || !$guestbookCheck->theme_id) {
            return redirect()->route('pengguna.pilih_tema');
        }

        $setting = Setting::firstOrCreate(
            ['pengguna_id' => $penggunaId],
            [
                'whatsapp_template' => 'Halo {name}, terima kasih telah menghadiri acara kami!',
                'custom_kiosk_title' => 'Buku Tamu Digital Resmi'
            ]
        );

        $totalTamu = Guest::where('pengguna_id', $penggunaId)->count();
        $daftarTamu = Guest::where('pengguna_id', $penggunaId)->latest()->get();
        $themes = Theme::all();

        return view('pengguna.dashboard', compact('setting', 'totalTamu', 'daftarTamu', 'themes', 'guestbookCheck'));
    }

    public function updateSettings(Request $request)
    {
        $penggunaId = $this->getPenggunaId();

        $request->validate([
            'custom_kiosk_title' => 'required|string|max:255',
            'whatsapp_template' => 'required|string',
            'theme_id' => 'nullable|exists:themes,id'
        ]);

        $setting = Setting::where('pengguna_id', $penggunaId)->first();
        $setting->update([
            'custom_kiosk_title' => $request->custom_kiosk_title,
            'whatsapp_template' => $request->whatsapp_template,
        ]);

        Guestbook::updateOrCreate(
            ['pengguna_id' => $penggunaId],
            [
                'name' => $request->custom_kiosk_title,
                'theme_id' => $request->theme_id,
            ]
        );

        return redirect()->back()->with('success', 'Pengaturan berhasil diperbarui!');
    }

    // 🌐 MAMPANGIN LAYAR KIOSK UNTUK PUBLIK
    public function showKiosk($id)
    {
        $setting = Setting::where('pengguna_id', $id)->firstOrFail();
        $guestbook = Guestbook::with('theme')->where('pengguna_id', $id)->firstOrFail();

        return view('pengguna.kiosk', compact('setting', 'guestbook'));
    }

    // 📥 TANGKAP INPUT DATA TAMU (MASUK KE DATABASE DAN STRUKTUR TENANT)
    public function submitKiosk(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
            'notes' => 'nullable|string',
            'photo' => 'nullable|image|max:2048'
        ]);

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('guest_photos', 'public');
        }

        // Proses Penyimpanan data tamu terikat dengan pengguna_id sang Pemilik Acara
        Guest::create([
            'pengguna_id' => $id,
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'address' => $request->address,
            'notes' => $request->notes,
            'photo' => $photoPath,
        ]);

        $setting = Setting::where('pengguna_id', $id)->first();
        $templatePesan = $setting->whatsapp_template ?? 'Halo {name}, terima kasih!';
        $pesanFinal = str_replace('{name}', $request->name, $templatePesan);
        $waUrl = "https://api.whatsapp.com/send?phone=" . $request->phone . "&text=" . urlencode($pesanFinal);

        return redirect()->back()
            ->with('success', 'Check-In Berhasil! Data Anda sudah terekam di sistem kami.')
            ->with('wa_redirect', $waUrl);
    }

    public function exportGuests()
    {
        $penggunaId = $this->getPenggunaId();
        $fileName = 'Daftar-Tamu-' . date('Y-m-d') . '.csv';
        $daftarTamu = Guest::where('pengguna_id', $penggunaId)->get();

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['Nama Tamu', 'WhatsApp', 'Email', 'Alamat', 'Catatan', 'Waktu Hadir'];

        $callback = function() use($daftarTamu, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($daftarTamu as $tamu) {
                fputcsv($file, [
                    $tamu->name,
                    $tamu->phone,
                    $tamu->email,
                    $tamu->address,
                    $tamu->notes,
                    $tamu->created_at->format('Y-m-d H:i:s'),
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
