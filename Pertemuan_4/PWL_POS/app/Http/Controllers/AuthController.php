<?php

namespace App\Http\Controllers;

use App\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\LevelModel;


class AuthController extends Controller
{
    public function login()
    {
        if (Auth::check()) { // jika sudah login, maka redirect ke halaman home
            return redirect('/');
        }
        return view('auth.login');
    }

    public function postlogin(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $credentials = $request->only('username', 'password');
            if (Auth::attempt($credentials)) {
                return response()->json([
                    'status' => true,
                    'message' => 'Login Berhasil',
                    'redirect' => url('/')
                ]);
            }
            return response()->json([
                'status' => false,
                'message' => 'Login Gagal'
            ]);
        }
        return redirect('login');
    }


    public function register()
    {
        $levels = LevelModel::all(); // Ambil semua data level dari database
        return view('auth.register', compact('levels'));
    }


    public function postregister(Request $request)
    {
        return response()->json($request->all());


        // Validasi semua input
        $request->validate([
            'username' => 'required',
            'nama' => 'required',
            'level_id' => 'required',
            'password' => 'required|confirmed',
        ]);

        // Simpan data user
        $user = new UserModel;
        $user->username = $request->username;
        $user->nama = $request->nama;
        $user->password = bcrypt($request->password);
        $user->level_id = $request->level_id; // dari input user, bukan angka 4
        $user->save();

        return redirect('login');
    }
}
