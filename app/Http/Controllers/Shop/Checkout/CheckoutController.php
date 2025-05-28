<?php
namespace App\Http\Controllers\Shop\Checkout;

use Stripe\Stripe;
use App\Models\Cart;
use App\Models\User;
use App\Models\Product;
use App\Models\CartItem;
use Stripe\PaymentIntent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class CheckoutController extends Controller
{

    public function index(){

        if (Auth::check()) {
            // User is logged in, get their active cart
            $cart = Cart::with('items.product')
                        ->where('user_id', Auth::id())
                        ->where('status', 'active')
                        ->first();
        } else{
            return redirect()->Route('shop.cart')->withErrors([
                'ErrorMSG' => 'Please login first... before proceed to checkout',
            ]);
        }

        return view('shop.checkout.email', compact('cart'));
    }

    public function emailSubmit(Request $request){

        if (!Auth::check()) {
            return redirect()->Route('shop.cart')->withErrors([
                'ErrorMSG' => 'Please login first... before proceed to checkout',
            ]);
        } 

        // Validate the request
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'phone' => 'required|string|min:10|max:15',
        ]);

        session()->put('purchaser', [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
        ]);

        return redirect()->Route('shop.checkout.payment');
    }
    public function payment(){

        if (!Auth::check()) {
            return redirect()->Route('shop.cart')->withErrors([
                'ErrorMSG' => 'Please login first... before proceed to checkout',
            ]);
        }

        return view('shop.checkout.payment');
    }

    public function createPaymentIntent(Request $request)
    {
        Stripe::setApiKey(config('services.stripe.secret'));

        $cart = Cart::with('items.product')
            ->where('user_id', Auth::id())
            ->where('status', 'active')
            ->first();

        $amount = $cart->items->sum(fn($item) => $item->price_at_add * $item->quantity);

        $intent = PaymentIntent::create([
            'amount' => $amount*100,
            'currency' => 'inr',
            'metadata' => [
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
            ]
        ]);
        return response()->json(['clientSecret' => $intent->client_secret]);
    }
}