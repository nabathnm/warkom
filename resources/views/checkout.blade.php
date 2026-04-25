<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <title>Checkout - Warkom</title>
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

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <h1 class="text-xl text-black mb-6">Checkout</h1>

        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6 text-xs">
                <ul class="list-disc ml-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="flex flex-col lg:flex-row gap-10 items-start">
            
            <!-- Left Column: Alamat & Produk -->
            <div class="flex-grow w-full">
                <form action="{{ route('checkout.process') }}" method="POST" id="checkout-form">
                    @csrf
                    @foreach($carts as $cart)
                        <input type="hidden" name="selected[]" value="{{ $cart->id }}">
                    @endforeach
                    
                    <!-- Address Box -->
                    <div class="border border-gray-300 p-6 mb-8 bg-white">
                        <h2 class="text-[10px] text-black font-bold mb-4">Alamat Pengiriman</h2>
                        
                        <textarea name="address" rows="5" class="w-full bg-transparent border-none outline-none text-[10px] text-gray-800 p-0 resize-none leading-relaxed" required placeholder="Masukkan detail alamat Anda di sini...">{{ old('address') }}</textarea>
                    </div>
                    
                    <!-- Ringkasan Produk Box -->
                    <h2 class="text-lg text-black mb-4">Ringkasan Produk</h2>
                    <div class="border border-gray-300 flex flex-col bg-white">
                        @php $subtotalRaw = 0; @endphp
                        @foreach($carts as $cart)
                            @php 
                                $subtotalItem = $cart->product->price * $cart->quantity;
                                $subtotalRaw += $subtotalItem;
                            @endphp
                            <div class="flex items-center p-6 border-b border-gray-200 last:border-b-0">
                                <!-- Image -->
                                <div class="w-16 h-16 bg-[#e2e8f0] flex-shrink-0 flex items-center justify-center overflow-hidden mr-6">
                                    @if($cart->product->images && is_array($cart->product->images) && count($cart->product->images) > 0)
                                        <img src="{{ asset('storage/' . $cart->product->images[0]) }}" alt="{{ $cart->product->name }}" class="w-full h-full object-cover opacity-80">
                                    @elseif($cart->product->image)
                                        <img src="{{ asset('storage/' . $cart->product->image) }}" alt="{{ $cart->product->name }}" class="w-full h-full object-cover opacity-80">
                                    @else
                                        <i class="fa-regular fa-image text-gray-400 text-xs"></i>
                                    @endif
                                </div>
                                
                                <!-- Info -->
                                <div class="flex-grow">
                                    <h3 class="text-[10px] text-black">{{ $cart->product->name }}</h3>
                                    <p class="text-[10px] text-black mt-1">Rp. {{ number_format($cart->product->price, 0, ',', '.') }} x {{ $cart->quantity }}</p>
                                </div>
                                
                                <!-- Price -->
                                <div class="text-sm text-black">
                                    Rp {{ number_format($subtotalItem, 0, ',', '.') }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                </form>
            </div>

            <!-- Right Sidebar: Ringkasan Pesanan & Metode Pembayaran -->
            <div class="w-full lg:w-[320px] flex-shrink-0 flex flex-col gap-6">
                
                @php 
                    $ongkir = 15000;
                    $ppn = $subtotalRaw * 0.01;
                    $grandTotal = $subtotalRaw + $ongkir + $ppn;
                @endphp

                <!-- Ringkasan Pesanan Box -->
                <div class="bg-[#cbd5e1] p-6 rounded-xl">
                    <h2 class="text-sm text-black mb-6">Ringkasan Pesanan</h2>
                    
                    <div class="space-y-4 mb-6">
                        <div class="flex justify-between items-center text-[10px] text-black">
                            <span>Subtotal</span>
                            <span>Rp {{ number_format($subtotalRaw, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between items-center text-[10px] text-black">
                            <span>Ongkos kirim</span>
                            <span>Rp {{ number_format($ongkir, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between items-center text-[10px] text-black">
                            <span>Biaya PPN (1%)</span>
                            <span>Rp {{ number_format($ppn, 0, ',', '.') }}</span>
                        </div>
                    </div>
                    
                    <hr class="border-gray-400 mb-4">
                    
                    <div class="flex justify-between items-center text-[10px] text-black font-bold mb-6">
                        <span>Total Pembayaran</span>
                        <span>Rp {{ number_format($grandTotal, 0, ',', '.') }}</span>
                    </div>
                    
                    <button type="button" onclick="document.getElementById('checkout-form').submit()" class="block w-full bg-[#3e4b5b] text-white text-[10px] py-3 rounded text-center hover:bg-black transition-colors shadow-sm mb-3">
                        Pesan sekarang
                    </button>
                    
                    <a href="{{ route('cart.index') }}" class="block w-full bg-[#f8fafc] border border-gray-300 text-black text-[10px] py-3 rounded text-center hover:bg-gray-100 transition-colors shadow-sm">
                        Kembali ke keranjang
                    </a>
                </div>

                <!-- Metode Pembayaran Box -->
                <div class="bg-[#cbd5e1] p-6 rounded-xl">
                    <h2 class="text-sm text-black mb-4">Metode Pembayaran</h2>
                    
                    <div class="space-y-2 mb-6">
                        <label class="flex items-center gap-3 border border-gray-400 p-2 cursor-pointer hover:bg-[#b0bdcc] transition-colors">
                            <input type="radio" name="payment_method" value="qris" class="w-3 h-3" checked form="checkout-form">
                            <span class="text-[9px] text-black uppercase tracking-wider">QRIS</span>
                        </label>
                        <label class="flex items-center gap-3 border border-gray-400 p-2 cursor-pointer hover:bg-[#b0bdcc] transition-colors">
                            <input type="radio" name="payment_method" value="bca" class="w-3 h-3" form="checkout-form">
                            <span class="text-[9px] text-black uppercase tracking-wider">BANK BCA</span>
                        </label>
                        <label class="flex items-center gap-3 border border-gray-400 p-2 cursor-pointer hover:bg-[#b0bdcc] transition-colors">
                            <input type="radio" name="payment_method" value="mandiri" class="w-3 h-3" form="checkout-form">
                            <span class="text-[9px] text-black uppercase tracking-wider">BANK MANDIRI</span>
                        </label>
                    </div>

                    <hr class="border-gray-400 mb-4">
                    
                    <div class="flex justify-between items-center text-[10px] text-black font-bold">
                        <span>Total Pembayaran</span>
                        <span>Rp {{ number_format($grandTotal, 0, ',', '.') }}</span>
                    </div>
                </div>

            </div>

        </div>
    </main>
</body>
</html>
