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
            
            @if($product->images && is_array($product->images) && count($product->images) > 0)
                <div class="mb-4">
                    <label class="block font-medium mb-2 text-gray-700">Gambar Saat Ini:</label>
                    <p class="text-xs text-gray-500 mb-2">Pilih "Utama" untuk menjadikan foto sebagai gambar utama (Carousel). Centang "Hapus" untuk membuang foto.</p>
                    <div class="flex gap-4 overflow-x-auto pb-2">
                        @foreach($product->images as $img)
                            <div class="flex-shrink-0 flex flex-col items-center group relative">
                                <img src="{{ asset('storage/' . $img) }}" alt="{{ $product->name }}" class="w-24 h-24 object-cover rounded-lg shadow-sm border mb-2 {{ $loop->first ? 'border-2 border-blue-500' : '' }}">
                                <div class="flex gap-2">
                                    <label class="text-xs text-blue-600 flex items-center gap-1 cursor-pointer bg-blue-50 px-2 py-1 rounded border border-blue-200 hover:bg-blue-100 transition">
                                        <input type="radio" name="main_image" value="{{ $img }}" {{ $loop->first ? 'checked' : '' }}> Utama
                                    </label>
                                    <label class="text-xs text-red-600 flex items-center gap-1 cursor-pointer bg-red-50 px-2 py-1 rounded border border-red-200 hover:bg-red-100 transition">
                                        <input type="checkbox" name="delete_images[]" value="{{ $img }}"> Hapus
                                    </label>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @elseif($product->image)
                <div class="mb-4">
                    <label class="block font-medium mb-1 text-gray-700">Gambar Saat Ini:</label>
                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-32 h-32 object-cover rounded-lg shadow-sm border">
                </div>
            @endif

            <div>
                <label class="block font-medium mb-1">Tambah Gambar Baru (Pilih beberapa file sekaligus)</label>
                <input type="file" name="images[]" multiple class="w-full border rounded-lg p-2 outline-blue-500 bg-gray-50" accept="image/*">
                <p class="text-xs text-gray-500 mt-1">Tips: Tahan tombol Ctrl (atau Shift) saat memilih untuk upload banyak gambar sekaligus.</p>
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
