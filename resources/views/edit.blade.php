<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Edit Produk</title>
</head>
<body class="bg-gray-50 p-10 text-gray-800">
    <div class="max-w-2xl mx-auto bg-white p-8 rounded-xl shadow-sm border border-gray-100">
        <h1 class="text-2xl font-bold mb-6">Edit Produk</h1>
        
        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <ul class="list-disc ml-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            @method('PUT')
            
            @if($product->image)
                <div class="mb-4">
                    <label class="block font-medium mb-1 line-through text-gray-400">Gambar Saat Ini:</label>
                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-32 h-32 object-cover rounded-lg shadow-sm border">
                </div>
            @endif

            <div>
                <label class="block font-medium mb-1">Ganti Gambar (Opsional)</label>
                <input type="file" name="image" class="w-full border rounded-lg p-2 outline-blue-500 bg-gray-50" accept="image/*">
            </div>
            <div>
                <label class="block font-medium mb-1">Nama Produk</label>
                <input type="text" name="name" value="{{ old('name', $product->name) }}" class="w-full border rounded-lg p-2 outline-blue-500" required>
            </div>
            <div>
                <label class="block font-medium mb-1">Kategori</label>
                <select name="category" class="w-full border rounded-lg p-2 outline-blue-500 bg-white" required>
                    <option value="" disabled>Pilih Kategori...</option>
                    <option value="cpu" {{ $product->category == 'cpu' ? 'selected' : '' }}>CPU / Prosesor</option>
                    <option value="motherboard" {{ $product->category == 'motherboard' ? 'selected' : '' }}>Motherboard</option>
                    <option value="vga" {{ $product->category == 'vga' ? 'selected' : '' }}>VGA / Kartu Grafis</option>
                    <option value="ram" {{ $product->category == 'ram' ? 'selected' : '' }}>RAM / Memori</option>
                    <option value="storage" {{ $product->category == 'storage' ? 'selected' : '' }}>Penyimpanan (HDD/SSD)</option>
                    <option value="psu" {{ $product->category == 'psu' ? 'selected' : '' }}>Power Supply (PSU)</option>
                    <option value="casing" {{ $product->category == 'casing' ? 'selected' : '' }}>Casing</option>
                    <option value="cooling" {{ $product->category == 'cooling' ? 'selected' : '' }}>Pendingin / Cooling</option>
                    <option value="aksesoris" {{ $product->category == 'aksesoris' ? 'selected' : '' }}>Aksesoris</option>
                </select>
            </div>
            <div>
                <label class="block font-medium mb-1">Deskripsi</label>
                <textarea name="description" class="w-full border rounded-lg p-2 outline-blue-500" rows="3" required>{{ old('description', $product->description) }}</textarea>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block font-medium mb-1">Harga</label>
                    <input type="number" name="price" value="{{ old('price', $product->price) }}" class="w-full border rounded-lg p-2 outline-blue-500" required>
                </div>
                <div>
                    <label class="block font-medium mb-1">Stok</label>
                    <input type="number" name="stock" value="{{ old('stock', $product->stock) }}" class="w-full border rounded-lg p-2 outline-blue-500" required>
                </div>
            </div>
            <div class="flex gap-2 pt-4">
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">Update</button>
                <a href="{{ route('products.index') }}" class="bg-gray-200 px-6 py-2 rounded-lg hover:bg-gray-300 shadow-sm text-center">Batal</a>
            </div>
        </form>
    </div>
</body>
</html>
