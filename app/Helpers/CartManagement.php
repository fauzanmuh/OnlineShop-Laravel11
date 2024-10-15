<?php

namespace App\Helpers;

use App\Models\Produk;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Storage;

class CartManagement
{

    // Add Item to cart
    static public function addItemToCart($productId)
    {
        $cartItems = self::getCartItemsFromCookie();

        $existingItem = null;

        foreach ($cartItems as $key => $item) {
            if ($item['product_id'] == $productId) {
                $existingItem = $key;
                break;
            }
        }

        if ($existingItem !== null) {
            $cartItems[$existingItem]['quantity']++;
            $cartItems[$existingItem]['total_amount'] = $cartItems[$existingItem]['quantity'] * $cartItems[$existingItem]['unit_amount'];
        } else {
            $product = Produk::where('id', $productId)->first(['id', 'name', 'price', 'image']);
            if ($product) {
                $cartItems[] = [
                    'product_id' => $productId,
                    'name' => $product->name,
                    'image' => $product->image[0],
                    'quantity' => 1,
                    'unit_amount' => $product->price,
                    'total_amount' => $product->price
                ];
            }
        }
        self::addCartItemsToCookie($cartItems);
        return count($cartItems);
    }

    // Add Item to cart with quantity
    static public function addItemToCartWithQty($productId, $qty = 1)
    {
        $cartItems = self::getCartItemsFromCookie();

        $existingItem = null;

        foreach ($cartItems as $key => $item) {
            if ($item['product_id'] == $productId) {
                $existingItem = $key;
                break;
            }
        }

        if ($existingItem !== null) {
            $cartItems[$existingItem]['quantity'] = $qty;
            $cartItems[$existingItem]['total_amount'] = $cartItems[$existingItem]['quantity'] * $cartItems[$existingItem]['unit_amount'];
        } else {
            $product = Produk::where('id', $productId)->first(['id', 'name', 'price', 'image']);
            if ($product) {
                $cartItems[] = [
                    'product_id' => $productId,
                    'name' => $product->name,
                    'image' => $product->image[0],
                    'quantity' => $qty,
                    'unit_amount' => $product->price,
                    'total_amount' => $product->price
                ];
            }
        }
        self::addCartItemsToCookie($cartItems);
        return count($cartItems);
    }

    // Remove Item from cart
    static public function removeItemFromCart($productId)
    {
        $cartItems = self::getCartItemsFromCookie();

        foreach ($cartItems as $key => $item) {
            if ($item['product_id'] == $productId) {
                unset($cartItems[$key]);
                break;
            }
        }
        self::addCartItemsToCookie($cartItems);
        return $cartItems;
    }

    // Add cart Items to cookie
    static public function addCartItemsToCookie($cartItems)
    {
        Cookie::queue('cartItems', json_encode($cartItems), 60 * 24 * 30);
    }

    // Clear cart Items from cookie
    static public function clearCartItemsFromCookie()
    {
        Cookie::queue(Cookie::forget('cartItems'));
    }

    // Get all cart Items from cookie
    static public function getCartItemsFromCookie()
    {
        $cartItems = json_decode(Cookie::get('cartItems'), true);

        if (!$cartItems) {
            $cartItems = [];
        }
        return $cartItems;
    }

    // increment cart item quantity
    static public function incrementCartItemQuantity($productId)
    {
        $cartItems = self::getCartItemsFromCookie();
        foreach ($cartItems as $key => $item) {
            if ($item['product_id'] == $productId) {
                $cartItems[$key]['quantity']++;
                $cartItems[$key]['total_amount'] = $cartItems[$key]['quantity'] * $cartItems[$key]['unit_amount'];
                break;
            }
        }
        self::addCartItemsToCookie($cartItems);
        return $cartItems;
    }

    // decrement cart item quantity
    static public function decrementCartItemQuantity($productId)
    {
        $cartItems = self::getCartItemsFromCookie();
        foreach ($cartItems as $key => $item) {
            if ($item['product_id'] == $productId) {
                if ($cartItems[$key]['quantity'] > 1) {
                    $cartItems[$key]['quantity']--;
                    $cartItems[$key]['total_amount'] = $cartItems[$key]['quantity'] * $cartItems[$key]['unit_amount'];
                    break;
                }
            }
        }
        self::addCartItemsToCookie($cartItems);
        return $cartItems;
    }

    // Calculate cart total price
    static public function calculateCartTotalPrice($items)
    {
        return array_sum(array_column($items, 'total_amount'));
    }
}