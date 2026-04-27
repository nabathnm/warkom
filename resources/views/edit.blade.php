<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Edit Produk</title>
</head>
<body class="bg-white p-10 text-black font-sans">
    <div class="max-w-2xl mx-auto">
        <h1 class="text-4xl mb-6">Edit Produk</h1>
        
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
            <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')
                
                @if($product->images && is_array($product->images) && count($product->images) > 0)
                    <div class="mb-4">
                        <label class="block mb-2 text-[15px]">Pilih Gambar</label>
                        <div class="flex gap-4 overflow-x-auto pb-2">
                            @foreach($product->images as $img)
                                <div class="flex-shrink-0 flex flex-col items-start gap-2 image-container">
                                    <div class="w-24 h-24 bg-gray-200 flex items-center justify-center border border-gray-300">
                                        <img src="{{ asset('storage/' . $img) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                                    </div>
                                    <div class="flex gap-2">
                                        <label class="relative cursor-pointer">
                                            <input type="radio" name="main_image" value="{{ $img }}" {{ $loop->first ? 'checked' : '' }} class="peer sr-only">
                                            <div class="text-[10px] bg-white px-3 py-1 border border-gray-500 text-gray-700 peer-checked:bg-[#fca566] peer-checked:font-bold hover:bg-gray-50 transition">Utama</div>
                                        </label>
                                        <label class="relative cursor-pointer">
                                            <input type="checkbox" name="delete_images[]" value="{{ $img }}" class="peer sr-only">
                                            <div class="text-[10px] bg-white px-3 py-1 border border-gray-500 text-gray-700 peer-checked:bg-red-100 peer-checked:text-red-700 peer-checked:border-red-500 hover:bg-red-200 transition">Hapus</div>
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <p class="text-[11px] text-gray-400 mt-2">Pilih "Utama" untuk menjadikan foto sebagai gambar utama (carousel)</p>
                    </div>
                @elseif($product->image)
                    <div class="mb-4">
                        <label class="block mb-2 text-[15px]">Gambar Saat Ini</label>
                        <div class="w-24 h-24 bg-gray-200 flex items-center justify-center border border-gray-300">
                            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                        </div>
                    </div>
                @endif

                <div>
                    <label class="block mb-2 text-[15px]">Tambah Gambar</label>
                    <input type="file" name="images[]" multiple class="w-full border border-gray-400 p-1.5 text-sm bg-white" accept="image/*">
                </div>
                <div>
                    <label class="block mb-2 text-[15px]">Nama Produk</label>
                    <input type="text" name="name" value="{{ old('name', $product->name) }}" class="w-full border border-gray-400 p-2 focus:outline-none" required>
                </div>
                <div>
                    <label class="block mb-2 text-[15px]">Kategori Produk</label>
                    <select name="category" class="w-full border border-gray-400 p-2 bg-white focus:outline-none" required>
                        <option value="" disabled></option>
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
                    <label class="block mb-2 text-[15px]">Deskripsi Produk</label>
                    <textarea name="description" class="w-full border border-gray-400 p-2 focus:outline-none" rows="4" required>{{ old('description', $product->description) }}</textarea>
                </div>
                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <label class="block mb-2 text-[15px]">Harga</label>
                        <input type="number" name="price" value="{{ old('price', $product->price) }}" class="w-full border border-gray-400 p-2 focus:outline-none" required>
                    </div>
                    <div>
                        <label class="block mb-2 text-[15px]">Stok</label>
                        <input type="number" name="stock" value="{{ old('stock', $product->stock) }}" class="w-full border border-gray-400 p-2 focus:outline-none" required>
                    </div>
                </div>
                <div class="flex gap-4 pt-4">
                    <button type="submit" class="bg-[#fca566] border border-gray-500 text-black px-8 py-1.5 text-xs">Update</button>
                    <a href="{{ route('products.index') }}" class="bg-[#9ca3af] border border-gray-500 px-8 py-1.5 text-xs text-white text-center flex items-center justify-center">Batal</a>
                </div>
            </form>
        </div>
    </div>
    
    <script>
        document.querySelectorAll('input[name="delete_images[]"]').forEach(function(checkbox) {
            checkbox.addEventListener('change', function() {
                if(this.checked) {
                    // Sembunyikan elemen gambar secara visual
                    this.closest('.image-container').style.display = 'none';
                }
            });
        });
    </script>
</body>
</html>
