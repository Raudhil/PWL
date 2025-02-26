<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AboutController extends Controller
{
    public function about(){
        $name = 'Raul';
        $nim = '2341720164';
        return view('about', compact('name', 'nim'));
    }
}
