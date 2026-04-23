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

        @if(session('error'))
            <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif

        @if($product->image)
            <div class="mb-6">
                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-64 object-cover rounded-xl shadow-sm border border-gray-100">
            </div>
        @endif

        <div class="space-y-4">
            <div>
                <span class="block text-sm text-gray-500 uppercase font-semibold">Nama Produk</span>
                <div class="flex items-center gap-3">
                    <p class="text-lg font-medium">{{ $product->name }}</p>
                    @php
                        $avgRating = $product->reviews->avg('rating') ?: 0;
                        $totalReviews = $product->reviews->count();
                    @endphp
                    @if($totalReviews > 0)
                        <div class="flex items-center bg-yellow-50 px-2 py-0.5 rounded text-sm border border-yellow-100">
                            <span class="text-yellow-500 mr-1">★</span>
                            <span class="font-bold text-gray-800">{{ number_format($avgRating, 1) }}</span>
                            <span class="text-gray-500 ml-1">({{ $totalReviews }})</span>
                        </div>
                    @endif
                </div>
            </div>
            <div>
                <span class="block text-sm text-gray-500 uppercase font-semibold">Kategori</span>
                <span class="inline-block bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded mt-1 uppercase font-semibold tracking-wider">
                    {{ $product->category }}
                </span>
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

        <div class="mt-8 flex items-center gap-3 pt-6 border-t">
            <a href="{{ route('products.index') }}" class="bg-gray-200 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-300 font-medium transition text-center">Kembali</a>
            
            @if(auth()->check() && auth()->user()->role === 'admin')
                <a href="{{ route('products.edit', $product->id) }}" class="bg-yellow-500 text-white px-6 py-2 rounded-lg hover:bg-yellow-600 font-medium transition text-center">Edit Produk</a>
            @elseif(auth()->check() && auth()->user()->role === 'user')
                @if($product->stock > 0)
                    <form action="{{ route('cart.add', $product->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 font-medium transition shadow-sm text-center">Masukkan Keranjang</button>
                    </form>
                @else
                    <button disabled class="bg-gray-400 cursor-not-allowed text-white px-6 py-2 rounded-lg font-medium transition shadow-sm text-center">Stok Habis</button>
                    <p class="text-red-500 text-sm font-medium ml-2">Mohon maaf, stok produk sedang habis.</p>
                @endif
            @endif
        </div>

        <!-- Ulasan Produk -->
        <div class="mt-12 pt-8 border-t border-gray-100">
            <h2 class="text-xl font-bold mb-6 text-gray-800">Ulasan Produk ({{ $totalReviews }})</h2>

            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <!-- Form Ulasan -->
            @if(auth()->check() && auth()->user()->role === 'user')
                @if($hasBought && !$hasReviewed)
                    <div class="bg-gray-50 p-6 rounded-xl mb-8 border border-gray-100">
                        <h3 class="font-bold text-gray-800 mb-4">Berikan Ulasan Anda</h3>
                        <form action="{{ route('reviews.store', $product->id) }}" method="POST">
                            @csrf
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Rating</label>
                                <select name="rating" class="border rounded-lg p-2 outline-blue-500 w-full max-w-xs" required>
                                    <option value="" disabled selected>Pilih Rating...</option>
                                    <option value="5">⭐⭐⭐⭐⭐ (5/5)</option>
                                    <option value="4">⭐⭐⭐⭐ (4/5)</option>
                                    <option value="3">⭐⭐⭐ (3/5)</option>
                                    <option value="2">⭐⭐ (2/5)</option>
                                    <option value="1">⭐ (1/5)</option>
                                </select>
                            </div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Komentar (Opsional)</label>
                                <textarea name="comment" rows="3" class="w-full border rounded-lg p-2 outline-blue-500" placeholder="Ceritakan pengalaman Anda menggunakan produk ini..."></textarea>
                            </div>
                            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 font-medium transition shadow-sm">Kirim Ulasan</button>
                        </form>
                    </div>
                @elseif($hasReviewed)
                    <div class="bg-blue-50 text-blue-700 p-4 rounded-lg mb-8 border border-blue-100">
                        Anda sudah memberikan ulasan untuk produk ini. Terima kasih!
                    </div>
                @endif
            @endif

            <!-- Daftar Ulasan -->
            <div class="space-y-6">
                @forelse($product->reviews as $review)
                    <div class="border-b pb-6 last:border-b-0">
                        <div class="flex items-center gap-2 mb-2">
                            <div class="w-8 h-8 bg-gray-200 rounded-full flex items-center justify-center font-bold text-gray-600 text-sm">
                                {{ substr($review->user->name, 0, 1) }}
                            </div>
                            <div>
                                <p class="font-semibold text-gray-800 text-sm">{{ $review->user->name }}</p>
                                <p class="text-xs text-gray-500">{{ $review->created_at->format('d M Y') }}</p>
                            </div>
                        </div>
                        <div class="flex text-yellow-400 text-sm mb-2">
                            @for($i = 1; $i <= 5; $i++)
                                <span>{{ $i <= $review->rating ? '★' : '☆' }}</span>
                            @endfor
                        </div>
                        @if($review->comment)
                            <p class="text-gray-700 text-sm">{{ $review->comment }}</p>
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
