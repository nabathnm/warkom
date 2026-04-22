<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Tambah Produk Baru</title>
</head>
<body class="bg-gray-50 p-10 text-gray-800">
    <div class="max-w-2xl mx-auto bg-white p-8 rounded-xl shadow-sm border border-gray-100">
        <h1 class="text-2xl font-bold mb-6">Tambah Produk Baru</h1>

        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <ul class="list-disc ml-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <div>
                <label class="block font-medium mb-1">Gambar Produk</label>
                <input type="file" name="image" class="w-full border rounded-lg p-2 outline-blue-500 bg-gray-50" accept="image/*">
            </div>
            <div>
                <label class="block font-medium mb-1">Nama Produk</label>
                <input type="text" name="name" class="w-full border rounded-lg p-2 outline-blue-500" required>
            </div>
            <div>
                <label class="block font-medium mb-1">Deskripsi</label>
                <textarea name="description" class="w-full border rounded-lg p-2 outline-blue-500" rows="3" required></textarea>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block font-medium mb-1">Harga</label>
                    <input type="number" name="price" class="w-full border rounded-lg p-2 outline-blue-500" required>
                </div>
                <div>
                    <label class="block font-medium mb-1">Stok</label>
                    <input type="number" name="stock" class="w-full border rounded-lg p-2 outline-blue-500" required>
                </div>
            </div>
            <div class="flex gap-2 pt-4">
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">Simpan</button>
                <a href="{{ route('products.index') }}" class="bg-gray-200 px-6 py-2 rounded-lg hover:bg-gray-300 shadow-sm text-center">Batal</a>
            </div>
        </form>
    </div>
</body>
</html>