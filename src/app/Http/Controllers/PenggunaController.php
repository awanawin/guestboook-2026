<?php

namespace App\Http\Controllers;

use App\Models\Pengguna;
use App\Models\Setting;
use App\Models\Guestbook; // Pastikan model Guestbook diimport bro
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Exception;

class PenggunaController extends Controller
{
    public function showAuthForm()
    {
        if (Auth::guard('pengguna')->check()) {
            return redirect()->route('pengguna.dashboard');
        }
        return view('auth.login_register');
    }

    public function registerManual(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:penggunas',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $pengguna = Pengguna::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        Setting::create([
            'pengguna_id' => $pengguna->id,
            'whatsapp_template' => 'Halo {name}, terima kasih banyak telah berkunjung ke acara kami!',
            'custom_kiosk_title' => 'Buku Tamu Digital Resmi'
        ]);

        Auth::guard('pengguna')->login($pengguna);
        $request->session()->regenerate();

        return redirect()->route('pengguna.pilih_tema');
    }

    public function loginManual(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::guard('pengguna')->attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();
            return redirect()->route('pengguna.dashboard');
        }

        return back()->withErrors(['email' => 'Kredensial login tidak cocok dengan data kami.']);
    }

    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            $pengguna = Pengguna::where('google_id', $googleUser->id)->orWhere('email', $googleUser->email)->first();

            $isNewUser = false;

            if (!$pengguna) {
                $isNewUser = true;
                $pengguna = Pengguna::create([
                    'name' => $googleUser->name,
                    'email' => $googleUser->email,
                    'google_id' => $googleUser->id,
                    'avatar' => $googleUser->avatar,
                    'password' => Hash::make(Str::random(16)), // Kasih password random aman untuk DB strict
                ]);

                Setting::create([
                    'pengguna_id' => $pengguna->id,
                    'whatsapp_template' => 'Halo {name}, terima kasih banyak telah berkunjung ke acara kami!',
                    'custom_kiosk_title' => 'Buku Tamu Digital Resmi'
                ]);
            } else {
                if (empty($pengguna->google_id)) {
                    $pengguna->update([
                        'google_id' => $googleUser->id,
                        'avatar' => $googleUser->avatar,
                    ]);
                }

                // Cek apakah user lama ini ternyata belum punya config tema sama sekali
                $hasGuestbook = Guestbook::where('pengguna_id', $pengguna->id)->exists();
                if (!$hasGuestbook) {
                    $isNewUser = true;
                }
            }

            // Lock guard login tenant & amankan session state
            Auth::guard('pengguna')->login($pengguna, true);
            request()->session()->regenerate();

            // 🌟 FIX LOGIC: Kalau user baru / belum set tema, wajib lempar ke /pilih-tema dulu biar ga kena mental redirect!
            if ($isNewUser) {
                return redirect()->route('pengguna.pilih_tema')->with('success', 'Registrasi Google Berhasil! Silakan pilih tema buku tamu Anda.');
            }

            return redirect()->route('pengguna.dashboard');

        } catch (Exception $e) {
            return redirect()->route('login')->with('error', 'Gagal login via Google.');
        }
    }

    public function logout(Request $request)
    {
        Auth::guard('pengguna')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}