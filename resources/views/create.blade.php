<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Tambah Produk</title>
</head>
<body class="bg-white p-10 text-black font-sans">
    <div class="max-w-2xl mx-auto">
        <h1 class="text-4xl mb-6">Tambah Produk</h1>

        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 mb-4">
                <ul class="list-disc ml-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="border border-gray-400 p-8">
            <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                <div>
                    <label class="block mb-2 text-[15px]">Tambah Gambar</label>
                    <input type="file" name="images[]" multiple class="w-full border border-gray-400 p-1.5 text-sm bg-white" accept="image/*">
                </div>
                <div>
                    <label class="block mb-2 text-[15px]">Nama Produk</label>
                    <input type="text" name="name" class="w-full border border-gray-400 p-2 focus:outline-none" required>
                </div>
                <div>
                    <label class="block mb-2 text-[15px]">Kategori Produk</label>
                    <select name="category" class="w-full border border-gray-400 p-2 bg-white focus:outline-none" required>
                        <option value="" disabled selected></option>
                        <option value="cpu">CPU / Prosesor</option>
                        <option value="motherboard">Motherboard</option>
                        <option value="vga">VGA / Kartu Grafis</option>
                        <option value="ram">RAM / Memori</option>
                        <option value="storage">Penyimpanan (HDD/SSD)</option>
                        <option value="psu">Power Supply (PSU)</option>
                        <option value="casing">Casing</option>
                        <option value="cooling">Pendingin / Cooling</option>
                        <option value="aksesoris">Aksesoris</option>
                    </select>
                </div>
                <div>
                    <label class="block mb-2 text-[15px]">Deskripsi Produk</label>
                    <textarea name="description" class="w-full border border-gray-400 p-2 focus:outline-none" rows="4" required></textarea>
                </div>
                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <label class="block mb-2 text-[15px]">Harga</label>
                        <input type="number" name="price" class="w-full border border-gray-400 p-2 focus:outline-none" required>
                    </div>
                    <div>
                        <label class="block mb-2 text-[15px]">Stok</label>
                        <input type="number" name="stock" class="w-full border border-gray-400 p-2 focus:outline-none" required>
                    </div>
                </div>
                <div class="flex gap-4 pt-4">
                    <button type="submit" class="bg-[#fca566] border border-gray-500 text-black px-8 py-1.5 text-xs">Tambah</button>
                    <a href="{{ route('products.index') }}" class="bg-[#9ca3af] border border-gray-500 px-8 py-1.5 text-xs text-white text-center flex items-center justify-center">Batal</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>