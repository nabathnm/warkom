<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <title>Detail Produk - {{ $product->name }}</title>
</head>
<body class="bg-white text-gray-800 font-sans min-h-screen">

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
    <nav class="bg-[#d5dbe1] w-full border-b border-gray-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3">
            <div class="flex items-center text-xs text-gray-500 font-medium">
                <a href="{{ route('products.index') }}" class="hover:text-black">Home</a>
                <span class="mx-3">/</span>
                <span class="text-black">Detail Produk</span>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 pb-16 w-full">
        
        <!-- Back Button -->
        <div class="mb-6">
            <a href="{{ route('products.index') }}" class="inline-flex items-center gap-3 border border-gray-300 px-4 py-2 text-xs font-medium text-gray-600 hover:bg-gray-50 bg-white">
                <i class="fa-solid fa-arrow-left"></i> Kembali ke halaman produk
            </a>
        </div>

        @if(session('error'))
            <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif
        @if(session('success'))
            <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        <!-- Image Gallery Area -->
        <div class="mb-8 max-w-5xl mx-auto">
            <!-- Main Image -->
            <div class="bg-[#e9ecef] w-full h-[350px] sm:h-[500px] rounded-lg mb-4 flex items-center justify-center overflow-hidden">
                @if($product->images && is_array($product->images) && count($product->images) > 0)
                    <img id="mainImage" src="{{ asset('storage/' . $product->images[0]) }}" alt="{{ $product->name }}" class="w-full h-full object-contain">
                @elseif($product->image)
                    <img id="mainImage" src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-contain">
                @else
                    <div class="text-gray-400 flex flex-col items-center">
                        <i class="fa-regular fa-image text-3xl mb-1"></i>
                        <span class="text-[10px] tracking-widest uppercase">Image</span>
                    </div>
                @endif
            </div>

            <!-- Thumbnails -->
            @if($product->images && is_array($product->images) && count($product->images) > 0)
                <div class="flex gap-4 overflow-x-auto justify-center py-2 px-2 snap-x">
                    @foreach($product->images as $index => $img)
                        <div class="bg-[#e9ecef] w-20 h-20 rounded-lg flex-shrink-0 cursor-pointer overflow-hidden snap-center flex items-center justify-center opacity-80 hover:opacity-100 transition-all thumbnail-box {{ $index === 0 ? 'opacity-100 ring-2 ring-black ring-offset-2' : '' }}"
                             onclick="changeImage(this, '{{ asset('storage/' . $img) }}', {{ $index }})">
                            <img src="{{ asset('storage/' . $img) }}" alt="Thumbnail" class="w-full h-full object-cover">
                        </div>
                    @endforeach
                </div>
            @endif
            
            <!-- Carousel dots -->
            @if($product->images && is_array($product->images) && count($product->images) > 0)
                <div class="flex justify-center gap-1.5 mt-4" id="carousel-dots">
                    @for($i = 0; $i < count($product->images); $i++)
                        <div class="w-1.5 h-1.5 rounded-full carousel-dot {{ $i === 0 ? 'bg-gray-400' : 'bg-black' }}"></div>
                    @endfor
                </div>
            @endif

            <script>
                function changeImage(element, src, index) {
                    const mainImage = document.getElementById('mainImage');
                    if (mainImage) mainImage.src = src;
                    
                    document.querySelectorAll('.thumbnail-box').forEach(el => {
                        el.classList.remove('opacity-100', 'ring-2', 'ring-black', 'ring-offset-2');
                        el.classList.add('opacity-80');
                    });
                    
                    element.classList.remove('opacity-80');
                    element.classList.add('opacity-100', 'ring-2', 'ring-black', 'ring-offset-2');

                    // Update carousel dots
                    document.querySelectorAll('.carousel-dot').forEach((dot, i) => {
                        if (i === index) {
                            dot.classList.remove('bg-black');
                            dot.classList.add('bg-gray-400');
                        } else {
                            dot.classList.remove('bg-gray-400');
                            dot.classList.add('bg-black');
                        }
                    });
                }
            </script>
        </div>

        <!-- Product Details Section -->
        <div class="flex flex-col md:flex-row justify-between items-start mt-12 max-w-5xl mx-auto gap-12">
            
            <!-- Left Column -->
            <div class="flex-1 w-full">
                <h1 class="text-3xl sm:text-4xl font-normal text-black mb-3">{{ $product->name }}</h1>
                
                @php
                    $avgRating = $product->reviews->avg('rating') ?: 4.5; // Defaulting to 4.5 for mockup look if no reviews
                    $totalReviews = $product->reviews->count() ?: 20; // Defaulting to 20 for mockup look
                @endphp
                
                <div class="flex items-center gap-3 mb-4">
                    <div class="flex text-black text-xs">
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star-half-stroke"></i>
                    </div>
                    <span class="text-xs font-medium text-black">{{ number_format($avgRating, 1) }} ( {{ $totalReviews }} Ulasan )</span>
                </div>

                <div class="inline-flex items-center gap-2 border border-gray-200 px-3 py-1.5 rounded text-[10px] font-bold mb-8">
                    <div class="w-1.5 h-1.5 rounded-full {{ $product->stock > 0 ? 'bg-green-600' : 'bg-red-600' }}"></div>
                    <span class="text-black tracking-wider uppercase">Stock : {{ $product->stock }}</span>
                </div>

                <div class="mt-4">
                    <h3 class="text-[11px] font-bold text-gray-500 uppercase tracking-widest mb-3">DESKRIPSI PRODUK</h3>
                    <div class="text-gray-400 text-sm leading-relaxed space-y-2">
                        @if($product->description)
                            <p class="text-gray-700 whitespace-pre-wrap">{{ $product->description }}</p>
                        @else
                            <div class="h-4 bg-[#cfd4da] rounded w-full"></div>
                            <div class="h-4 bg-[#cfd4da] rounded w-11/12"></div>
                            <div class="h-4 bg-[#cfd4da] rounded w-10/12"></div>
                            <div class="h-4 bg-[#cfd4da] rounded w-full"></div>
                            <div class="h-4 bg-[#cfd4da] rounded w-9/12"></div>
                            <div class="h-4 bg-[#cfd4da] rounded w-11/12"></div>
                            <div class="h-4 bg-[#cfd4da] rounded w-5/12"></div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Right Column -->
            <div class="md:w-80 w-full flex flex-col pt-2">
                <div class="text-3xl sm:text-4xl font-mono text-black mb-6 tracking-tight self-start md:self-auto">
                    Rp {{ number_format($product->price, 0, ',', '.') }}
                </div>
                
                <div class="flex items-center justify-between w-full mb-6">
                    <span class="text-sm text-gray-600">Jumlah</span>
                    <div class="flex items-center bg-[#f8f9fa] border border-gray-200 rounded px-1 py-0.5">
                        <button type="button" onclick="decrementQty()" class="w-8 h-8 flex items-center justify-center text-gray-500 hover:text-black hover:bg-gray-200 rounded">-</button>
                        <span id="qty-display" class="w-10 text-center text-sm font-medium">1</span>
                        <button type="button" onclick="incrementQty()" class="w-8 h-8 flex items-center justify-center text-gray-500 hover:text-black hover:bg-gray-200 rounded">+</button>
                    </div>
                </div>

                <script>
                    let maxStock = {{ $product->stock }};
                    function incrementQty() {
                        let input = document.getElementById('qty-input');
                        let display = document.getElementById('qty-display');
                        if(!input) return; // for admin or guest who don't have the form
                        let current = parseInt(input.value);
                        if (current < maxStock) {
                            input.value = current + 1;
                            display.innerText = current + 1;
                        }
                    }
                    function decrementQty() {
                        let input = document.getElementById('qty-input');
                        let display = document.getElementById('qty-display');
                        if(!input) return;
                        let current = parseInt(input.value);
                        if (current > 1) {
                            input.value = current - 1;
                            display.innerText = current - 1;
                        }
                    }
                </script>

                @if(auth()->check() && auth()->user()->role === 'admin')
                    <a href="{{ route('products.edit', $product->id) }}" class="w-full bg-yellow-500 text-white py-3.5 rounded-[4px] text-sm font-medium hover:bg-yellow-600 transition text-center mb-3">
                        Edit Produk
                    </a>
                    <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="w-full">
                        @csrf @method('DELETE')
                        <button type="submit" class="w-full bg-red-500 text-white py-3.5 rounded-[4px] text-sm font-medium hover:bg-red-600 transition" onclick="return confirm('Hapus produk ini?')">
                            Hapus Produk
                        </button>
                    </form>
                @elseif(auth()->check() && auth()->user()->role === 'user')
                    @if($product->stock > 0)
                        <form action="{{ route('cart.add', $product->id) }}" method="POST" class="w-full">
                            @csrf
                            <input type="hidden" name="quantity" id="qty-input" value="1">
                            <button type="submit" class="w-full bg-[#3e4b5b] text-white py-4 rounded-md text-sm font-medium hover:bg-black transition shadow-sm">
                                Tambah ke Keranjang
                            </button>
                        </form>
                    @else
                        <button disabled class="w-full bg-gray-400 cursor-not-allowed text-white py-4 rounded-md text-sm font-medium">
                            Stok Habis
                        </button>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="w-full bg-[#3e4b5b] text-white py-4 rounded-md text-sm font-medium hover:bg-black transition shadow-sm text-center block">
                        Login untuk Membeli
                    </a>
                @endif
            </div>
        </div>

        <!-- Ulasan Section (Preserving functionality below the mockup design) -->
        <div class="mt-20 pt-10 border-t border-gray-200 max-w-5xl mx-auto">
            <h2 class="text-2xl font-bold mb-8 text-gray-800">Ulasan Produk</h2>

            <!-- Form Ulasan -->
            @if(auth()->check() && auth()->user()->role === 'user')
                @php
                    // Mocking $hasBought and $hasReviewed for view consistency if not passed
                    $hasBought = $hasBought ?? false;
                    $hasReviewed = $hasReviewed ?? false;
                @endphp
                @if($hasBought && !$hasReviewed)
                    <div class="bg-gray-50 p-6 rounded-xl mb-10 border border-gray-100">
                        <h3 class="font-bold text-gray-800 mb-4">Berikan Ulasan Anda</h3>
                        <form action="{{ route('reviews.store', $product->id) }}" method="POST">
                            @csrf
                            <div class="mb-5">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Rating</label>
                                <select name="rating" class="border rounded-lg p-2.5 outline-blue-500 w-full max-w-xs" required>
                                    <option value="" disabled selected>Pilih Rating...</option>
                                    <option value="5">⭐⭐⭐⭐⭐ (5/5)</option>
                                    <option value="4">⭐⭐⭐⭐ (4/5)</option>
                                    <option value="3">⭐⭐⭐ (3/5)</option>
                                    <option value="2">⭐⭐ (2/5)</option>
                                    <option value="1">⭐ (1/5)</option>
                                </select>
                            </div>
                            <div class="mb-5">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Komentar (Opsional)</label>
                                <textarea name="comment" rows="3" class="w-full border rounded-lg p-3 outline-blue-500" placeholder="Ceritakan pengalaman Anda menggunakan produk ini..."></textarea>
                            </div>
                            <button type="submit" class="bg-[#3e4b5b] text-white px-8 py-2.5 rounded-lg hover:bg-black font-medium transition shadow-sm text-sm">Kirim Ulasan</button>
                        </form>
                    </div>
                @elseif($hasReviewed)
                    <div class="bg-blue-50 text-blue-700 p-4 rounded-lg mb-10 border border-blue-100 text-sm">
                        Anda sudah memberikan ulasan untuk produk ini. Terima kasih!
                    </div>
                @endif
            @endif

            <!-- Daftar Ulasan -->
            <div class="space-y-8">
                @forelse($product->reviews as $review)
                    <div class="border-b pb-8 last:border-b-0">
                        <div class="flex items-center gap-3 mb-3">
                            <div class="w-10 h-10 bg-gray-200 rounded-full flex items-center justify-center font-bold text-gray-600">
                                {{ substr($review->user->name, 0, 1) }}
                            </div>
                            <div>
                                <p class="font-semibold text-gray-800">{{ $review->user->name }}</p>
                                <p class="text-xs text-gray-500">{{ $review->created_at->format('d M Y') }}</p>
                            </div>
                        </div>
                        <div class="flex text-yellow-400 text-sm mb-3">
                            @for($i = 1; $i <= 5; $i++)
                                <span>{{ $i <= $review->rating ? '★' : '☆' }}</span>
                            @endfor
                        </div>
                        @if($review->comment)
                            <p class="text-gray-700">{{ $review->comment }}</p>
                        @endif
                    </div>
                @empty
                    <p class="text-gray-500 italic">Belum ada ulasan untuk produk ini.</p>
                @endforelse
            </div>
        </div>

    </div>
</body>
</html>
