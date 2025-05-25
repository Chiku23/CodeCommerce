<?php

namespace App\Http\Controllers\Admin;

use App\Models\Brand;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdminBrandController extends Controller
{
    public function index(){
        $brands = Brand::get();
        return view('admin.dashboard.dashboard-parts.admin-brands',compact('brands'));
    }
}
