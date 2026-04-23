<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Product::query();
        if ($request->has('search') && $request->search != '') {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
        }
        $products = $query->get();
        return view('index', compact('products'));
    }

    public function store(Request $request)
    {
        abort_if(auth()->user()->role !== 'admin', 403, 'Akses Ditolak.');

        $request->validate([
            'name' => 'required',
            'category' => 'required|in:cpu,motherboard,vga,ram,storage,psu,casing,cooling,aksesoris',
            'description' => 'required',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $data = $request->all();
        
        // Handle single image fallback (optional, but let's keep it if we still want it or just use images)
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        if ($request->hasFile('images')) {
            $images = [];
            foreach ($request->file('images') as $file) {
                $images[] = $file->store('products', 'public');
            }
            $data['images'] = $images;
        }

        Product::create($data);
        return redirect()->route('products.index')->with('success', 'Produk berhasil ditambahkan!');
    }

    public function create()
    {
        abort_if(auth()->user()->role !== 'admin', 403, 'Akses Ditolak.');
        return view('create');
    }

    public function show(Product $product)
    {
        $hasBought = false;
        $hasReviewed = false;

        if (auth()->check()) {
            $hasBought = auth()->user()->orders()->whereHas('items', function($q) use ($product) {
                $q->where('product_id', $product->id);
            })->exists();

            $hasReviewed = $product->reviews()->where('user_id', auth()->id())->exists();
        }

        $product->load('reviews.user');
        
        return view('show', compact('product', 'hasBought', 'hasReviewed'));
    }

    public function edit(Product $product)
    {
        abort_if(auth()->user()->role !== 'admin', 403, 'Akses Ditolak.');
        return view('edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        abort_if(auth()->user()->role !== 'admin', 403, 'Akses Ditolak.');

        $request->validate([
            'name' => 'required',
            'category' => 'required|in:cpu,motherboard,vga,ram,storage,psu,casing,cooling,aksesoris',
            'description' => 'required',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $data = $request->all();

        // Fallback for single image delete/update
        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        $existingImages = is_array($product->images) ? $product->images : [];

        // Hapus gambar yang dicentang
        if ($request->has('delete_images')) {
            foreach ($request->delete_images as $delImg) {
                if (($key = array_search($delImg, $existingImages)) !== false) {
                    Storage::disk('public')->delete($delImg);
                    unset($existingImages[$key]);
                }
            }
            $existingImages = array_values($existingImages); // Reindex array
        }

        // Tambahkan gambar baru (append)
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $existingImages[] = $file->store('products', 'public');
            }
        }

        // Jadikan gambar utama (pindah ke index 0)
        if ($request->has('main_image')) {
            $mainImg = $request->input('main_image');
            if (($key = array_search($mainImg, $existingImages)) !== false) {
                unset($existingImages[$key]);
                array_unshift($existingImages, $mainImg);
            }
        }
        
        $data['images'] = array_values($existingImages);

        $product->update($data);
        return redirect()->route('products.index')->with('success', 'Produk berhasil diupdate!');
    }

    public function destroy(Product $product)
    {
        abort_if(auth()->user()->role !== 'admin', 403, 'Akses Ditolak.');

        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        if ($product->images && is_array($product->images)) {
            foreach ($product->images as $img) {
                Storage::disk('public')->delete($img);
            }
        }

        $product->delete();
        return redirect()->route('products.index')->with('success', 'Produk berhasil dihapus!');
    }
}
