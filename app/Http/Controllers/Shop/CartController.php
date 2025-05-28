<?php
namespace App\Http\Controllers\Shop;

use App\Models\Cart;
use App\Models\Product;
use App\Models\CartItem;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CartController extends Controller
{
public function index(){
        $cart = null;

        if (Auth::check()) {
            // User is logged in, get their active cart
            $cart = Cart::with('items.product')
                        ->where('user_id', Auth::id())
                        ->where('status', 'active')
                        ->first();
        } else {
            // User is a guest, try to get their cart based on session ID
            $sessionId = session()->get('cart_guest_session_id');
            if ($sessionId) {
                $cart = Cart::with('items.product')
                            ->where('session_id', $sessionId)
                            ->whereNull('user_id')
                            ->where('status', 'active')
                            ->first();
            }
        }

        // If no cart is found
        // For simplicity, we'll ensure $cart is an empty collection if no cart exists to avoid errors in the view.
        if (!$cart) {
            $cart = [];
        }
        // Forget the session if present - after coming to cart page from unsuccessful checkout.
        session()->forget('purchaser');

        return view('shop.cart.cart', compact('cart'));
    }

    /**
     * Add a product to the user's active cart or create a new cart item.
     */
    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'sometimes|required|integer|min:1'
        ]);

        $productId = $request->input('product_id');
        $quantity = $request->input('quantity', 1);
        $product = Product::findOrFail($productId);

        $cart = null;

        DB::beginTransaction();

        try {
            if (Auth::check()) {
                $user = Auth::user();

                // Find user's active cart, or create one if it doesn't exist.
                $cart = Cart::firstOrCreate(
                    [
                        'user_id' => $user->id,
                        'status' => 'active'
                    ],
                    [
                        // 'session_id' => null, // Default will be null if not specified
                    ]
                );

                // Handle merging a guest cart if user just logged in
                $guestSessionIdForPotentialMerge = session()->get('cart_guest_session_id');
                
                if ($guestSessionIdForPotentialMerge) {
                    $guestCart = Cart::where('session_id', $guestSessionIdForPotentialMerge)
                                     ->whereNull('user_id')
                                     ->where('status', 'active')
                                     ->first();
                    if ($guestCart) {
                        // Merge items from guestCart to user's $cart
                        foreach ($guestCart->items as $guestItem) {
                            $existingItem = $cart->items()->where('product_id', $guestItem->product_id)->first();
                            if ($existingItem) {
                                $existingItem->quantity += $guestItem->quantity;
                                $existingItem->save();
                            } else {
                                $cart->items()->create([
                                    'product_id' => $guestItem->product_id,
                                    'quantity' => $guestItem->quantity,
                                    'price_at_add' => $guestItem->price_at_add,
                                ]);
                            }
                        }
                        $guestCart->delete();
                        session()->forget('cart_guest_session_id');
                        $cart->load('items');
                    }
                }

            } else {
                // --- User is a Guest ---
                $sessionId = session()->get('cart_guest_session_id');
                if (!$sessionId) {
                    $sessionId = session()->getId(); // Use Laravel's session ID
                    // $sessionId = Str::uuid()->toString(); // Alternative for a unique ID
                    session()->put('cart_guest_session_id', $sessionId);
                }

                $cart = Cart::firstOrCreate(
                    [
                        'session_id' => $sessionId,
                        'status' => 'active',
                        'user_id' => null // Explicitly ensure user_id is null for guest cart query
                    ],
                    [] // No other defaults needed if `user_id` is handled by the query conditions
                );
            }

            // --- Add or Update Cart Item in the determined Cart ---
            $cartItem = $cart->items()->where('product_id', $product->id)->first();

            if ($cartItem) {
                $cartItem->quantity += $quantity;
                // Optionally re-evaluate price if requires:
                // $cartItem->price = $product->price;
                $cartItem->save();
            } else {
                $cartItem = $cart->items()->create([
                    'product_id' => $product->id,
                    'quantity'   => $quantity,
                    'price_at_add' => $product->price,
                ]);
            }

            DB::commit(); // Commit

            $totalItemsInCart = $cart->items()->sum('quantity');

            return response()->json([
                'success' => true,
                'message' => $product->name . ' (' . $quantity . ') added to cart!',
                'cartItemCount' => $totalItemsInCart,
                'cartItem' => $cartItem->load('product:id,name,slug')
            ]);

        } catch (\Exception $e) {
            DB::rollBack(); // Rollback
            return response()->json([
                'success' => false,
                'message' => 'Could not add product to cart. ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Remove a product from the user's active cart.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function remove(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:cart_items,id',
            'cart_id' => 'required|exists:carts,id'
        ]);

        $itemId = $request->input('item_id');
        $cartId = $request->input('cart_id');

        try {
            $cart = null;

            if (Auth::check()) {
                // User is logged in, find their active cart by ID and user_id
                $cart = Cart::where('id', $cartId)
                            ->where('user_id', Auth::id())
                            ->where('status', 'active')
                            ->first();
            } else {
                // User is a guest, find their active cart by ID and session_id
                $sessionId = session()->get('cart_guest_session_id');
                if ($sessionId) {
                    $cart = Cart::where('id', $cartId)
                                ->where('session_id', $sessionId)
                                ->whereNull('user_id')
                                ->where('status', 'active')
                                ->first();
                }
            }

            if (!$cart) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cart not found or does not belong to the current user/session.'
                ], 404);
            }

            // Find the specific cart item to remove
            $cartItem = CartItem::find($itemId);

            if (!$cartItem) {
                return response()->json([
                    'success' => false,
                    'message' => 'Product not found in this cart.'
                ], 404);
            }

            // Delete the cart item
            $cartItem->delete();

            // Recalculate total items in cart
            $totalItemsInCart = $cart->items()->sum('quantity');

            return response()->json([
                'success' => true,
                'message' => 'Product removed from cart successfully!',
                'cartItemCount' => $totalItemsInCart
            ]);

        } catch (\Exception $e) {
            Log::error("Error removing from cart: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            return response()->json([
                'success' => false,
                'message' => 'Could not remove product from cart. ' . $e->getMessage()
            ], 500);
        }
    }
}