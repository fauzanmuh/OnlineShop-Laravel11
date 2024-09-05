<?php

namespace App\Livewire;

use App\Models\Produk;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Detail Produk - Tokopaido')]
class DetailProdukPage extends Component
{
    public $slug;

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
