<?php

namespace App\Livewire;

use App\Helpers\CartManagement;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Checkout')]
class CheckoutPage extends Component
{
    public $first_name;
    public $last_name;
    public $phone;
    public $street_address;
    public $city;
    public $state;
    public $zip_code;
    public $payment_method;

    public function placeOrder() {
        $this->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'phone' => 'required',
            'street_address' => 'required',
            'city' => 'required',
            'state' => 'required',
            'zip_code' => 'required',
            'payment_method' => 'required'
        ], [
           'first_name.required' => 'Nama harus diisi',
           'last_name.required' => 'Nama harus diisi',
           'phone.required' => 'Nomor telepon harus diisi',
           'street_address.required' => 'Alamat lengkap harus diisi',
           'city.required' => 'Kota harus diisi',
           'state.required' => 'Provinsi harus diisi',
           'zip_code.required' => 'Kode pos harus diisi',
           'payment_method.required' => 'Metode pembayaran harus dipilih'
        ]);
    }

    public function render()
    {
        $cartItems = CartManagement::getCartItemsFromCookie();
        $total = CartManagement::calculateCartTotalPrice($cartItems);
        return view('livewire.checkout-page', [
            'cartItems' => $cartItems,
            'total' => $total
        ]);
    }
}
