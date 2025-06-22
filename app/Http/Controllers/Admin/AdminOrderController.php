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

    public function viewOrder(Request $request){
        if(!$request['orderid']){
            return redirect()->route('admin.orders.index');
        }
        $order = new Order;
        $objOrder = $order->where('id', $request['orderid'])->first();
        if(!$objOrder){
            return redirect()->route('admin.orders.index');
        }
        return view('admin.dashboard.dashboard-parts.admin-orderview',compact('objOrder'));
    }
}
