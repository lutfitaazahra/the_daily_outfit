<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Product;
use App\Models\ProductSize;

class CartController extends Controller
{
    public function index()
    {
        $items    = Cart::with('product')->where('user_id', auth()->id())->get();
        $subtotal = $items->sum(fn($i) => $i->product->price * $i->quantity);
        $shipping = $subtotal >= 50000 ? 0 : 25000;
        $total    = $subtotal + $shipping;
        return view('cart', compact('items', 'subtotal', 'shipping', 'total'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'size'       => 'required',
            'quantity'   => 'required|integer|min:1',
        ]);

        $product = Product::findOrFail($request->product_id);
        $qty     = $request->quantity;
        $color   = $request->color ?? null;

        // Cek stok product_sizes
        $productSize = ProductSize::where('product_id', $product->id)
            ->where('size', $request->size)
            ->when($color, fn($q) => $q->where('color', $color))
            ->first();

        // Kalau ada product_sizes, cek stoknya
        if ($productSize) {
            if ($productSize->stock < $qty) {
                return back()->withErrors(['stock' => 'Stok ukuran/varian ini tidak mencukupi.']);
            }
        } else {
            // Kalau tidak ada product_sizes, cek stok utama
            if ($product->stock < $qty) {
                return back()->withErrors(['stock' => 'Stok produk tidak mencukupi.']);
            }
        }

        // Cek kalau sudah ada di keranjang
        $existing = Cart::where('user_id', auth()->id())
            ->where('product_id', $request->product_id)
            ->where('size', $request->size)
            ->where('color', $color)
            ->first();

        if ($existing) {
            // Cek stok mencukupi untuk tambahan quantity
            $tambahan = $qty;
            if ($productSize) {
                if ($productSize->stock < $tambahan) {
                    return back()->withErrors(['stock' => 'Stok ukuran/varian ini tidak mencukupi.']);
                }
                $productSize->decrement('stock', $tambahan);
            } else {
                if ($product->stock < $tambahan) {
                    return back()->withErrors(['stock' => 'Stok produk tidak mencukupi.']);
                }
            }
            // Kurangi stok utama
            $product->decrement('stock', $tambahan);
            $existing->increment('quantity', $tambahan);
        } else {
            // Kurangi stok
            if ($productSize) {
                $productSize->decrement('stock', $qty);
            }
            $product->decrement('stock', $qty);

            Cart::create([
                'user_id'    => auth()->id(),
                'product_id' => $request->product_id,
                'size'       => $request->size,
                'color'      => $color,
                'quantity'   => $qty,
            ]);
        }

        return back()->with('success', 'Produk berhasil ditambahkan ke keranjang!');
    }

    public function update(Request $request)
    {
        foreach ($request->qty as $cart_id => $qty) {
            $cart = Cart::with('product')->where('id', $cart_id)
                ->where('user_id', auth()->id())
                ->first();

            if (!$cart) continue;

            $qty    = max(1, (int)$qty);
            $selisih = $qty - $cart->quantity; // positif = tambah, negatif = kurang

            if ($selisih === 0) continue;

            $product = $cart->product;

            // Cek stok di product_sizes
            $productSize = ProductSize::where('product_id', $product->id)
                ->where('size', $cart->size)
                ->when($cart->color, fn($q) => $q->where('color', $cart->color))
                ->first();

            if ($selisih > 0) {
                // Tambah quantity → kurangi stok
                if ($productSize && $productSize->stock < $selisih) {
                    continue; // skip kalau stok tidak cukup
                }
                if ($product->stock < $selisih) continue;

                if ($productSize) $productSize->decrement('stock', $selisih);
                $product->decrement('stock', $selisih);
            } else {
                // Kurangi quantity → kembalikan stok
                $kembalikan = abs($selisih);
                if ($productSize) $productSize->increment('stock', $kembalikan);
                $product->increment('stock', $kembalikan);
            }

            $cart->update(['quantity' => $qty]);
        }

        return back();
    }

    public function remove($id)
    {
        $cart = Cart::with('product')->where('id', $id)
            ->where('user_id', auth()->id())
            ->first();

        if ($cart) {
            // Kembalikan stok saat hapus dari keranjang
            $product = $cart->product;

            $productSize = ProductSize::where('product_id', $product->id)
                ->where('size', $cart->size)
                ->when($cart->color, fn($q) => $q->where('color', $cart->color))
                ->first();

            if ($productSize) $productSize->increment('stock', $cart->quantity);
            $product->increment('stock', $cart->quantity);

            $cart->delete();
        }

        return back();
    }
}