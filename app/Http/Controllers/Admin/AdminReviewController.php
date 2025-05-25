<?php

namespace App\Http\Controllers\Admin;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\ProductReview;
use App\Http\Controllers\Controller;

class AdminReviewController extends Controller
{
    public function index(){
        $reviews = ProductReview::get();
        return view('admin.dashboard.dashboard-parts.admin-reviews',compact('reviews'));
    }
}
