<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <title>Warkom - Home</title>
</head>
<body class="bg-white text-gray-800 font-sans">
    
    <!-- Header -->
    <header class="bg-white border-b border-gray-200 w-full">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex justify-between items-center gap-4">
                
                <!-- Logo -->
                <a href="{{ route('products.index') }}" class="flex items-center gap-3 flex-shrink-0 hover:opacity-80 transition-opacity">
                    <img src="{{ asset('logo.png') }}" alt="Warkom Logo" class="h-8 w-auto">
                    <span class="font-bold text-xl tracking-tight text-gray-900" style="font-family: 'Poppins', sans-serif;">WARKOM</span>
                </a>

                <!-- Search Bar -->
                <div class="flex-1 max-w-xl px-4 hidden md:block mx-auto">
                    <form action="{{ route('products.index') }}" method="GET" class="relative w-full">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fa-solid fa-magnifying-glass text-gray-500 text-xs"></i>
                        </div>
                        <input type="text" name="search" value="{{ request('search') }}" 
                               class="block w-full pl-10 pr-4 py-2 bg-gray-100 border-transparent rounded-full text-xs placeholder-gray-500 focus:border-green-500 focus:bg-white focus:ring-2 focus:ring-green-200 transition-all outline-none" 
                               placeholder="Cari produk...">
                    </form>
                </div>

                <!-- Icons & Auth -->
                <div class="flex items-center gap-6">
                    <div class="flex items-center gap-4 text-black text-sm">
                        @if(auth()->check() && auth()->user()->role === 'user')
                            <a href="{{ route('orders.index') }}" class="hover:text-green-600 transition" title="Riwayat Pesanan">
                                <i class="fa-regular fa-rectangle-list"></i>
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
                    
                    <div class="flex items-center gap-2">
                        @if(auth()->check())
                            <span class="text-xs font-medium text-gray-700 hidden sm:block">{{ auth()->user()->name }}</span>
                            <form action="{{ route('logout') }}" method="GET" class="flex items-center m-0 p-0">
                                @csrf
                                <button type="submit" class="text-xs text-red-600 hover:text-red-800 font-medium ml-2">Logout</button>
                            </form>
                            <i class="fa-solid fa-circle-user text-lg text-black"></i>
                        @else
                            <a href="{{ route('login') }}" class="text-xs font-medium text-black hover:text-gray-600 transition">Login / Sign up</a>
                            <i class="fa-solid fa-circle-user text-lg text-black"></i>
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
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-x-6 gap-y-10">
            @foreach($products as $product)
                <div class="group flex flex-col">
                    <!-- Image Area -->
                    <div class="bg-gray-200 aspect-[4/3] w-full rounded-md flex flex-col items-center justify-center mb-4 relative overflow-hidden">
                        @if($product->images && is_array($product->images) && count($product->images) > 0)
                            <a href="{{ route('products.show', $product->id) }}"><img src="{{ asset('storage/' . $product->images[0]) }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"></a>
                        @elseif($product->image)
                            <a href="{{ route('products.show', $product->id) }}"><img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"></a>
                        @else
                            <i class="fa-regular fa-image text-gray-400 text-3xl mb-2"></i>
                            <span class="text-gray-400 text-[10px] font-mono tracking-widest uppercase">Image</span>
                        @endif
                        
                        <!-- Stock Badge -->
                        @if(auth()->check() && auth()->user()->role === 'admin')
                            <div class="absolute top-2 right-2 px-2 py-1 text-[10px] font-bold rounded shadow {{ $product->stock > 0 ? 'bg-green-500 text-white' : 'bg-red-500 text-white' }}">
                                Stok: {{ $product->stock }}
                            </div>
                        @endif
                    </div>

                    <!-- Product Details -->
                    <div class="flex-grow flex flex-col">
                        <h3 class="text-xs font-mono font-bold text-gray-900 mb-1 truncate">{{ $product->name }}</h3>
                        <p class="text-xs font-mono font-bold text-gray-900 mb-4">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                        
                        <div class="mt-auto space-y-2">
                            <a href="{{ route('products.show', $product->id) }}" class="block w-full text-center py-2 border border-gray-400 text-gray-800 text-[10px] font-mono font-bold hover:bg-green-600 hover:text-white hover:border-green-600 transition-colors uppercase">
                                Lihat Detail
                            </a>
                            
                            @if(auth()->check() && auth()->user()->role === 'admin')
                                <div class="flex gap-2">
                                    <a href="{{ route('products.edit', $product->id) }}" class="flex-1 text-center py-1.5 border border-yellow-500 text-yellow-600 text-[10px] font-mono font-bold hover:bg-yellow-500 hover:text-white transition-colors uppercase">Edit</a>
                                    @php
                                        $imageUrl = '';
                                        if($product->images && is_array($product->images) && count($product->images) > 0) {
                                            $imageUrl = asset('storage/' . $product->images[0]);
                                        } elseif($product->image) {
                                            $imageUrl = asset('storage/' . $product->image);
                                        }
                                    @endphp
                                    <button type="button" 
                                            class="delete-btn flex-1 text-center py-1.5 border border-red-500 text-red-600 text-[10px] font-mono font-bold hover:bg-red-500 hover:text-white transition-colors uppercase" 
                                            data-url="{{ route('products.destroy', $product->id) }}"
                                            data-name="{{ $product->name }}"
                                            data-price="{{ number_format($product->price, 0, ',', '.') }}"
                                            data-image="{{ $imageUrl }}">
                                        Hapus
                                    </button>
                                </div>
                            @elseif(auth()->check() && auth()->user()->role === 'user')
                                <form action="{{ route('cart.add', $product->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="w-full text-center py-2 bg-green-600 text-white text-[10px] font-mono font-bold hover:bg-green-700 transition-colors uppercase">
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

    <!-- Delete Modal Overlay -->
    <div id="deleteModal" class="fixed inset-0 bg-black/40 hidden flex items-center justify-center z-50">
        <!-- Modal Content -->
        <div class="bg-[#f3f4f6] p-8 w-full max-w-md relative shadow-2xl">
            <h2 class="text-xl font-medium mb-8 text-black">Hapus Produk</h2>
            
            <div class="flex items-center justify-between mb-8">
                <div class="flex items-center gap-4">
                    <div id="deleteModalImageContainer" class="w-16 h-16 bg-gray-200 flex items-center justify-center overflow-hidden">
                        <!-- Image injected here -->
                    </div>
                    <span id="deleteModalName" class="font-medium text-sm text-black"></span>
                </div>
                <span id="deleteModalPrice" class="font-medium text-sm text-black"></span>
            </div>
            
            <hr class="border-gray-300 mb-8">
            
            <p class="text-sm font-medium text-black mb-6 text-center sm:text-left">Yakin Ingin Menghapus Produk?</p>
            
            <div class="flex justify-center">
                <form id="deleteForm" method="POST">
                    @csrf @method('DELETE')
                    <button type="submit" class="px-6 py-2 border border-gray-400 bg-white text-black text-sm font-medium hover:bg-gray-100 flex items-center gap-2 transition-colors">
                        Hapus <i class="fa-solid fa-trash"></i>
                    </button>
                </form>
            </div>
            
            <!-- Close Button -->
            <button onclick="closeDeleteModal()" class="absolute top-4 right-4 text-gray-400 hover:text-black transition-colors">
                <i class="fa-solid fa-xmark text-2xl"></i>
            </button>
        </div>
    </div>

    <script>
        document.querySelectorAll('.delete-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const url = this.getAttribute('data-url');
                const name = this.getAttribute('data-name');
                const price = this.getAttribute('data-price');
                const image = this.getAttribute('data-image');
                
                document.getElementById('deleteModal').classList.remove('hidden');
                document.getElementById('deleteModalName').innerText = name;
                document.getElementById('deleteModalPrice').innerText = 'Rp ' + price;
                document.getElementById('deleteForm').action = url;
                
                const imgContainer = document.getElementById('deleteModalImageContainer');
                if (image) {
                    imgContainer.innerHTML = `<img src="${image}" class="w-full h-full object-cover">`;
                } else {
                    imgContainer.innerHTML = `<span class="text-[10px] text-gray-400 font-mono tracking-widest uppercase">Image</span>`;
                }
            });
        });

        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.add('hidden');
        }

        // Close modal if clicked outside
        document.getElementById('deleteModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeDeleteModal();
            }
        });
    </script>
</body>
</html>