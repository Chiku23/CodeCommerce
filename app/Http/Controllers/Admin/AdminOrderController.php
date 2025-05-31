<?php

namespace App\Http\Controllers\Admin;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdminOrderController extends Controller
{
    public function index(){
        $orders = Order::get();
        return view('admin.dashboard.dashboard-parts.admin-orders',compact('orders'));
    }
}
