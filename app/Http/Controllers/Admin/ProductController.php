<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductSize;
use App\Helpers\CloudinaryHelper;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    private array $accessoryVariants = ['Gold', 'Silver', 'Rose Gold'];

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
        ]);

        $image = '';
        if ($request->hasFile('image')) {
            $image = CloudinaryHelper::upload($request->file('image')->getRealPath(), 'products');
        }

        $category    = Category::find($request->category_id);
        $isAccessory = $category && $category->slug === 'accessories';
        $hasVariants = $isAccessory && $request->has('accessory_has_variants');

        $product = Product::create([
            'category_id'  => $request->category_id,
            'name'         => $request->name,
            'slug'         => str()->slug($request->name),
            'description'  => $request->description,
            'price'        => $request->price,
            'stock'        => 0,
            'image'        => $image,
            'is_featured'  => $request->has('is_featured') ? 1 : 0,
            'has_variants' => $hasVariants,
        ]);

        $totalStock = $this->saveStockRows($request, $product, $isAccessory, $hasVariants);

        $product->update(['stock' => $totalStock]);

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
        ]);

        $category    = Category::find($request->category_id);
        $isAccessory = $category && $category->slug === 'accessories';
        $hasVariants = $isAccessory && $request->has('accessory_has_variants');

        $data = [
            'category_id'  => $request->category_id,
            'name'         => $request->name,
            'slug'         => str()->slug($request->name),
            'description'  => $request->description,
            'price'        => $request->price,
            'is_featured'  => $request->has('is_featured') ? 1 : 0,
            'has_variants' => $hasVariants,
        ];

        if ($request->hasFile('image')) {
            $data['image'] = CloudinaryHelper::upload($request->file('image')->getRealPath(), 'products');
        }

        $product->update($data);

        ProductSize::where('product_id', $product->id)->delete();

        $totalStock = $this->saveStockRows($request, $product, $isAccessory, $hasVariants);

        $product->update(['stock' => $totalStock]);

        return redirect()->route('admin.products')->with('success', 'Produk berhasil diperbarui!');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return back()->with('success', 'Produk berhasil dihapus!');
    }

    /**
     * Simpan baris ProductSize sesuai tipe produk, return total stok.
     */
    private function saveStockRows(Request $request, Product $product, bool $isAccessory, bool $hasVariants): int
    {
        $totalStock = 0;

        if ($isAccessory && $hasVariants) {
            // Aksesoris dengan varian warna (misal: perhiasan)
            foreach ($this->accessoryVariants as $variant) {
                $stock = (int)($request->{'variant_' . str_replace(' ', '_', $variant)} ?? 0);
                if ($stock > 0) {
                    ProductSize::create([
                        'product_id' => $product->id,
                        'size'       => $variant,
                        'color'      => null,
                        'stock'      => $stock,
                    ]);
                    $totalStock += $stock;
                }
            }
        } elseif ($isAccessory) {
            // Aksesoris fix warna Pink, tanpa varian
            $stock = (int)($request->accessory_stock ?? 0);

            ProductSize::create([
                'product_id' => $product->id,
                'size'       => null,
                'color'      => 'Pink',
                'stock'      => $stock,
            ]);

            $totalStock = $stock;
        } else {
            // Baju: kombinasi size + color
            $sizes       = array_filter($request->input('combo_size', []), fn($v) => $v !== '');
            $colors      = $request->input('combo_color', []);
            $comboStocks = $request->input('combo_stock', []);

            foreach ($sizes as $i => $size) {
                $color = trim($colors[$i] ?? '');
                $stock = (int)($comboStocks[$i] ?? 0);

                ProductSize::create([
                    'product_id' => $product->id,
                    'size'       => $size ?: null,
                    'color'      => $color ?: null,
                    'stock'      => $stock,
                ]);
                $totalStock += $stock;
            }
        }

        return $totalStock;
    }
}