<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    //
    public function register(){
        return view("shop.auth.register");
    }
    
    // Handle the registration process
    public function registerUser(Request $request)
    {
        try {
            // Validate the request data
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'phone' => 'required|string|min:10|max:15', // Adjusted validation for phone numbers
                'password' => 'required|string|min:8|confirmed', // Added 'confirmed' rule for password confirmation
            ], [
                'email.unique' => 'This email is already registered. Please use a different one.',
                'password.confirmed' => 'The password confirmation does not match.',
            ]);

            // Create user
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'phone' => $validated['phone'],
                'email_verified_at' => now()
            ]);
            
            // Clear session
            $request->session()->forget(['registration_data']);

            // Log in the user
            Auth::login($user);

            // Put the logged in user details in session
            session()->put('user', [
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone
            ]);

            $request->session()->flash('status', 'Registration Successfull!');
            return redirect('/');
    
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator) // keep original errors
                ->with('error', 'Registration Failed! Please check the form and try again.');
        }
    }
}
