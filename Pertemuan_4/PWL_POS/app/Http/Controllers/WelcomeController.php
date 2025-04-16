<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WelcomeController extends Controller
{
    public function index()
    {
        $breadcrumb = (object)[
            'title' => 'Selamat Datang',
            'list' => ['Home', 'Welcome']
        ];

        $activeMenu = 'dashboard';

        // Ambil gambar profil dari session jika ada
        $profilePicture = session('profile_picture', 'default.jpg'); // default image jika belum ada

        return view('welcome', [
            'breadcrumb' => $breadcrumb,
            'activeMenu' => $activeMenu,
            'profilePicture' => $profilePicture
        ]);
    }

    public function uploadProfile(Request $request)
    {
        $request->validate([
            'profilePicture' => 'required|image|mimes:jpg,jpeg,png,gif|max:2048'
        ]);

        if ($request->hasFile('profilePicture')) {
            $file = $request->file('profilePicture');

            // Generate nama file unik
            $filename = 'profile_' . time() . '.' . $file->getClientOriginalExtension();

            // Simpan file ke folder public/profile-picture (bukan storage)
            $file->move(public_path('profile-picture'), $filename);

            // Simpan nama file ke session
            session(['profile_picture' => $filename]);
        }

        return back()->with('success', 'Foto profil berhasil diganti!');
    }
}
