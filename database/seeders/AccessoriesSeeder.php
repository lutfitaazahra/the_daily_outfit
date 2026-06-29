<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AccessoriesSeeder extends Seeder
{
    public function run(): void
    {
        $categoryId = DB::table('categories')->where('slug', 'accessories')->value('id');

        if (!$categoryId) {
            $this->command->error('Kategori accessories tidak ditemukan!');
            return;
        }

        $products = [
            [
                'name'        => 'Kalung Cute Simple',
                'slug'        => 'kalung-cute-simple',
                'price'       => 25000,
                'stock'       => 60,
                'is_featured' => 1,
                'image'       => null,
                'description' => 'Kalung Cute Minimalis – Sentuhan Manis untuk Gaya Coquette. Desainnya yang simpel namun menawan cocok untuk gaya coquette, aesthetic, hingga Korean style.',
            ],
            [
                'name'        => 'Jepit Rambut Mutiara',
                'slug'        => 'jepit-rambut-mutiara',
                'price'       => 18000,
                'stock'       => 25,
                'is_featured' => 1,
                'image'       => null,
                'description' => 'Jepit Rambut Motif Mutiara – Manis & Elegan. Dihiasi aksen mutiara yang cantik, cocok untuk berbagai gaya dari casual hingga formal.',
            ],
            [
                'name'        => 'Jam Tangan Casual Pink',
                'slug'        => 'jam-tangan-casual-pink',
                'price'       => 28000,
                'stock'       => 30,
                'is_featured' => 1,
                'image'       => null,
                'description' => 'Jam Tangan Wanita Pink – Elegan, Fashionable & Nyaman Dipakai. Tampil lebih percaya diri dengan desain elegan dan modern.',
            ],
            [
                'name'        => 'Anting Mutiara Kecil',
                'slug'        => 'anting-mutiara-kecil',
                'price'       => 15000,
                'stock'       => 110,
                'is_featured' => 0,
                'image'       => null,
                'description' => 'Anting Mutiara Mini – Ringan & Nyaman Dipakai. Desain sederhana namun elegan, cocok untuk aktivitas sehari-hari maupun acara spesial.',
            ],
            [
                'name'        => 'Scrunchie Set 3pcs',
                'slug'        => 'scrunchie-set-3pcs',
                'price'       => 20000,
                'stock'       => 150,
                'is_featured' => 0,
                'image'       => null,
                'description' => 'Set Ikat Rambut Scrunchie 3 Pcs Warna Pastel (Warna Dikirim Random). Bahan lembut dan nyaman, tidak mudah merusak rambut.',
            ],
        ];

        foreach ($products as $product) {
            $exists = DB::table('products')->where('slug', $product['slug'])->exists();
            if (!$exists) {
                DB::table('products')->insert([
                    'category_id' => $categoryId,
                    'name'        => $product['name'],
                    'slug'        => $product['slug'],
                    'price'       => $product['price'],
                    'stock'       => $product['stock'],
                    'is_featured' => $product['is_featured'],
                    'image'       => $product['image'],
                    'description' => $product['description'],
                    'has_variants'=> 0,
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ]);
                echo "✓ Ditambahkan: {$product['name']}\n";
            } else {
                echo "⚠ Sudah ada: {$product['name']}\n";
            }
        }
    }
}