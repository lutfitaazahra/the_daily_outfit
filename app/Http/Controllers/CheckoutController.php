<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Notification;

class CheckoutController extends Controller
{
    public function buyNow(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'size'       => 'required',
            'quantity'   => 'required|integer|min:1',
        ]);

        session(['buy_now' => [
            'product_id' => $request->product_id,
            'size'       => $request->size,
            'quantity'   => $request->quantity,
        ]]);

        return redirect()->route('checkout');
    }

    public function index(Request $request)
    {
        if (session()->has('buy_now')) {
            $bn      = session('buy_now');
            $product = Product::findOrFail($bn['product_id']);

            $item = new Cart([
                'product_id' => $product->id,
                'size'       => $bn['size'],
                'quantity'   => $bn['quantity'],
            ]);
            $item->setRelation('product', $product);
            $items = collect([$item]);
        } else {
            $query = Cart::with('product')->where('user_id', auth()->id());

            if ($request->has('items')) {
                $selectedIds = explode(',', $request->items);
                $query->whereIn('id', $selectedIds);
            }

            $items = $query->get();
            if ($items->isEmpty()) return redirect()->route('cart');
        }

        $subtotal = $items->sum(fn($i) => $i->product->price * $i->quantity);
        $shipping = $subtotal >= 300000 ? 0 : 25000;
        $total    = $subtotal + $shipping;
        $user     = auth()->user();

        return view('checkout', compact('items', 'subtotal', 'shipping', 'total', 'user'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'address'        => 'required|string',
            'payment_method' => 'required|in:transfer,cod,ewallet',
        ]);

        if (session()->has('buy_now')) {
            $bn      = session('buy_now');
            $product = Product::findOrFail($bn['product_id']);

            $item = new Cart([
                'product_id' => $product->id,
                'size'       => $bn['size'],
                'quantity'   => $bn['quantity'],
            ]);
            $item->setRelation('product', $product);
            $items = collect([$item]);
            $cartIdsToDelete = [];
        } else {
            $query = Cart::with('product')->where('user_id', auth()->id());

            if ($request->has('items')) {
                $selectedIds = explode(',', $request->items);
                $query->whereIn('id', $selectedIds);
            }

            $items = $query->get();
            if ($items->isEmpty()) return redirect()->route('cart');
            $cartIdsToDelete = $items->pluck('id')->toArray();
        }

        $subtotal = $items->sum(fn($i) => $i->product->price * $i->quantity);
        $shipping = (int) $request->input('shipping_cost', $subtotal >= 300000 ? 0 : 25000);
        $total    = $subtotal + $shipping;

        $order = Order::create([
            'user_id'          => auth()->id(),
            'order_number'     => 'TDO-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -5)),
            'total_amount'     => $total,
            'shipping_cost'    => $shipping,
            'payment_method'   => $request->payment_method,
            'shipping_address' => $request->address,
            'notes'            => $request->notes,
        ]);

        foreach ($items as $item) {
            OrderItem::create([
                'order_id'   => $order->id,
                'product_id' => $item->product_id,
                'size'       => $item->size,
                'quantity'   => $item->quantity,
                'price'      => $item->product->price,
            ]);
        }

        Notification::create([
            'user_id' => auth()->id(),
            'type'    => 'order',
            'title'   => 'Pesanan Berhasil Dibuat',
            'message' => 'Pesanan kamu dengan invoice ' . $order->order_number . ' senilai Rp ' . number_format($total, 0, ',', '.') . ' sedang menunggu pembayaran.',
        ]);

        if (session()->has('buy_now')) {
            session()->forget('buy_now');
        } else {
            Cart::whereIn('id', $cartIdsToDelete)->delete();
        }

        return redirect()->route('payment', $order->id);
    }
}