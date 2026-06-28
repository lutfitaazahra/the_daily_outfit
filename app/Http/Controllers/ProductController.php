<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductSize;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function home()
    {
        $products   = Product::with('category')->where('stock', '>', 0)->latest()->get();
        $categories = Category::all();
        $featured   = Product::with('category')->where('is_featured', 1)->get();
        return view('home', compact('products', 'categories', 'featured'));
    }

    public function shop(Request $request)
    {
        $query = Product::with('category');

        if ($request->category && $request->category !== 'all') {
            $query->whereHas('category', function($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        if ($request->q) {
            $query->where('name', 'like', '%' . $request->q . '%');
        }

        if ($request->sort === 'price_asc') {
            $query->orderBy('price', 'asc');
        } elseif ($request->sort === 'price_desc') {
            $query->orderBy('price', 'desc');
        } elseif ($request->sort === 'name') {
            $query->orderBy('name', 'asc');
        } else {
            $query->latest();
        }

        $products   = $query->get();
        $categories = Category::all();

        return view('shop', compact('products', 'categories'));
    }

    public function newArrivals()
    {
        $products   = Product::with('category')->latest()->limit(12)->get();
        $categories = Category::all();

        return view('new-arrivals', compact('products', 'categories'));
    }

    public function detail($id)
    {
        $product = Product::with(['category', 'sizes'])->findOrFail($id);
        return view('product-detail', compact('product'));
    }
}