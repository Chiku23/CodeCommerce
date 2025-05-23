<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    // Home URL return the home blade
    public function index(){
        return view('home');
    }
}
