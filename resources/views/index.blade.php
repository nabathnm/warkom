<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <title>Warkom - Home</title>
</head>
<body class="bg-white text-gray-800 font-sans">
    
    <!-- Header -->
    <header class="bg-white border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20 gap-4">
                
                <!-- Logo -->
                <div class="flex items-center gap-3 flex-shrink-0">
                    <div class="bg-gray-800 text-white p-2 rounded-lg flex items-center justify-center h-10 w-10">
                        <i class="fa-solid fa-arrow-down font-bold text-xl"></i>
                    </div>
                    <span class="font-bold text-2xl tracking-tight text-gray-900">WARKOM</span>
                </div>

                <!-- Search Bar -->
                <div class="flex-1 max-w-2xl px-4 hidden md:block">
                    <form action="{{ route('products.index') }}" method="GET" class="relative w-full">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fa-solid fa-magnifying-glass text-gray-500"></i>
                        </div>
                        <input type="text" name="search" value="{{ request('search') }}" 
                               class="block w-full pl-12 pr-4 py-2.5 bg-gray-100 border-transparent rounded-full text-sm placeholder-gray-500 focus:border-green-500 focus:bg-white focus:ring-2 focus:ring-green-200 transition-all outline-none" 
                               placeholder="Cari produk...">
                    </form>
                </div>

                <!-- Icons & Auth -->
                <div class="flex items-center gap-6">
                    <div class="flex items-center gap-4 text-gray-700 text-xl">
                        @if(auth()->check() && auth()->user()->role === 'user')
                            <a href="{{ route('orders.index') }}" class="hover:text-green-600 transition" title="Riwayat Pesanan">
                                <i class="fa-solid fa-list-ul"></i>
                            </a>
                            <a href="{{ route('cart.index') }}" class="hover:text-green-600 transition" title="Keranjang">
                                <i class="fa-solid fa-cart-shopping"></i>
                            </a>
                        @endif
                        @if(auth()->check() && auth()->user()->role === 'admin')
                            <a href="{{ route('products.create') }}" class="hover:text-green-600 transition" title="Tambah Produk">
                                <i class="fa-solid fa-plus-square"></i>
                            </a>
                        @endif
                    </div>
                    
                    <div class="flex items-center gap-3">
                        @if(auth()->check())
                            <span class="text-sm font-medium text-gray-700 hidden sm:block">{{ auth()->user()->name }}</span>
                            <form action="{{ route('logout') }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="text-sm text-red-600 hover:text-red-800 font-medium ml-2">Logout</button>
                            </form>
                            <i class="fa-solid fa-circle-user text-2xl text-gray-700"></i>
                        @else
                            <a href="{{ route('login') }}" class="text-sm font-medium text-gray-700 hover:text-green-600 transition">Login / Sign up</a>
                            <i class="fa-solid fa-circle-user text-2xl text-gray-700"></i>
                        @endif
                    </div>
                </div>
                
            </div>
        </div>
    </header>

    <!-- Sub Navigation -->
    <nav class="bg-gray-200 border-b border-gray-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex h-10 items-center">
                <a href="{{ route('products.index') }}" class="text-sm font-medium text-gray-800 hover:text-green-700 transition">Home</a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="bg-gray-300 w-full h-[400px] flex flex-col justify-center items-center relative mb-12">
        <h1 class="text-6xl md:text-8xl font-bold tracking-widest text-black mb-8">BEST PRODUK</h1>
        <div class="absolute bottom-6 flex gap-2">
            <div class="w-3 h-3 rounded-full bg-black"></div>
            <div class="w-3 h-3 rounded-full bg-black"></div>
            <div class="w-3 h-3 rounded-full bg-black"></div>
        </div>
    </div>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-16">
        
        @if(session('success'))
            <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 mb-8" role="alert">
                <p>{{ session('success') }}</p>
            </div>
        @endif

        @if(request('search') && $products->isEmpty())
            <div class="bg-yellow-50 text-yellow-700 p-6 rounded border border-yellow-200 text-center mb-8">
                Tidak ada produk yang cocok dengan pencarian "<b>{{ request('search') }}</b>".
            </div>
        @endif

        <!-- Product Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-x-6 gap-y-10">
            @foreach($products as $product)
                <div class="group flex flex-col">
                    <!-- Image Area -->
                    <div class="bg-gray-200 aspect-[4/3] w-full rounded-md flex flex-col items-center justify-center mb-4 relative overflow-hidden">
                        @if($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                        @else
                            <i class="fa-regular fa-image text-gray-400 text-4xl mb-2"></i>
                            <span class="text-gray-400 text-xs tracking-widest uppercase">Image</span>
                        @endif
                        
                        <!-- Stock Badge -->
                        @if(auth()->check() && auth()->user()->role === 'admin')
                            <div class="absolute top-2 right-2 px-2 py-1 text-xs font-bold rounded shadow {{ $product->stock > 0 ? 'bg-green-500 text-white' : 'bg-red-500 text-white' }}">
                                Stok: {{ $product->stock }}
                            </div>
                        @endif
                    </div>

                    <!-- Product Details -->
                    <div class="flex-grow flex flex-col">
                        <h3 class="text-sm font-bold text-gray-900 mb-1 truncate">{{ $product->name }}</h3>
                        <p class="text-sm font-bold text-gray-900 mb-4">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                        
                        <div class="mt-auto space-y-2">
                            <a href="{{ route('products.show', $product->id) }}" class="block w-full text-center py-2 border border-gray-400 text-gray-800 text-xs font-bold hover:bg-green-600 hover:text-white hover:border-green-600 transition-colors uppercase">
                                Lihat Detail
                            </a>
                            
                            @if(auth()->check() && auth()->user()->role === 'admin')
                                <div class="flex gap-2">
                                    <a href="{{ route('products.edit', $product->id) }}" class="flex-1 text-center py-1.5 border border-yellow-500 text-yellow-600 text-xs font-bold hover:bg-yellow-500 hover:text-white transition-colors uppercase">Edit</a>
                                    <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="flex-1">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="w-full text-center py-1.5 border border-red-500 text-red-600 text-xs font-bold hover:bg-red-500 hover:text-white transition-colors uppercase" onclick="return confirm('Hapus produk ini?')">Hapus</button>
                                    </form>
                                </div>
                            @elseif(auth()->check() && auth()->user()->role === 'user')
                                <form action="{{ route('cart.add', $product->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="w-full text-center py-2 bg-green-600 text-white text-xs font-bold hover:bg-green-700 transition-colors uppercase">
                                        + Keranjang
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        
    </main>
</body>
</html>