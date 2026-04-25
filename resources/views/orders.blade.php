<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <title>Riwayat Pesanan - Warkom</title>
</head>
<body class="bg-white text-gray-800 font-sans">
    @php
        $reviewedProductIds = \App\Models\Review::where('user_id', auth()->id())->pluck('product_id')->toArray();
    @endphp
    
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

    <!-- Sub Navigation / Hero Area -->
    <div class="bg-gray-300 border-b border-gray-400">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 md:py-12">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <h1 class="text-3xl md:text-4xl text-black">Riwayat Pesanan</h1>
                <a href="{{ route('products.index') }}" class="flex items-center gap-2 px-4 py-2 border border-gray-500 text-black text-sm hover:bg-gray-400 transition-colors">
                    <i class="fa-solid fa-arrow-left"></i> Kembali ke halaman produk
                </a>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        
        @if($orders->isEmpty())
            <div class="text-center py-12">
                <p class="text-gray-500 text-lg mb-4">Anda belum memiliki riwayat pesanan.</p>
            </div>
        @else
            <div class="flex flex-col gap-8">
                @foreach($orders as $order)
                    <div>
                        <!-- Order Header -->
                        <div class="flex justify-between items-center bg-gray-100 px-4 py-2 text-xs font-bold text-gray-600 rounded-t-md">
                            <span>Pesanan #{{ $order->id }}</span>
                            <span>{{ $order->created_at->format('d M Y H:i') }}</span>
                        </div>
                        
                        <div class="flex flex-col border border-gray-200 border-t-0 rounded-b-md px-4">
                            @foreach($order->items as $item)
                                <div class="flex flex-col sm:flex-row py-6 border-b border-gray-200 gap-6 last:border-0">
                                    <!-- Image -->
                                    <div class="w-32 h-32 bg-gray-200 flex-shrink-0 flex items-center justify-center relative">
                                        @if($item->product && is_array($item->product->images) && count($item->product->images) > 0)
                                            <img src="{{ asset('storage/' . $item->product->images[0]) }}" alt="{{ $item->product->name }}" class="w-full h-full object-cover">
                                        @elseif($item->product && $item->product->image)
                                            <img src="{{ asset('storage/' . $item->product->image) }}" alt="{{ $item->product->name }}" class="w-full h-full object-cover">
                                        @else
                                            <i class="fa-regular fa-image text-gray-400 text-xl mb-1"></i>
                                        @endif
                                    </div>

                                    <!-- Details -->
                                    <div class="flex-grow flex flex-col justify-between">
                                        <div class="flex justify-between items-start">
                                            <div>
                                                <h3 class="font-bold text-black text-sm">{{ $item->product ? $item->product->name : 'Produk Dihapus' }}</h3>
                                                <p class="text-xs text-gray-800 mt-1">{{ $item->quantity }} x Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                                                <p class="text-[10px] text-gray-500 mt-2 flex items-start gap-1 max-w-sm">
                                                    <i class="fa-solid fa-location-dot mt-0.5"></i>
                                                    <span>{{ $order->address }}</span>
                                                </p>
                                            </div>
                                            <div class="font-bold text-black text-sm">
                                                Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}
                                            </div>
                                        </div>
                                        
                                        <div class="flex justify-end gap-3 mt-4">
                                            @if($item->product)
                                                @php
                                                    $imageUrl = '';
                                                    if($item->product && is_array($item->product->images) && count($item->product->images) > 0) {
                                                        $imageUrl = asset('storage/' . $item->product->images[0]);
                                                    } elseif($item->product && $item->product->image) {
                                                        $imageUrl = asset('storage/' . $item->product->image);
                                                    }
                                                @endphp
                                                <form action="{{ route('cart.add', $item->product->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="px-4 py-1.5 border border-gray-400 text-[10px] text-gray-800 hover:bg-gray-100 transition-colors">Beli Lagi</button>
                                                </form>
                                                @if(in_array($item->product->id, $reviewedProductIds))
                                                    <span class="px-4 py-1.5 border border-gray-300 text-[10px] text-gray-400 bg-gray-50 flex items-center cursor-not-allowed">Sudah Diulas</span>
                                                @else
                                                    <button type="button" class="review-btn px-4 py-1.5 border border-gray-400 text-[10px] text-gray-800 hover:bg-gray-100 transition-colors flex items-center"
                                                            data-url="{{ route('reviews.store', $item->product->id) }}"
                                                            data-name="{{ htmlspecialchars($item->product->name, ENT_QUOTES) }}"
                                                            data-price="{{ number_format($item->product->price, 0, ',', '.') }}"
                                                            data-image="{{ $imageUrl }}">
                                                        Beri Ulasan
                                                    </button>
                                                @endif
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </main>
    </main>

    <!-- Review Modal Overlay -->
    <div id="reviewModal" class="fixed inset-0 bg-black/50 hidden flex items-center justify-center z-50 p-4">
        <!-- Modal Content -->
        <div class="bg-white w-full max-w-2xl relative shadow-2xl flex flex-col">
            <!-- Header -->
            <div class="bg-[#cbd5e1] px-8 py-6">
                <h2 class="text-2xl font-normal text-black">Nilai Produk</h2>
            </div>
            
            <div class="p-8">
                <!-- Beri Ulasan Bar -->
                <div class="bg-[#cbd5e1] text-center py-2 mb-8 text-sm text-black">
                    Beri Ulasan
                </div>
                
                <!-- Product Details -->
                <div class="flex items-center gap-6 mb-8">
                    <div id="reviewModalImageContainer" class="w-24 h-24 bg-[#e2e8f0] flex items-center justify-center overflow-hidden">
                        <!-- Image injected here -->
                    </div>
                    <div>
                        <h3 id="reviewModalName" class="text-black text-sm">Nama Produk</h3>
                        <p id="reviewModalPrice" class="text-[10px] text-black mt-1">Rp. 300.000</p>
                    </div>
                </div>
                
                <hr class="border-gray-300 mb-8">
                
                <form id="reviewForm" method="POST">
                    @csrf
                    
                    <div class="mb-6">
                        <label class="block text-sm text-black mb-2">Nilai Produk</label>
                        <div class="flex gap-3 text-2xl text-gray-300" id="starRatingContainer">
                            <i class="fa-regular fa-star cursor-pointer hover:text-gray-500 transition-colors star-icon" data-value="1"></i>
                            <i class="fa-regular fa-star cursor-pointer hover:text-gray-500 transition-colors star-icon" data-value="2"></i>
                            <i class="fa-regular fa-star cursor-pointer hover:text-gray-500 transition-colors star-icon" data-value="3"></i>
                            <i class="fa-regular fa-star cursor-pointer hover:text-gray-500 transition-colors star-icon" data-value="4"></i>
                            <i class="fa-regular fa-star cursor-pointer hover:text-gray-500 transition-colors star-icon" data-value="5"></i>
                        </div>
                        <input type="hidden" name="rating" id="ratingInput" value="0" required>
                    </div>
                    
                    <div class="mb-8">
                        <textarea name="comment" rows="6" class="w-full bg-[#cbd5e1] p-4 text-sm text-black outline-none resize-none placeholder-black" placeholder="Catatan"></textarea>
                    </div>
                    
                    <div>
                        <button type="submit" class="px-8 py-2 border border-gray-500 bg-white text-black text-xs hover:bg-gray-100 transition-colors">
                            Kirim
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Snackbar for success/error messages -->
    @if(session('success'))
        <div id="snackbar" class="fixed bottom-6 right-6 bg-green-600 text-white px-6 py-4 rounded-md shadow-2xl flex items-center gap-3 z-50 transform transition-all duration-300 translate-y-0 opacity-100">
            <i class="fa-solid fa-circle-check text-2xl"></i>
            <span class="text-sm font-medium">{{ session('success') }}</span>
            <button onclick="closeSnackbar()" class="ml-4 text-white hover:text-green-200 transition-colors">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>
    @endif
    
    @if(session('error'))
        <div id="snackbar" class="fixed bottom-6 right-6 bg-red-600 text-white px-6 py-4 rounded-md shadow-2xl flex items-center gap-3 z-50 transform transition-all duration-300 translate-y-0 opacity-100">
            <i class="fa-solid fa-circle-exclamation text-2xl"></i>
            <span class="text-sm font-medium">{{ session('error') }}</span>
            <button onclick="closeSnackbar()" class="ml-4 text-white hover:text-red-200 transition-colors">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>
    @endif

    <script>
        // Modal Logic
        document.querySelectorAll('.review-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const url = this.getAttribute('data-url');
                const name = this.getAttribute('data-name');
                const price = this.getAttribute('data-price');
                const image = this.getAttribute('data-image');
                
                document.getElementById('reviewModal').classList.remove('hidden');
                document.getElementById('reviewModalName').innerText = name;
                document.getElementById('reviewModalPrice').innerText = 'Rp. ' + price;
                document.getElementById('reviewForm').action = url;
                
                // Reset form
                document.getElementById('ratingInput').value = '0';
                document.querySelector('textarea[name="comment"]').value = '';
                updateStars(0);
                
                const imgContainer = document.getElementById('reviewModalImageContainer');
                if (image) {
                    imgContainer.innerHTML = `<img src="${image}" class="w-full h-full object-cover">`;
                } else {
                    imgContainer.innerHTML = `<i class="fa-regular fa-image text-gray-400"></i>`;
                }
            });
        });

        // Close modal if clicked outside
        document.getElementById('reviewModal').addEventListener('click', function(e) {
            if (e.target === this) {
                this.classList.add('hidden');
            }
        });

        // Star Rating Logic
        const stars = document.querySelectorAll('.star-icon');
        let currentRating = 0;

        function updateStars(rating) {
            stars.forEach((star, index) => {
                if (index < rating) {
                    star.classList.remove('fa-regular', 'text-gray-300');
                    star.classList.add('fa-solid', 'text-gray-500');
                } else {
                    star.classList.remove('fa-solid', 'text-gray-500');
                    star.classList.add('fa-regular', 'text-gray-300');
                }
            });
        }

        stars.forEach(star => {
            star.addEventListener('mouseover', function() {
                updateStars(this.getAttribute('data-value'));
            });
            
            star.addEventListener('mouseout', function() {
                updateStars(currentRating);
            });
            
            star.addEventListener('click', function() {
                currentRating = this.getAttribute('data-value');
                document.getElementById('ratingInput').value = currentRating;
                updateStars(currentRating);
            });
        });

        // Snackbar Logic
        function closeSnackbar() {
            const snackbar = document.getElementById('snackbar');
            if (snackbar) {
                snackbar.classList.add('translate-y-24', 'opacity-0');
                setTimeout(() => snackbar.remove(), 300);
            }
        }

        if(document.getElementById('snackbar')) {
            setTimeout(closeSnackbar, 4000);
        }
    </script>
</body>
</html>
