<?php

namespace App\Http\Controllers\Admin;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\ProductReview;
use App\Http\Controllers\Controller;

class AdminOrderController extends Controller
{
    public function index(){

        return view('admin.dashboard.dashboard');
    }
}
