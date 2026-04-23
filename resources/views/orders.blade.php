<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Riwayat Pesanan</title>
</head>
<body class="bg-gray-50 p-6 md:p-10 text-gray-800">
    <div class="max-w-5xl mx-auto">
        <!-- Header -->
        <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4 bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <h1 class="text-2xl font-bold border-b-2 border-blue-600 pb-1">📦 Riwayat Pesanan</h1>
            <a href="{{ route('products.index') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300 font-medium transition shadow-sm">Kembali ke Katalog</a>
        </div>

        @if($orders->isEmpty())
            <div class="bg-white p-12 rounded-xl border border-gray-100 text-center shadow-sm">
                <p class="text-gray-500 text-lg mb-4">Anda belum memiliki riwayat pesanan.</p>
                <a href="{{ route('products.index') }}" class="text-blue-600 hover:underline font-medium">Buka Katalog Produk</a>
            </div>
        @else
            <div class="space-y-6">
                @foreach($orders as $order)
                    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                        <div class="flex justify-between items-center border-b pb-4 mb-4">
                            <div>
                                <p class="text-sm text-gray-500">Nomor Pesanan: <span class="font-bold text-gray-800">#{{ $order->id }}</span></p>
                                <p class="text-sm text-gray-500">Tanggal: <span class="font-medium text-gray-800">{{ $order->created_at->format('d M Y H:i') }}</span></p>
                            </div>
                            <div>
                                <span class="bg-green-100 text-green-800 text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wide">
                                    {{ $order->status }}
                                </span>
                            </div>
                        </div>

                        <div class="space-y-3 mb-4">
                            @foreach($order->items as $item)
                                <div class="flex items-center gap-4 border-b border-dashed pb-3 last:border-0 last:pb-0">
                                    <div class="w-16 h-16 bg-gray-100 rounded-lg overflow-hidden flex-shrink-0">
                                        @if($item->product && $item->product->image)
                                            <img src="{{ asset('storage/' . $item->product->image) }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="flex items-center justify-center w-full h-full text-[10px] text-gray-400">No Image</div>
                                        @endif
                                    </div>
                                    <div class="flex-grow">
                                        <h3 class="font-semibold text-gray-800">{{ $item->product ? $item->product->name : 'Produk Dihapus' }}</h3>
                                        <p class="text-sm text-gray-500">{{ $item->quantity }} x Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                                    </div>
                                    <div class="font-bold text-gray-800">
                                        Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="border-t pt-4 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                            <div class="text-sm text-gray-600">
                                <span class="font-semibold block mb-1">Alamat Pengiriman:</span>
                                {{ $order->address }}
                            </div>
                            <div class="text-right w-full sm:w-auto bg-gray-50 p-3 rounded-lg border">
                                <p class="text-sm text-gray-500">Total Belanja</p>
                                <p class="text-xl font-black text-blue-600">Rp {{ number_format($order->total_price, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</body>
</html>
