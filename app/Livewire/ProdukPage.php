<?php

namespace App\Livewire;

use App\Models\Brand;
use App\Models\Kategori;
use App\Models\Produk;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Produk - Tokopaido')]
class ProdukPage extends Component
{
    use WithPagination;
    public function render()
    {
        $produk = Produk::where('is_active', true)->paginate(5);
        return view('livewire.produk-page', [
            'produk' => $produk,
            'brand' => Brand::where('is_active', true)->orderBy('name', 'asc')->get(['id', 'name', 'slug']),
            'kategori' => Kategori::where('is_active', true)->orderBy('name', 'asc')->get(['id', 'name', 'slug']),
        ]);
    }
}
