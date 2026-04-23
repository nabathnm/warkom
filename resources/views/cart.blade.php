<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Keranjang Belanja</title>
</head>
<body class="bg-gray-50 p-6 md:p-10 text-gray-800">
    <div class="max-w-5xl mx-auto">
        <!-- Header -->
        <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4 bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <h1 class="text-2xl font-bold border-b-2 border-blue-600 pb-1">🛒 Keranjang Belanja Anda</h1>
            <div class="flex gap-2">
                <a href="{{ route('orders.index') }}" class="bg-gray-100 text-gray-700 border border-gray-200 px-4 py-2 rounded-lg hover:bg-gray-200 font-medium transition shadow-sm flex items-center">📦 Riwayat Pesanan</a>
                <a href="{{ route('products.index') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300 font-medium transition shadow-sm">Lanjut Belanja</a>
            </div>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6 shadow-sm">{{ session('success') }}</div>
        @endif

        @if($carts->isEmpty())
            <div class="bg-white p-12 rounded-xl border border-gray-100 text-center shadow-sm">
                <p class="text-gray-500 text-lg mb-4">Keranjang belanja Anda masih kosong.</p>
                <a href="{{ route('products.index') }}" class="text-blue-600 hover:underline font-medium">Buka Katalog Produk</a>
            </div>
        @else
            <div class="flex flex-col lg:flex-row gap-8">
                <!-- Cart Items -->
                <div class="flex-grow space-y-4">
                    @php $grandTotal = 0; @endphp
                    @foreach($carts as $cart)
                        @php 
                            $subtotal = $cart->product->price * $cart->quantity;
                            $grandTotal += $subtotal;
                        @endphp
                        <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 flex flex-col sm:flex-row items-start sm:items-center gap-4">
                            <!-- Image -->
                            <div class="w-full sm:w-24 h-24 bg-gray-100 rounded-lg overflow-hidden flex-shrink-0">
                                @if($cart->product->image)
                                    <img src="{{ asset('storage/' . $cart->product->image) }}" alt="{{ $cart->product->name }}" class="w-full h-full object-cover">
                                @else
                                    <div class="flex items-center justify-center w-full h-full text-xs text-gray-400">No Image</div>
                                @endif
                            </div>

                            <!-- Details -->
                            <div class="flex-grow">
                                <h3 class="font-bold text-lg leading-tight">{{ $cart->product->name }}</h3>
                                <p class="text-blue-600 font-bold mb-2">Rp {{ number_format($cart->product->price, 0, ',', '.') }}</p>
                                <p class="text-sm text-gray-500">Subtotal: Rp {{ number_format($subtotal, 0, ',', '.') }}</p>
                            </div>

                            <!-- Actions -->
                            <div class="flex flex-row sm:flex-col items-center sm:items-end gap-3 w-full sm:w-auto mt-4 sm:mt-0">
                                <form action="{{ route('cart.update', $cart->id) }}" method="POST" class="flex items-center gap-2">
                                    @csrf @method('PUT')
                                    <input type="number" name="quantity" value="{{ $cart->quantity }}" min="1" class="w-16 border rounded p-1 text-center outline-blue-500">
                                    <button type="submit" class="bg-blue-100 text-blue-700 px-3 py-1 rounded text-sm font-medium hover:bg-blue-200 transition">Update</button>
                                </form>

                                <form action="{{ route('cart.remove', $cart->id) }}" method="POST" class="ml-auto sm:ml-0">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700 text-sm font-medium underline" onclick="return confirm('Hapus dari keranjang?')">Hapus</button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Summary Sidebar -->
                <div class="lg:w-80 flex-shrink-0">
                    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 sticky top-6">
                        <h2 class="text-xl font-bold mb-4 border-b pb-2">Ringkasan Belanja</h2>
                        <div class="flex justify-between items-center mb-6">
                            <span class="text-gray-600">Total Harga</span>
                            <span class="text-2xl font-black text-blue-600">Rp {{ number_format($grandTotal, 0, ',', '.') }}</span>
                        </div>
                        <a href="{{ route('checkout') }}" class="block w-full text-center bg-green-600 text-white font-bold py-3 rounded-lg shadow hover:bg-green-700 transition">Lanjut ke Pembayaran</a>
                    </div>
                </div>
            </div>
        @endif
    </div>
</body>
</html>
