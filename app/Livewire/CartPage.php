<?php

namespace App\Livewire;

use App\Helpers\CartManagement;
use App\Livewire\Partials\Navbar;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Keranjang - Tokopaido')]
class CartPage extends Component
{

    public $cartItems = [];
    public $total;

    public function mount()
    {
        $this->cartItems = CartManagement::getCartItemsFromCookie();
        $this->total = CartManagement::calculateCartTotalPrice($this->cartItems);
    }

    public function removeItem($productId)
    {
        $this->cartItems = CartManagement::removeItemFromCart($productId);
        $this->total = CartManagement::calculateCartTotalPrice($this->cartItems);
        $this->dispatch('update-cart-count', total_count: count($this->cartItems))->to(Navbar::class);
    }

    public function increaseQty($productId)
    {
        $this->cartItems = CartManagement::incrementCartItemQuantity($productId);
        $this->total = CartManagement::calculateCartTotalPrice($this->cartItems);
    }

    public function decreaseQty($productId)
    {
        $this->cartItems = CartManagement::decrementCartItemQuantity($productId);
        $this->total = CartManagement::calculateCartTotalPrice($this->cartItems);
    }
    public function render()
    {
        return view('livewire.cart-page');
    }
}
