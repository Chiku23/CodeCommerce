<?php

use App\Models\User;
use App\Models\Brand;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductImage;
use App\Models\ProductReview;
use App\Models\ProductAttribute;
use Illuminate\Support\Facades\Auth;
/*
 *****************************************************************************
 * Define All the functions that are supposed to be used repeatedly and widely
 *****************************************************************************
*/

/**
 * Get the user's name based on their ID.
 *
 * @param int $userID The ID of the user.
 * @return string The name of the user or an empty string if not found.
 */
function getUserName($userID)
{
    $users = new User;
    $user = $users->where('id', $userID)->first();
    if (empty($user)) {
        return '';
    }
    return $user['name'];
}

/**
 * Get the product's name based on its ID.
 *
 * @param int $productID The ID of the product.
 * @return string The name of the product or an empty string if not found.
 */
function getProductName($productID)
{
    $products = new Product;
    $product = $products->where('id', $productID)->first();
    if (empty($product)) {
        return '';
    }
    return $product['name'];
}

/**
 * Get the brand's name based on its ID.
 *
 * @param int $brandID The ID of the brand.
 * @return string The name of the brand or an empty string if not found.
 */
function getBrandName($brandID)
{
    $brands = new Brand;
    $brand = $brands->where('id', $brandID)->first();
    if (empty($brand)) {
        return '';
    }
    return $brand['name'];
}

/**
 * Get the total number of entries in the database
 *
 * @return array<string, int|null> 
 * An associative array with the model names as keys and their counts as values.
 * Keys with `null` indicate that no count is calculated for that section.
 */
function getAdminCounts()
{
    return [
        'Products' => Product::count(),
        'Categories' => null,
        'Brands' => Brand::count(),
        'Orders' => Order::count(),
        'Users' => User::count(),
        'Settings' => null,
        'Reviews' => ProductReview::count(),
    ];
}

/**
 * Get all product attributes as key-value pairs for a given product ID,
 * sorted alphabetically by the attribute key.
 *
 * @param int $productID
 * @return array
 */
function getProductAttributes($productID)
{
    return ProductAttribute::where('product_id', $productID)
        ->orderBy('key', 'asc')
        ->pluck('value', 'key')
        ->toArray();
}

/**
 * Get all product reviews for a given product ID,
 *
 * @param int $productID
 * @return array
 */
function getProductReviews($productID)
{
    return ProductReview::where('product_id', $productID)
        ->orderBy('created_at', 'desc')
        ->get()
        ->toArray();
}

/**
 * Get the products main image for a given product ID,
 *
 * @param int $productID
 * @return array
 */
function getProductMainImage($productID)
{
    return ProductImage::where('product_id', $productID)
        ->where('is_main', true)
        ->first();
}

/**
 * Get the products main image for a given product ID,
 *
 * @param int $productID
 * @return array
 */
function getProductCategory($categoryID)
{
    return Category::where('id', $categoryID)
        ->first();
}

/**
 * Get the current Cart Items Count
 *
 * @return int
 */
function getCartCount()
{
    $cart = null;

    if (Auth::check()) {
        // User is logged in, get their active cart
        $cart = Cart::where('user_id', Auth::id())
                    ->where('status', 'active')
                    ->first();
    } else {
        // User is a guest, try to get their cart based on session ID
        $sessionId = session()->get('cart_guest_session_id');
        if ($sessionId) {
            $cart = Cart::where('session_id', $sessionId)
                        ->whereNull('user_id') // Ensure it's a guest cart
                        ->where('status', 'active')
                        ->first();
        }
    }

    // If a cart is found, sum the quantities of its items; otherwise, return 0.
    return $cart ? $cart->items()->sum('quantity') : 0;
}

/**
 * Get the rupess sign tag
 *
 * @return string
 */
function dollarSign(){
    return '<i class="fa-solid fa-dollar-sign"></i>';
}

/**
 * Check the user details if an admin or not
 *
 * @return boolean
 */
function isAdminUser()
{
    if(Auth::user()){
        return Auth::user()->is_admin;
    }
    return false;
}