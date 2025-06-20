<?php
namespace App\Http\Controllers\Shop\Checkout;

use Carbon\Carbon;
use Stripe\Stripe;
use App\Models\Cart;
use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use App\Models\CartItem;
use App\Models\OrderItem;
use Stripe\PaymentIntent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
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
            return redirect()->route('shop.cart')->withErrors([
                'ErrorMSG' => 'Please login first... before proceed to checkout',
            ]);
        }
        if (!$cart) {
            return redirect()->route('shop.cart')->withErrors([
                'ErrorMSG' => 'Something went wrong!',
            ]);
        }
        return view('shop.checkout.email', compact('cart'));
    }

    public function emailSubmit(Request $request){

        if (!Auth::check()) {
            return redirect()->route('shop.cart')->withErrors([
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

        return redirect()->route('shop.checkout.payment');
    }
    public function payment(){

        if (!Auth::check()) {
            return redirect()->route('shop.cart')->withErrors([
                'ErrorMSG' => 'Please login first... before proceed to checkout',
            ]);
        }
        $purchaser = session()->get('purchaser');
        if (!$purchaser) {
            return redirect()->route('shop.checkout.index')->withErrors([
                'ErrorMSG' => 'Something went wrong. Please fill the form again.',
            ]);
        }
        return view('shop.checkout.payment', compact('purchaser'));
    }

    public function createPaymentIntent(Request $request)
    {
        Stripe::setApiKey(config('services.stripe.secret'));

        $cart = Cart::with('items.product')
            ->where('user_id', Auth::id())
            ->where('status', 'active')
            ->first();
        if(!$cart){
            return response()->json(['result'=>false,'error' => 'Something went wrong! Please try again.']);
        }
        $amount = $cart->items->sum(fn($item) => $item->price_at_add * $item->quantity);

        $intent = PaymentIntent::create([
            'amount' => $amount*100,
            'currency' => 'usd',
            'metadata' => [
                'user_id' => Auth::id(),
                'cart_id' => $cart->id,
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
            ]
        ]);
        return response()->json(['clientSecret' => $intent->client_secret]);
    }

    /**
     * Handles the post-payment actions after a successful Stripe payment.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function processOrderConfirmation(Request $request)
    {
        Stripe::setApiKey(config('services.stripe.secret'));

        // Ensure user is authenticated
        if (!Auth::check()) {
            return redirect()->route('shop.cart')->withErrors([
                'ErrorMSG' => 'Please login to view your order confirmation.',
            ]);
        }

        // Get the payment intent ID from the request
        $paymentIntentId = $request->input('payment_intent_id') ?? $request->query('payment_intent');

        if (!$paymentIntentId) {
            Log::warning('Order Confirmation: Missing payment_intent_id in request.');
            return redirect()->route('shop.cart')->withErrors([
                'ErrorMSG' => 'Payment confirmation failed. Missing payment details.',
            ]);
        }

        try {
            // Retrieve the Payment Intent from Stripe
            $paymentIntent = PaymentIntent::retrieve([
                            'id' => $paymentIntentId,
                            'expand' => ['charges', 'latest_charge']
                        ]);

            // Check if the payment was successful
            if ($paymentIntent->status !== 'succeeded') {
                Log::warning('Order Confirmation: Payment Intent ' . $paymentIntentId . ' status is not succeeded: ' . $paymentIntent->status);
                return redirect()->route('shop.cart')->withErrors([
                    'ErrorMSG' => 'Payment was not successful. Status: ' . $paymentIntent->status,
                ]);
            }

            // Check if an order for this payment intent already exists to prevent duplicates
            $existingOrder = Order::where('payment_intent_id', $paymentIntentId)->first();
            if ($existingOrder) {
                Log::info('Order Confirmation: Order already exists for PaymentIntent ' . $paymentIntentId . '. Displaying existing order.');
                // Display the existing order details
                return view('shop.checkout.thank-you', ['order' => $existingOrder]);
            }

            // If no existing order, proceed to create
            $order = null;

            $userId = $paymentIntent->metadata->user_id;
            $cartId = $paymentIntent->metadata->cart_id;
            $purchaserName = $paymentIntent->metadata->name;
            $purchaserEmail = $paymentIntent->metadata->email;
            $purchaserPhone = $paymentIntent->metadata->phone;
            $totalAmount = $paymentIntent->amount / 100;

            // Find the active cart for the user
            $cart = Cart::with('items.product')
                        ->where('id', $cartId)
                        ->where('user_id', $userId)
                        ->where('status', 'active')
                        ->first();

            if (!$cart) {
                Log::error('Order Confirmation: No active cart found for PaymentIntent ' . $paymentIntentId . '. Cannot create order.');
                throw new \Exception('Cart not found for order creation.');
            }

            // 1. Create the Order
            $order = Order::create([
                'user_id' => $userId,
                'status' => 'pending', // we will keep it as pending - will be changed after we confirm it from admin panel.
                'total_amount' => $totalAmount,
                'payment_method' => $paymentIntent->latest_charge->payment_method_details->type ?? 'Stripe',
                'payment_status' => 'paid',
                'payment_intent_id' => $paymentIntent->id, // Store Stripe Payment Intent ID
                'shipping_address' => json_encode([
                    'name' => $purchaserName,
                    'email' => $purchaserEmail,
                    'phone' => $purchaserPhone,
                    // ToDO - address
                ]),
                'billing_address' => json_encode([
                    'name' => $purchaserName,
                    'email' => $purchaserEmail,
                    'phone' => $purchaserPhone,
                    // ToDO - address
                ]),
            ]);

            // 2. Add Order Items and Reduce Stock
            foreach ($cart->items as $cartItem) {
                // Create OrderItem
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $cartItem->product_id,
                    'quantity' => $cartItem->quantity,
                    'price_at_purchase' => $cartItem->price_at_add,
                ]);

                // Reduce Product Stock/quantity
                $product = Product::find($cartItem->product_id);
                if ($product) {
                    // Ensure stock doesn't go below zero
                    $product->quantity = max(0, $product->quantity - $cartItem->quantity);
                    $product->save();
                } else {
                    Log::warning('Order Confirmation: Product not found for cart item ' . $cartItem->id);
                }
            }

            // 3. Update Cart Status (Mark as completed/purchased)
            $cart->status = 'completed';
            $cart->save();

            // Clear purchaser details from session after successful order
            session()->forget('purchaser');

            Log::info('Order ' . $order->id . ' created and processed successfully for PaymentIntent ' . $paymentIntent->id);

            // After the transaction, display the order
            return view('shop.checkout.thank-you', compact('order'));

        } catch (\Stripe\Exception\ApiErrorException $e) {
            Log::error('Stripe API error during order confirmation: ' . $e->getMessage());
            return redirect()->route('shop.cart')->withErrors([
                'ErrorMSG' => 'Payment verification failed due to a Stripe error. Please contact support.',
            ]);
        } catch (\Exception $e) {
            Log::error('Order creation failed: ' . $e->getMessage());
            return redirect()->route('shop.cart')->withErrors([
                'ErrorMSG' => 'Failed to process your order. Please try again or contact support.',
            ]);
        }
    }

    /**
     * Displays the order confirmation page.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function showOrderConfirmation()
    {
        if (!Auth::check()) {
            return redirect()->route('shop.cart')->withErrors([
                'ErrorMSG' => 'Please login to view your order confirmation.',
            ]);
        }

        // Calculate the timestamp 5 minutes ago
        $fiveMinutesAgo = Carbon::now()->subMinutes(5);

        // Fetch the most recent completed order for the authenticated user
        // and ensure it was created within the last 5 minutes.
        $order = Order::where('user_id', Auth::id())
                      ->where('payment_status', 'paid')
                      ->where('created_at', '>=', $fiveMinutesAgo) // Added time constraint
                      ->orderBy('created_at', 'desc')
                      ->first();

        if (!$order) {
            // If no recent order found, redirect to the home page
            return redirect()->route('home')->withErrors([
                'ErrorMSG' => 'No recent order found or order expired. Please check your order history.',
            ]);
        }
        
        return view('shop.checkout.thank-you', compact('order'));
    }
}