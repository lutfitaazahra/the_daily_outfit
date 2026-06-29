<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProductSize;

class ProductSizeSeeder extends Seeder
{
    public function run(): void
    {
        ProductSize::truncate();

        $sizes  = ['S', 'M', 'L', 'XL'];
        $colors = ['Hitam', 'Putih', 'Dusty Pink', 'Navy', 'Sage Green'];

        // ── TOPS (id 1-3) ──────────────────────────────────────────
        // 1 Linen Blend Shirt
        foreach ($sizes as $size) {
            foreach (['Hitam', 'Putih', 'Navy', 'Sage Green'] as $color) {
                ProductSize::create(['product_id' => 1, 'size' => $size, 'color' => $color, 'stock' => 10]);
            }
        }

        // 2 Basic Cotton Tee
        foreach ($sizes as $size) {
            foreach (['Hitam', 'Putih', 'Dusty Pink', 'Sage Green'] as $color) {
                ProductSize::create(['product_id' => 2, 'size' => $size, 'color' => $color, 'stock' => 12]);
            }
        }

        // 3 Soft Knit Blouse
        foreach ($sizes as $size) {
            foreach (['Dusty Pink', 'Hitam', 'Butteryellow', 'Mahogany', 'Biru Denim'] as $color) {
                ProductSize::create(['product_id' => 3, 'size' => $size, 'color' => $color, 'stock' => 10]);
            }
        }

        // ── BOTTOMS (id 4-5) ───────────────────────────────────────
        // 4 Straight Cut Pants
        foreach ($sizes as $size) {
            foreach (['Hitam', 'Putih', 'Navy', 'Coklat'] as $color) {
                ProductSize::create(['product_id' => 4, 'size' => $size, 'color' => $color, 'stock' => 8]);
            }
        }

        // 5 Flowy Mini Skirt
        foreach ($sizes as $size) {
            foreach (['Dusty Pink', 'Putih', 'Hitam', 'Lavender'] as $color) {
                ProductSize::create(['product_id' => 5, 'size' => $size, 'color' => $color, 'stock' => 8]);
            }
        }

        // ── DRESSES (id 6-7) ───────────────────────────────────────
        // 6 Summer Midi Dress
        foreach ($sizes as $size) {
            foreach (['Dusty Pink', 'Putih', 'Sage Green', 'Lavender'] as $color) {
                ProductSize::create(['product_id' => 6, 'size' => $size, 'color' => $color, 'stock' => 7]);
            }
        }

        // 7 Wrap Dress
        foreach ($sizes as $size) {
            foreach (['Hitam', 'Dusty Pink', 'Navy', 'Maroon'] as $color) {
                ProductSize::create(['product_id' => 7, 'size' => $size, 'color' => $color, 'stock' => 7]);
            }
        }

        // ── OUTERWEAR (id 8-9) ─────────────────────────────────────
        // 8 Oversized Cardigan
        foreach ($sizes as $size) {
            foreach (['Krem', 'Hitam', 'Dusty Pink', 'Grey', 'Sage Green'] as $color) {
                ProductSize::create(['product_id' => 8, 'size' => $size, 'color' => $color, 'stock' => 8]);
            }
        }

        // 9 Denim Jacket
        foreach ($sizes as $size) {
            foreach (['Biru Denim', 'Hitam', 'Putih'] as $color) {
                ProductSize::create(['product_id' => 9, 'size' => $size, 'color' => $color, 'stock' => 6]);
            }
        }

        // ── ACCESSORIES (id 10-15) — pakai size sebagai varian ─────
        // 10 Tote Bag Canvas Pink
        foreach (['Pink', 'Putih', 'Hitam'] as $variant) {
            ProductSize::create(['product_id' => 10, 'size' => $variant, 'color' => null, 'stock' => 15]);
        }

        // 11 Kalung Cute Simple
        foreach (['Gold', 'Silver'] as $variant) {
            ProductSize::create(['product_id' => 11, 'size' => $variant, 'color' => null, 'stock' => 20]);
        }

        // 12 Jepit Rambut Mutiara
        foreach (['Putih', 'Pink', 'Hitam'] as $variant) {
            ProductSize::create(['product_id' => 12, 'size' => $variant, 'color' => null, 'stock' => 25]);
        }

        // 13 Jam Tangan Casual Pink
        foreach (['Rose Gold', 'Silver', 'Hitam'] as $variant) {
            ProductSize::create(['product_id' => 13, 'size' => $variant, 'color' => null, 'stock' => 10]);
        }

        // 14 Anting Mutiara Kecil
        foreach (['Gold', 'Silver'] as $variant) {
            ProductSize::create(['product_id' => 14, 'size' => $variant, 'color' => null, 'stock' => 30]);
        }

        // 15 Scrunchie Set 3pcs
        foreach (['Pink', 'Putih', 'Hitam', 'Lavender'] as $variant) {
            ProductSize::create(['product_id' => 15, 'size' => $variant, 'color' => null, 'stock' => 20]);
        }

        // ── Update total stok di tabel products ────────────────────
        $productIds = range(1, 15);
        foreach ($productIds as $id) {
            $total = ProductSize::where('product_id', $id)->sum('stock');
            \App\Models\Product::where('id', $id)->update(['stock' => $total]);
        }

        $this->command->info('ProductSize seeder selesai!');
    }
}