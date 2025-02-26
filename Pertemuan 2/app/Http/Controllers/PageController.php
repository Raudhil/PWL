<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{
    public function index(){
        return 'Selamat Datang';
    }

    public function about(){
        $name = 'Raul';
        $nim = '2341720164';
        return view('about', compact('name', 'nim'));
    }

    public function articles($id){
        return 'Halaman Artikel '.$id;
    }
}
