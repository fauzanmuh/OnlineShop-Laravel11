<?php

namespace App\Livewire;

use App\Models\Brand;
use App\Models\Kategori;
use App\Models\Produk;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Produk - Tokopaido')]
class ProdukPage extends Component
{
    use WithPagination;

    #[Url]
    public $selected_kategori = [];

    #[Url]
    public $selected_brand = [];

    #[Url]
    public $featured;

    #[Url]
    public $in_stock;

    #[Url]
    public $price_range = 0;
    public function render()
    {
        $produk = Produk::where('is_active', true);
        
        if(!empty($this->selected_kategori)){
            $produk->whereIn('kategori_id', $this->selected_kategori);
        }
        if(!empty($this->selected_brand)){
            $produk->whereIn('brand_id', $this->selected_brand);
        }

        if($this->featured){
            $produk->where('is_featured', true);
        }

        if($this->in_stock){
            $produk->where('in_stock', true);
        }

        if($this->price_range){
            $produk->whereBetween('price', [0, $this->price_range]);
        }

        $produk = $produk->paginate(5);

        return view('livewire.produk-page', [
            'produk' => $produk,
            'brand' => Brand::where('is_active', true)->orderBy('name', 'asc')->get(['id', 'name', 'slug']),
            'kategori' => Kategori::where('is_active', true)->orderBy('name', 'asc')->get(['id', 'name', 'slug']),
        ]);
    }
}
