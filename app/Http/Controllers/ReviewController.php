<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Product;
use App\Models\Review;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function store(Request $request, Product $product)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        // Check if bought
        $hasBought = Auth::user()->orders()->whereHas('items', function($q) use ($product) {
            $q->where('product_id', $product->id);
        })->exists();

        if (!$hasBought) {
            return redirect()->back()->with('error', 'Anda harus membeli produk ini terlebih dahulu untuk memberikan ulasan.');
        }

        // Check if already reviewed
        if ($product->reviews()->where('user_id', Auth::id())->exists()) {
            return redirect()->back()->with('error', 'Anda sudah memberikan ulasan untuk produk ini.');
        }

        Review::create([
            'user_id' => Auth::id(),
            'product_id' => $product->id,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return redirect()->back()->with('success', 'Ulasan berhasil ditambahkan! Terima kasih atas feedback Anda.');
    }
}
