<?php

namespace App\Livewire;

use App\Helpers\CartManagement;
use App\Livewire\Partials\Navbar;
use App\Models\Produk;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Detail Produk - Tokopaido')]
class DetailProdukPage extends Component
{
    use LivewireAlert;
    public $slug;
    public $quantity = 1;

    public function decreaseQty() {
        if($this->quantity > 1) {
            $this->quantity--;
        }
    }

    public function increaseQty() {
        $this->quantity++;
    }

    // Add cart
    public function addToCart($productId) {
        $total_count = CartManagement::addItemToCartWithQty($productId, $this->quantity);
        $this->dispatch('update-cart-count', total_count: $total_count)->to(Navbar::class);

        $this->alert('success', 'Produk ditambahkan ke keranjang', [
            'position' => 'bottom-end',
            'timer' => 3000,
            'toast' => true,
        ]);
    }

    public function mount($slug) {
        $this->slug = $slug;
    }
    public function render()
    {
        return view('livewire.detail-produk-page', [
            'produk' => Produk::where('slug', $this->slug)->firstOrFail(),
        ]);
    }
}
