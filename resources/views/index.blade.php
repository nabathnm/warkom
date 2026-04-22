<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Daftar Produk Warkom</title>
</head>
<body class="bg-gray-50 p-6 md:p-10 text-gray-800">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4 bg-white p-6 rounded-xl shadow-sm">
            <h1 class="text-2xl font-bold border-b-2 border-blue-600 pb-1">Katalog Produk</h1>
            
            <!-- Search & Actions -->
            <div class="flex flex-col sm:flex-row items-center gap-4 w-full md:w-auto">
                <form action="{{ route('products.index') }}" method="GET" class="w-full sm:w-auto relative">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau rincian..." class="w-full sm:w-64 border rounded-lg pl-3 pr-10 py-2 outline-blue-500 bg-gray-50 focus:bg-white transition text-sm">
                    <button type="submit" class="absolute right-2 top-2 text-gray-500 hover:text-blue-600">🔍</button>
                </form>

                <div class="flex gap-2">
                    @if(auth()->check() && auth()->user()->role === 'user')
                        <a href="{{ route('cart.index') }}" class="bg-gray-800 text-white px-4 py-2 rounded-lg hover:bg-gray-900 transition text-sm font-medium shadow-sm flex items-center">🛒 Keranjang</a>
                    @endif
                    @if(auth()->check() && auth()->user()->role === 'admin')
                        <a href="{{ route('products.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition text-sm font-medium whitespace-nowrap shadow-sm">+ Tambah</a>
                    @endif
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="bg-red-50 text-red-600 border border-red-100 px-4 py-2 rounded-lg hover:bg-red-100 transition text-sm font-medium shadow-sm">Logout</button>
                    </form>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6 shadow-sm">{{ session('success') }}</div>
        @endif

        @if(request('search') && $products->isEmpty())
            <div class="bg-yellow-50 text-yellow-700 p-6 rounded-xl border border-yellow-200 text-center shadow-sm">
                Tidak ada produk yang cocok dengan pencarian "<b>{{ request('search') }}</b>".
            </div>
        @endif

        <!-- Card Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach($products as $product)
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden flex flex-col hover:shadow-md transition duration-300">
                    <!-- Product Image -->
                    <div class="h-48 bg-gray-100 w-full relative">
                        @if($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                        @else
                            <div class="flex items-center justify-center w-full h-full text-gray-400 text-sm">Tanpa Gambar</div>
                        @endif
                        
                        <!-- Stock Badge -->
                        <div class="absolute top-2 right-2 px-2 py-1 text-xs font-bold rounded-md shadow-sm {{ $product->stock > 0 ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                            Stok: {{ $product->stock }}
                        </div>
                    </div>

                    <!-- Product Info -->
                    <div class="p-5 flex-grow flex flex-col">
                        <h2 class="text-lg font-bold text-gray-800 line-clamp-1 mb-1">{{ $product->name }}</h2>
                        <p class="text-blue-600 font-black text-xl mb-3">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                        <p class="text-gray-500 text-sm line-clamp-2 flex-grow">{{ $product->description }}</p>
                    </div>

                    <!-- Actions -->
                    <div class="p-4 border-t bg-gray-50 flex justify-between gap-2">
                        <a href="{{ route('products.show', $product->id) }}" class="text-center text-sm font-medium text-blue-600 bg-blue-50 px-4 py-2 rounded border border-blue-100 hover:bg-blue-100 transition truncate">Detail</a>
                        
                        @if(auth()->check() && auth()->user()->role === 'admin')
                            <a href="{{ route('products.edit', $product->id) }}" class="text-center text-sm font-medium text-yellow-600 bg-yellow-50 px-4 py-2 rounded border border-yellow-100 hover:bg-yellow-100 transition truncate">Edit</a>
                            <form action="{{ route('products.destroy', $product->id) }}" method="POST">
                                @csrf @method('DELETE')
                                <button type="submit" class="w-full text-center text-sm font-medium text-red-600 bg-red-50 px-4 py-2 rounded border border-red-100 hover:bg-red-100 transition truncate" onclick="return confirm('Hapus produk ini?')">Hapus</button>
                            </form>
                        @elseif(auth()->check() && auth()->user()->role === 'user')
                            <form action="{{ route('cart.add', $product->id) }}" method="POST" class="flex-grow">
                                @csrf
                                <button type="submit" class="w-full text-center text-sm font-medium text-white bg-green-600 px-4 py-2 rounded hover:bg-green-700 transition shadow-sm truncate">Masukkan Keranjang</button>
                            </form>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</body>
</html>