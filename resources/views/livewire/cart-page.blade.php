<div class="w-full max-w-[85rem] py-10 px-4 sm:px-6 lg:px-8 mx-auto">
    <div class="container mx-auto px-4">
        <h1 class="text-2xl font-semibold mb-4">Shopping Cart</h1>
        <div class="flex flex-col md:flex-row gap-4">
            <div class="md:w-3/4">
                <div class="bg-white overflow-x-auto rounded-lg shadow-md p-6 mb-4">
                    <table class="w-full">
                        <thead>
                            @if ($cartItems)
                            <tr>
                                <th class="text-left font-semibold">Produk</th>
                                <th class="text-left font-semibold">Harga</th>
                                <th class="text-left font-semibold">Jumlah</th>
                                <th class="text-left font-semibold">Total</th>
                                <th class="text-left font-semibold">Hapus</th>
                            </tr>
                            @endif
                        </thead>
                        <tbody>
                            @forelse ($cartItems as $item)
                            <tr wire:key='{{ $item['product_id'] }}'>
                                <td class="py-4">
                                    <div class="flex items-center">
                                        <img class="h-16 w-16 mr-4" src="{{ url('storage', $item['image']) }}" alt="{{ $item['name'] }}">
                                        <span class="font-semibold">{{ $item['name'] }}</span>
                                    </div>
                                </td>
                                <td class="py-4">Rp. {{ number_format($item['unit_amount'], 0, ',', '.') }}</td>
                                <td class="py-4">
                                    <div class="flex items-center">
                                        <button wire:click="decreaseQty({{ $item['product_id'] }})" class="border rounded-md py-2 px-4 mr-2">-</button>
                                        <span class="text-center w-8">{{ $item['quantity'] }}</span>
                                        <button wire:click="increaseQty({{ $item['product_id'] }})" class="border rounded-md py-2 px-4 ml-2">+</button>
                                    </div>
                                </td>
                                <td class="py-4">Rp. {{ number_format($item['total_amount'], 0, ',', '.') }}</td>
                                <td><button wire:click="removeItem({{ $item['product_id'] }})" class="bg-slate-300 border-2 border-slate-400 rounded-lg px-3 py-1 hover:bg-red-500 hover:text-white hover:border-red-700"><span wire:loading.remove wire:target="removeItem({{ $item['product_id'] }})">Hapus</span><span wire:loading wire:target="removeItem({{ $item['product_id'] }})">Menghapus...</span></button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-4xl font-semibold text-slate-500">Keranjang belanja masih kosong</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="md:w-1/4">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-lg font-semibold mb-4">Summary</h2>
                    <div class="flex justify-between mb-2">
                        <span>Subtotal</span>
                        <span>Rp. {{ !empty($cartItems) ? number_format($total, 0, ',', '.') : '0' }}</span>
                    </div>
                    <div class="flex justify-between mb-2">
                        <span>Pajak</span>
                        <span>Rp. {{ !empty($cartItems) ? number_format(2000, 0, ',', '.') : '0' }}</span>
                    </div>
                    <div class="flex justify-between mb-2">
                        <span>Ongkos Kirim</span>
                        <span>Rp. {{ !empty($cartItems) ? number_format(0, 0, ',', '.') : '0' }}</span>
                    </div>
                    <hr class="my-2">
                    <div class="flex justify-between mb-2">
                        <span class="font-semibold">Total</span>
                        <span class="font-semibold">Rp. {{ !empty($cartItems) ? number_format($total + 2000, 0, ',', '.') : '0' }}</span>
                    </div>
                    @if ($cartItems)
                    @auth
                        <a href="/checkout" class="bg-green-500 text-white block text-center py-2 px-4 rounded-lg mt-4 w-full">Checkout</a>
                    @else
                        <a href="/login" class="bg-red-500 text-white block text-center py-2 px-4 rounded-lg mt-4 w-full">Login to Checkout</a>
                    @endauth
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
