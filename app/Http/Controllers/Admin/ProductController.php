<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductSize;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products   = Product::with('category')->latest()->get();
        $categories = Category::all();
        return view('admin.products', compact('products', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:200',
            'category_id' => 'required|exists:categories,id',
            'price'       => 'required|numeric|min:0',
            'stock'       => 'required|integer|min:0',
        ]);

        $image = '';
        if ($request->hasFile('image')) {
            $image = $request->file('image')->store('products', 'public');
        }

        $product = Product::create([
            'category_id' => $request->category_id,
            'name'        => $request->name,
            'slug'        => str()->slug($request->name),
            'description' => $request->description,
            'price'       => $request->price,
            'stock'       => $request->stock,
            'image'       => $image,
            'is_featured' => $request->has('is_featured') ? 1 : 0,
        ]);

        $category    = Category::find($request->category_id);
        $isAccessory = $category && $category->slug === 'accessories';

        if ($isAccessory) {
            foreach (['Gold', 'Silver', 'Hitam', 'Putih', 'Rose Gold'] as $variant) {
                $stock = (int)($request->{'size_' . $variant} ?? 0);
                if ($stock > 0) {
                    ProductSize::create([
                        'product_id' => $product->id,
                        'size'       => $variant,
                        'stock'      => $stock,
                        'color'      => null,
                    ]);
                }
            }
        } else {
            foreach (['S', 'M', 'L', 'XL'] as $size) {
                $stock = (int)($request->{'size_' . $size} ?? 0);
                if ($stock > 0) {
                    ProductSize::create([
                        'product_id' => $product->id,
                        'size'       => $size,
                        'stock'      => $stock,
                        'color'      => null,
                    ]);
                }
            }

            $colorNames  = $request->input('color_name', []);
            $colorStocks = $request->input('color_stock', []);

            foreach ($colorNames as $i => $colorName) {
                $colorName = trim($colorName);
                $stock     = (int)($colorStocks[$i] ?? 0);
                if ($colorName !== '') {
                    ProductSize::create([
                        'product_id' => $product->id,
                        'size'       => null,
                        'stock'      => $stock,
                        'color'      => $colorName,
                    ]);
                }
            }
        }

        return back()->with('success', 'Produk berhasil ditambahkan!');
    }

    public function edit(Product $product)
    {
        $product->load('sizes');
        $categories = Category::all();
        return view('admin.products-edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name'        => 'required|string|max:200',
            'category_id' => 'required|exists:categories,id',
            'price'       => 'required|numeric|min:0',
            'stock'       => 'required|integer|min:0',
        ]);

        $data = [
            'category_id' => $request->category_id,
            'name'        => $request->name,
            'slug'        => str()->slug($request->name),
            'description' => $request->description,
            'price'       => $request->price,
            'stock'       => $request->stock,
            'is_featured' => $request->has('is_featured') ? 1 : 0,
        ];

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        $product->update($data);
      

        $category    = Category::find($request->category_id);
        $isAccessory = $category && $category->slug === 'accessories';

        ProductSize::where('product_id', $product->id)->delete();

        if ($isAccessory) {
            foreach (['Gold', 'Silver', 'Hitam', 'Putih', 'Rose Gold'] as $variant) {
                $stock = (int)($request->{'size_' . $variant} ?? 0);
                if ($stock > 0) {
                    ProductSize::create([
                        'product_id' => $product->id,
                        'size'       => $variant,
                        'stock'      => $stock,
                        'color'      => null,
                    ]);
                }
            }
        } else {
            foreach (['S', 'M', 'L', 'XL'] as $size) {
                $stock = (int)($request->{'size_' . $size} ?? 0);
                if ($stock > 0) {
                    ProductSize::create([
                        'product_id' => $product->id,
                        'size'       => $size,
                        'stock'      => $stock,
                        'color'      => null,
                    ]);
                }
            }

            $colorNames  = $request->input('color_name', []);
            $colorStocks = $request->input('color_stock', []);

            foreach ($colorNames as $i => $colorName) {
                $colorName = trim($colorName);
                $stock     = (int)($colorStocks[$i] ?? 0);
                if ($colorName !== '') {
                    ProductSize::create([
                        'product_id' => $product->id,
                        'size'       => null,
                        'stock'      => $stock,
                        'color'      => $colorName,
                    ]);
                }
            }
        }

        return redirect()->route('admin.products')->with('success', 'Produk berhasil diperbarui!');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return back()->with('success', 'Produk berhasil dihapus!');
    }
}