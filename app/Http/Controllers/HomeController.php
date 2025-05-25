<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    // Home URL return the home blade
    public function index(){
        $products = Product::get();

        return view('shop.home', compact('products'));
    }
}
