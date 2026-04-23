<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Checkout</title>
</head>
<body class="bg-gray-50 p-6 md:p-10 text-gray-800 min-h-screen">
    <div class="max-w-4xl mx-auto">
        <h1 class="text-3xl font-bold mb-8 text-center text-gray-800">Checkout Pesanan</h1>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Form Alamat Pengiriman -->
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <h2 class="text-xl font-bold mb-4 border-b pb-2">Informasi Pengiriman</h2>
                
                @if ($errors->any())
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                        <ul class="list-disc ml-5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('checkout.process') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label class="block font-medium mb-2 text-gray-700">Alamat Lengkap</label>
                        <textarea name="address" rows="4" class="w-full border border-gray-300 rounded-lg p-3 outline-blue-500 focus:ring-2 focus:ring-blue-200 transition" placeholder="Masukkan alamat pengiriman lengkap..." required>{{ old('address') }}</textarea>
                    </div>

                    <div class="mt-6">
                        <button type="submit" class="w-full bg-green-600 text-white font-bold py-3 px-4 rounded-lg shadow hover:bg-green-700 transition">
                            Konfirmasi Pembayaran
                        </button>
                    </div>
                </form>
            </div>

            <!-- Ringkasan Pesanan -->
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <h2 class="text-xl font-bold mb-4 border-b pb-2">Ringkasan Pesanan</h2>
                
                <div class="space-y-4 mb-6 max-h-64 overflow-y-auto pr-2">
                    @php $grandTotal = 0; @endphp
                    @foreach($carts as $cart)
                        @php 
                            $subtotal = $cart->product->price * $cart->quantity;
                            $grandTotal += $subtotal;
                        @endphp
                        <div class="flex justify-between items-start border-b pb-3">
                            <div>
                                <p class="font-medium text-gray-800">{{ $cart->product->name }}</p>
                                <p class="text-sm text-gray-500">{{ $cart->quantity }} x Rp {{ number_format($cart->product->price, 0, ',', '.') }}</p>
                            </div>
                            <p class="font-semibold text-gray-800">Rp {{ number_format($subtotal, 0, ',', '.') }}</p>
                        </div>
                    @endforeach
                </div>

                <div class="pt-4 border-t-2 border-dashed">
                    <div class="flex justify-between items-center text-lg font-bold">
                        <span>Total Pembayaran</span>
                        <span class="text-blue-600 text-2xl">Rp {{ number_format($grandTotal, 0, ',', '.') }}</span>
                    </div>
                </div>

                <div class="mt-6 text-center">
                    <a href="{{ route('cart.index') }}" class="text-gray-500 hover:text-gray-700 hover:underline transition">Kembali ke Keranjang</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
