<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <title>Keranjang Belanja - Warkom</title>
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
                            <a href="{{ route('orders.index') }}" class="hover:opacity-80 transition" title="Riwayat Pesanan">
                                <img src="{{ asset('purchase_order.png') }}" alt="Riwayat Pesanan" class="w-[20px] h-[20px] object-contain">
                            </a>
                            <a href="{{ route('cart.index') }}" class="hover:text-green-600 transition" title="Keranjang">
                                <i class="fa-solid fa-cart-shopping"></i>
                            </a>
                        @endif
                        @if(auth()->check() && auth()->user()->role === 'admin')
                            <a href="{{ route('products.create') }}" class="hover:opacity-80 transition" title="Tambah Produk">
                                <img src="{{ asset('add_new_product.png') }}" alt="Tambah Produk" class="w-[20px] h-[20px] object-contain">
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
    <div class="bg-[#cbd5e1] border-b border-gray-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div>
                    <h1 class="text-2xl text-black">Keranjang Anda</h1>
                    <p class="text-[10px] text-black mt-1">Total item {{ $carts->count() }}</p>
                </div>
                <a href="{{ route('products.index') }}" class="flex items-center gap-2 px-4 py-2 border border-gray-500 text-black text-xs hover:bg-gray-400 transition-colors">
                    <i class="fa-solid fa-arrow-left"></i> Kembali ke halaman produk
                </a>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        @if(session('success'))
            <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded text-sm relative" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif
        @if(session('error'))
            <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded text-sm relative" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif

        @if($carts->isEmpty())
            <div class="text-center py-12">
                <p class="text-gray-500 text-lg mb-4">Keranjang belanja Anda masih kosong.</p>
                <a href="{{ route('products.index') }}" class="text-blue-600 hover:underline font-medium">Buka Katalog Produk</a>
            </div>
        @else
            <div class="flex flex-col lg:flex-row gap-12 items-start">
                
                <!-- Items List -->
                <div class="flex-grow w-full">
                    <!-- Pilih Semua -->
                    <div class="flex items-center gap-2 mb-6">
                        <input type="checkbox" id="check-all" class="w-3 h-3 rounded border-gray-300 text-gray-600" checked>
                        <span class="text-[10px] text-gray-600">Pilih Semua</span>
                    </div>

                    <div class="flex flex-col">
                        @php $grandTotal = 0; @endphp
                        @foreach($carts as $cart)
                            @php 
                                $subtotal = $cart->product->price * $cart->quantity;
                                $grandTotal += $subtotal;
                            @endphp
                            
                            <div class="flex flex-col sm:flex-row py-6 border-b border-gray-200 gap-6 last:border-0 relative">
                                
                                <!-- Image -->
                                <div class="w-24 h-24 bg-[#e2e8f0] flex-shrink-0 flex items-center justify-center overflow-hidden">
                                    @if($cart->product->images && is_array($cart->product->images) && count($cart->product->images) > 0)
                                        <img src="{{ asset('storage/' . $cart->product->images[0]) }}" alt="{{ $cart->product->name }}" class="w-full h-full object-cover">
                                    @elseif($cart->product->image)
                                        <img src="{{ asset('storage/' . $cart->product->image) }}" alt="{{ $cart->product->name }}" class="w-full h-full object-cover">
                                    @else
                                        <i class="fa-regular fa-image text-gray-400"></i>
                                    @endif
                                </div>

                                <!-- Details -->
                                <div class="flex-grow flex flex-col justify-between">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <div class="flex items-center gap-3">
                                                <h3 class="text-xs text-black">{{ $cart->product->name }}</h3>
                                                <input type="checkbox" class="w-3 h-3 rounded border-gray-300 text-gray-600 item-checkbox" value="{{ $cart->id }}" data-price="{{ $subtotal }}" checked>
                                            </div>
                                            <p class="text-[10px] text-black mt-1">Rp. {{ number_format($cart->product->price, 0, ',', '.') }} / unit</p>
                                        </div>
                                        <div class="text-[10px] text-black">
                                            Rp {{ number_format($subtotal, 0, ',', '.') }}
                                        </div>
                                    </div>
                                    
                                    <div class="flex justify-between items-end mt-4">
                                        <div>
                                            <span class="text-[8px] text-gray-500 block mb-1">Jumlah</span>
                                            <form id="form-{{$cart->id}}" action="{{ route('cart.update', $cart->id) }}" method="POST" class="flex items-center border border-gray-300 px-1 py-0.5 w-max">
                                                @csrf @method('PUT')
                                                <button type="button" onclick="updateQty('form-{{$cart->id}}', -1)" class="w-5 h-5 flex items-center justify-center text-gray-500 hover:text-black">-</button>
                                                <input type="number" name="quantity" value="{{ $cart->quantity }}" min="1" class="w-6 text-center text-[10px] outline-none bg-transparent" readonly>
                                                <button type="button" onclick="updateQty('form-{{$cart->id}}', 1)" class="w-5 h-5 flex items-center justify-center text-gray-500 hover:text-black">+</button>
                                            </form>
                                        </div>
                                        
                                        <form action="{{ route('cart.remove', $cart->id) }}" method="POST">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="flex items-center gap-2 px-3 py-1 border border-gray-400 text-[10px] text-black hover:bg-gray-100 transition-colors">
                                                Hapus <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Right Sidebar (Ringkasan) -->
                <div class="w-full lg:w-[280px] flex-shrink-0">
                    <div class="bg-[#cbd5e1] p-6 rounded-xl sticky top-6">
                        <h2 class="text-sm text-black mb-6">Ringkasan Pesanan</h2>
                        <div class="flex justify-between items-center text-[10px] text-black mb-6">
                            <span>Subtotal</span>
                            <span id="subtotal-display">Rp {{ number_format($grandTotal, 0, ',', '.') }}</span>
                        </div>
                        <hr class="border-gray-400 mb-6">
                        <form action="{{ route('checkout') }}" method="GET" id="checkout-form">
                            <button type="submit" class="block w-full bg-[#3e4b5b] text-white text-[10px] py-3 rounded text-center hover:bg-black transition-colors shadow-sm">
                                Checkout
                            </button>
                        </form>
                    </div>
                </div>

            </div>
        @endif
    </main>

    <script>
        function updateQty(formId, change) {
            const form = document.getElementById(formId);
            const input = form.querySelector('input[name="quantity"]');
            let current = parseInt(input.value);
            let newValue = current + change;
            if (newValue >= 1) {
                input.value = newValue;
                form.submit();
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            const checkAll = document.getElementById('check-all');
            const itemCheckboxes = document.querySelectorAll('.item-checkbox');
            const subtotalDisplay = document.getElementById('subtotal-display');
            const checkoutForm = document.getElementById('checkout-form');

            function updateSubtotal() {
                let total = 0;
                let allChecked = true;
                itemCheckboxes.forEach(cb => {
                    if (cb.checked) {
                        total += parseInt(cb.dataset.price);
                    } else {
                        allChecked = false;
                    }
                });
                
                if (itemCheckboxes.length > 0) {
                    checkAll.checked = allChecked;
                }
                
                subtotalDisplay.innerText = 'Rp ' + total.toLocaleString('id-ID');
            }

            checkAll.addEventListener('change', function() {
                itemCheckboxes.forEach(cb => {
                    cb.checked = this.checked;
                });
                updateSubtotal();
            });

            itemCheckboxes.forEach(cb => {
                cb.addEventListener('change', updateSubtotal);
            });

            checkoutForm.addEventListener('submit', function(e) {
                e.preventDefault();
                // Remove existing hidden inputs
                checkoutForm.querySelectorAll('input[name="selected[]"]').forEach(el => el.remove());
                
                let selectedCount = 0;
                itemCheckboxes.forEach(cb => {
                    if (cb.checked) {
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = 'selected[]';
                        input.value = cb.value;
                        checkoutForm.appendChild(input);
                        selectedCount++;
                    }
                });

                if (selectedCount === 0) {
                    alert('Pilih setidaknya satu produk untuk di-checkout.');
                    return;
                }

                this.submit();
            });

            // Initial calculation
            updateSubtotal();
        });
    </script>
</body>
</html>
