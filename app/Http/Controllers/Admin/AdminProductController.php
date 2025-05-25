<?php

namespace App\Http\Controllers\Admin;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\ProductReview;
use App\Http\Controllers\Controller;

class AdminProductController extends Controller
{
    public function index(){
        $products = Product::get();
        return view('admin.dashboard.dashboard-parts.admin-products',compact('products'));
    }
}
