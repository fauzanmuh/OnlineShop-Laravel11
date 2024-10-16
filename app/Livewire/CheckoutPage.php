<?php

namespace App\Livewire;

use App\Helpers\CartManagement;
use App\Models\Alamat;
use App\Models\Order;
use Illuminate\Support\Facades\Session;
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

    public function mount()
    {
        $cartItems = CartManagement::getCartItemsFromCookie();
        if(count($cartItems) == 0){
            return redirect('/produk')->with('message', 'Keranjang belanja masih kosong');
        }
    }
    public function placeOrder()
    {
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

        $cartItems = CartManagement::getCartItemsFromCookie();

        $lineItems = [];

        foreach ($cartItems as $item) {
            $lineItems[] = [
                'price_data' => [
                    'currency' => 'IDR',
                    'unit_amount' => $item['unit_amount'] * 100,
                    'product_data' => [
                        'name' => $item['name'],
                    ]
                ],
                'quantity' => $item['quantity'],
            ];
        }

        $order = new Order();
        $order->user_id = auth()->user()->id;
        $order->total = CartManagement::calculateCartTotalPrice($cartItems);
        $order->payment_method = $this->payment_method;
        $order->payment_status = 'Pending';
        $order->status = 'new';
        $order->currency = 'IDR';
        $order->shipping_amount = 0;
        $order->shipping_method = 'JNT';
        $order->notes = 'Order from ' . auth()->user()->name;

        $address = new Alamat();
        $address->first_name = $this->first_name;
        $address->last_name = $this->last_name;
        $address->phone = $this->phone;
        $address->street_address = $this->street_address;
        $address->city = $this->city;
        $address->state = $this->state;
        $address->zip_code = $this->zip_code;

        $redirect_url = '';

        if ($this->payment_method == 'midtrans') {
            \Midtrans\Config::$serverKey = config('midtrans.server_key');
            // Set to Development/Sandbox Environment (default). Set to true for Production Environment (accept real transaction).
            \Midtrans\Config::$isProduction = false;
            // Set sanitization on (default)
            \Midtrans\Config::$isSanitized = true;
            // Set 3DS transaction for credit card to true
            \Midtrans\Config::$is3ds = true;
            $params = array(
                [
                    'transaction_details' => array(
                        'order_id' => $order->id,
                        'gross_amount' => $order->total,
                    ),
                    'customer_details' => array(
                        'customer_email' => auth()->user()->email,
                        'line_items' => $lineItems,
                        'mode' => 'payment',
                        'success_url' => route('success') . '?session_id={CHECKOUT_SESSION_ID}',
                        'cancel_url' => route('cancel'),
                    )
                ]
            );

            $redirect_url = $params;
        } else {
            $redirect_url = route('success');
        }

        $order->save();
        $address->order_id = $order->id;
        $address->save();
        foreach ($cartItems as $item) {
            $order->items()->create([
                'produk_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'unit_amount' => $item['unit_amount'],
                'total_amount' => $item['total_amount'],
            ]);
        }
        CartManagement::clearCartItemsFromCookie();
        return redirect($redirect_url);
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
