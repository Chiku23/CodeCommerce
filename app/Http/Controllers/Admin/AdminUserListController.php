<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdminUserListController extends Controller
{
    public function index(){
        $users = User::get();
        return view('admin.dashboard.dashboard-parts.admin-userslist',compact('users'));
    }
}
