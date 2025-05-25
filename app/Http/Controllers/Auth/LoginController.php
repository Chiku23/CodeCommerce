<?php
namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    // Show the login form
    public function login()
    {
        return view('shop.auth.login');
    }

    // Handle login request
    public function loginUser(Request $request)
    {
        // Validate the request
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Create a User object and retrieve the user by email
        $user = new User(); // Create a new User instance
        $user = $user->where('email', $request->email)->first(); // Retrieve the user

        if (!$user) {
            // User not found
            return back()->withErrors([
                'ErrorMSG' => 'User not found.',
            ]);
        }
        if ($user->google_id) {
            // Google User trying to login with password
            return back()->withErrors([
                'ErrorMSG' => 'You are a Google Authenticated user please login with Google.',
            ]);
        }

        // Check if the password is correct
        if (!Hash::check($request->password, $user->password)) {
            // Password is incorrect
            return back()->withErrors([
                'ErrorMSG' => 'Password is incorrect.',
            ]);
        }

        // Log in the user
        Auth::login($user);
        // Put the logged in user details in session
        session()->put('user', [
            'name' => $user->name,
            'email' => $user->email,
        ]);

        // Redirect to Homepage
        return redirect('/');
    }

    // Handle logout request
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->forget('user'); //forget the user data
        $request->session()->flash('status', 'Logged Out Successfully !');
        return redirect('/'); // Redirect to homepage or another appropriate route
    }
}