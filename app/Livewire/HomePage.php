<?php

namespace App\Livewire;

use App\Models\Brand;
use App\Models\Kategori;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Home Page - Tokopaido')]
class HomePage extends Component
{
    public function render()
    {
        $brand = Brand::where('is_active', true)->get();
        $kategori = Kategori::where('is_active', true)->get();
        return view('livewire.home-page', [
            'brand' => $brand,
            'kategori' => $kategori
        ]);
    }
}
