<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function foodBeverage() {
        return view('food_beverage');
    }

    public function beautyHealth() {
        return view('beauty_health');
    }

    public function homeCare() {
        return view('home_care');
    }

    public function babyKid() {
        return view('baby_kid');
    }
}
