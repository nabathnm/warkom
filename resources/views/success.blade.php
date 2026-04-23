<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Pemesanan Berhasil</title>
</head>
<body class="bg-gray-50 text-gray-800 min-h-screen flex items-center justify-center p-6">
    <div class="max-w-md w-full bg-white p-8 rounded-xl shadow-sm border border-gray-100 text-center">
        <div class="w-20 h-20 bg-green-100 text-green-600 rounded-full flex items-center justify-center mx-auto mb-6">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
            </svg>
        </div>
        
        <h1 class="text-2xl font-bold text-gray-800 mb-2">Pemesanan Berhasil!</h1>
        <p class="text-gray-600 mb-8 leading-relaxed">
            Terima kasih telah berbelanja di Warkom. Pesanan Anda telah kami terima dan akan segera diproses.
        </p>

        <a href="{{ route('products.index') }}" class="inline-block bg-blue-600 text-white font-medium px-8 py-3 rounded-lg shadow-sm hover:bg-blue-700 transition">
            Kembali ke Katalog Produk
        </a>
    </div>
</body>
</html>
