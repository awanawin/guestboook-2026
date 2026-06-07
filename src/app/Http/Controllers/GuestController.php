<?php

namespace App\Http\Controllers;

use App\Models\Guest;
use App\Models\Guestbook;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GuestController extends Controller
{
    public function storeKioskGuest(Request $request)
    {
        $penggunaId = Auth::guard('pengguna')->id();
        $guestbook = Guestbook::where('pengguna_id', $penggunaId)->firstOrFail();

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

        Guest::create([
            'pengguna_id' => $penggunaId,
            'guestbook_id' => $guestbook->id,
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'address' => $request->address,
            'notes' => $request->notes,
            'photo' => $photoPath,
        ]);

        $setting = Setting::where('pengguna_id', $penggunaId)->first();
        $pesanHasil = str_replace('{name}', $request->name, $setting->whatsapp_template);

        if ($photoPath) {
            $pesanHasil .= "\n\nFoto Anda: " . asset('storage/' . $photoPath);
        }

        $waUrl = "https://api.whatsapp.com/send?phone=" . urlencode($request->phone) . "&text=" . urlencode($pesanHasil);

        return redirect()->back()->with([
            'success' => 'Check-In berhasil disimpan!',
            'wa_redirect' => $waUrl
        ]);
    }
}
