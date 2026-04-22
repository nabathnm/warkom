<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Detail Produk - {{ $product->name }}</title>
</head>
<body class="bg-gray-50 text-gray-800 min-h-screen flex items-center justify-center p-6">
    <div class="max-w-2xl w-full bg-white p-8 rounded-xl shadow-sm border border-gray-100">
        <h1 class="text-2xl font-bold mb-6 text-gray-800 border-b pb-4">Detail Produk</h1>

        @if($product->image)
            <div class="mb-6">
                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-64 object-cover rounded-xl shadow-sm border border-gray-100">
            </div>
        @endif

        <div class="space-y-4">
            <div>
                <span class="block text-sm text-gray-500 uppercase font-semibold">Nama Produk</span>
                <p class="text-lg font-medium">{{ $product->name }}</p>
            </div>
            <div>
                <span class="block text-sm text-gray-500 uppercase font-semibold">Harga</span>
                <p class="text-xl text-blue-600 font-bold">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
            </div>
            <div>
                <span class="block text-sm text-gray-500 uppercase font-semibold">Stok Tersedia</span>
                <p class="text-md {{ $product->stock > 0 ? 'text-green-600' : 'text-red-500' }} font-medium">
                    {{ $product->stock }} Item
                </p>
            </div>
            <div>
                <span class="block text-sm text-gray-500 uppercase font-semibold">Deskripsi</span>
                <div class="bg-gray-50 p-4 rounded-lg mt-1 border text-sm text-gray-700 leading-relaxed whitespace-pre-wrap">{{ $product->description }}</div>
            </div>
        </div>

        <div class="mt-8 flex gap-3 pt-6 border-t">
            <a href="{{ route('products.index') }}" class="bg-gray-200 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-300 font-medium transition text-center">Kembali</a>
            
            @if(auth()->check() && auth()->user()->role === 'admin')
                <a href="{{ route('products.edit', $product->id) }}" class="bg-yellow-500 text-white px-6 py-2 rounded-lg hover:bg-yellow-600 font-medium transition text-center">Edit Produk</a>
            @elseif(auth()->check() && auth()->user()->role === 'user')
                <form action="{{ route('cart.add', $product->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 font-medium transition shadow-sm text-center">Masukkan Keranjang</button>
                </form>
            @endif
        </div>
    </div>
</body>
</html>
