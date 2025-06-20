<?php

namespace App\Http\Controllers\Shop;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    public function index(){
        $user = Auth::user();
        if(!$user){
            return redirect()->route('home');
        }
        return view('shop.user.user-profile', compact('user'));
    }
}
