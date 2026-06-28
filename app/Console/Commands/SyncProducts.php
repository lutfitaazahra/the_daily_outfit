<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SyncProducts extends Command
{
    protected $signature = 'products:sync';
    protected $description = 'Update/insert data produk sesuai data terbaru dari localhost (aman dijalankan berkali-kali)';

    public function handle(): void
    {
        $products = [
            ['category_id'=>1,'name'=>'Linen Blend Shirt','slug'=>'linen-blend-shirt','price'=>68000,'stock'=>100,'is_featured'=>1,'description'=>"## Kemeja Linen Premium – Nyaman, Elegan & Stylish\r\n\r\nTampil lebih percaya diri dengan **Kemeja Linen Premium** yang memadukan desain modern dan bahan berkualitas.\r\n\r\n### Keunggulan Produk:\r\n\r\n* Bahan linen premium, lembut dan nyaman dipakai.\r\n* Adem dan ringan, cocok untuk penggunaan sehari-hari.\r\n* Desain simpel, elegan, dan timeless.\r\n\r\nLengkapi koleksi fashion Anda dengan **Kemeja Linen Premium**.", 'image'=>'products/50bQOvkKjM8kpsaamjgu5qHYtrrxJvEOpcq6YDh5.jpg'],

            ['category_id'=>1,'name'=>'Basic Cotton Tee','slug'=>'basic-cotton-tee','price'=>55000,'stock'=>99,'is_featured'=>1,'description'=>"## Kaos Katun Premium – Lembut, Nyaman & Stylish\r\n\r\nNikmati kenyamanan maksimal dengan **Kaos Katun Premium** yang dibuat dari bahan katun berkualitas.\r\n\r\nPilih **Kaos Katun Premium** untuk tampilan yang simpel, nyaman, dan tetap stylish.", 'image'=>'products/GsL6hLSGuDn2RwAPf7F6ho41U44ogWOjNdJ5offC.webp'],

            ['category_id'=>1,'name'=>'Soft Knit Blouse','slug'=>'soft-knit-blouse','price'=>60000,'stock'=>40,'is_featured'=>1,'description'=>"## Soft Knit Blouse – Lembut, Elegan & Nyaman Dipakai\r\n\r\nTampil anggun dan stylish dengan **Soft Knit Blouse** yang dibuat dari bahan knit lembut, elastis, dan nyaman.\r\n\r\nLengkapi penampilan Anda dengan **Soft Knit Blouse**.", 'image'=>'products/rjRSwLn2FnFDnJMKVj7P7uO9oyj730tUx6xq0jX1.webp'],

            ['category_id'=>2,'name'=>'Straight Cut Pants','slug'=>'straight-cut-pants','price'=>150000,'stock'=>60,'is_featured'=>1,'description'=>"## Straight Cut Pants – Simpel, Nyaman & Stylish\r\n\r\nLengkapi gaya sehari-hari dengan **Straight Cut Pants** yang memiliki potongan lurus.\r\n\r\nDapatkan **Straight Cut Pants** untuk melengkapi koleksi fashion Anda.", 'image'=>'products/meXv9zSY4zMWAAUeoxqnnngbXlU5XYMZnFLVYGMY.jpg'],

            ['category_id'=>2,'name'=>'Flowy Mini Skirt','slug'=>'flowy-mini-skirt','price'=>78000,'stock'=>45,'is_featured'=>0,'description'=>"## Flowy Mini Skirt – Manis, Feminin & Nyaman Dipakai\r\n\r\nTampil lebih feminin dengan **Flowy Mini Skirt** yang memiliki desain flowy.\r\n\r\nLengkapi koleksi fashion Anda dengan **Flowy Mini Skirt**.", 'image'=>'products/nbliRYUNouLRx74hbXS9iTohMBEoptv7NuvEz8wC.avif'],

            ['category_id'=>3,'name'=>'Summer Midi Dress','slug'=>'summer-midi-dress','price'=>89000,'stock'=>30,'is_featured'=>1,'description'=>"## Summer Midi Dress – Anggun, Nyaman & Elegan\r\n\r\nTampil cantik dan effortless dengan **Summer Midi Dress** yang dirancang untuk memberikan kenyamanan.\r\n\r\nLengkapi koleksi fashion Anda dengan **Summer Midi Dress**.", 'image'=>'products/17xVaLtQihLYzqdxWtgnmyFjw9glwOkhDKUIrpiI.jpg'],

            ['category_id'=>3,'name'=>'Wrap Dress','slug'=>'wrap-dress','price'=>70000,'stock'=>25,'is_featured'=>1,'description'=>"## Dress Wrap Elegan – Feminin, Anggun & Nyaman Dipakai\r\n\r\nTampil lebih percaya diri dengan **Dress Wrap Elegan** yang dirancang untuk memberikan siluet yang cantik.\r\n\r\nLengkapi koleksi fashion Anda dengan **Dress Wrap Elegan**.", 'image'=>'products/Akmz24sKQ212pGPQq7HSjDOlXiS9kil8Ey862WCv.avif'],

            ['category_id'=>4,'name'=>'Oversized Cardigan','slug'=>'oversized-cardigan','price'=>84000,'stock'=>35,'is_featured'=>1,'description'=>"## Cardigan Oversized – Hangat, Nyaman & Stylish\r\n\r\nTampil modis dengan **Cardigan Oversized** yang menggabungkan kenyamanan dan gaya.\r\n\r\nLengkapi koleksi fashion Anda dengan **Cardigan Oversized**.", 'image'=>'products/AmVspxVURRDvtt1Djlp1u371QtPm1CHkY2c0Sx8v.webp'],

            ['category_id'=>4,'name'=>'Denim Jacket','slug'=>'denim-jacket','price'=>115000,'stock'=>20,'is_featured'=>0,'description'=>"## Jaket Denim Klasik – Trendy, Tahan Lama & Mudah Dipadukan\r\n\r\nLengkapi gaya fashion Anda dengan **Jaket Denim Klasik** yang hadir dengan desain timeless.\r\n\r\nTampil lebih percaya diri dengan **Jaket Denim Klasik**.", 'image'=>'products/0GOhOQk5omXS7CMXxQLmjTQl6gRn6CrkvLDwdZuU.jpg'],

            ['category_id'=>5,'name'=>'Tote Bag Canvas Pink','slug'=>'tote-bag-canvas-pink','price'=>35000,'stock'=>81,'is_featured'=>0,'description'=>"## Tas Tote Kanvas Soft Pink – Cute, Praktis & Cocok untuk Daily\r\n\r\nTampil manis setiap hari dengan **Tas Tote Kanvas Soft Pink** yang memadukan desain minimalis.\r\n\r\nLengkapi gaya sehari-harimu dengan **Tas Tote Kanvas Soft Pink**.", 'image'=>'products/6vbPkpI1tQ3QkPVKfjUxuBA8GLheDJJ3SAfu5DyI.jpg'],

            ['category_id'=>5,'name'=>'Kalung Cute Simple','slug'=>'kalung-cute-simple','price'=>25000,'stock'=>50,'is_featured'=>1,'description'=>"Kalung Cute Minimalis – Sentuhan Manis untuk Gaya Coquette\r\n\r\nLengkapi penampilanmu dengan kalung cute minimalis yang menghadirkan kesan manis, feminin, dan elegan.\r\n\r\nTambahkan Kalung Cute Minimalis ke koleksimu sekarang!", 'image'=>'products/mNt72n79oSqg6zcohBgzvyyJD238V5EcA6TKV2uS.jpg'],

            ['category_id'=>5,'name'=>'Jepit Rambut Mutiara','slug'=>'jepit-rambut-mutiara','price'=>18000,'stock'=>60,'is_featured'=>1,'description'=>"Jepit Rambut Motif Mutiara – Manis & Elegan\r\n\r\nPercantik penampilanmu dengan Jepit Rambut Motif Mutiara yang menghadirkan sentuhan manis, elegan, dan feminin.\r\n\r\nLengkapi koleksi aksesori rambutmu dengan Jepit Rambut Motif Mutiara.", 'image'=>'products/Mo06NnAbqgfKVaGjHI9NxCQGqiABX5s8n91mGWrr.webp'],

            ['category_id'=>5,'name'=>'Jam Tangan Casual Pink','slug'=>'jam-tangan-casual-pink','price'=>28000,'stock'=>40,'is_featured'=>1,'description'=>"Jam Tangan Wanita Pink – Elegan, Fashionable & Nyaman Dipakai\r\n\r\nTampil lebih percaya diri dengan jam tangan wanita warna pink yang memadukan desain elegan dan modern.\r\n\r\nTambahkan jam tangan warna pink ini ke koleksi Anda sekarang!", 'image'=>'products/c2oz9K72UE95UEcqKnnIv7AMilntk5gWI6yyP2JF.webp'],

            ['category_id'=>5,'name'=>'Anting Mutiara Kecil','slug'=>'anting-mutiara-kecil','price'=>15000,'stock'=>70,'is_featured'=>0,'description'=>"Anting Mutiara Mini – Ringan & Nyaman Dipakai\r\n\r\nTampil anggun dengan Anting Mutiara Mini yang memiliki desain sederhana namun tetap elegan.\r\n\r\nLengkapi koleksi aksesori favoritmu dengan Anting Mutiara Mini.", 'image'=>'products/2Gfpbo2eUUlqIMRR0oUWgGY7rBvSnXd8qE1Z7xG3.webp'],

            ['category_id'=>5,'name'=>'Scrunchie Set 3pcs','slug'=>'scrunchie-set-3pcs','price'=>20000,'stock'=>80,'is_featured'=>0,'description'=>"Set Ikat Rambut Scrunchie 3 Pcs Warna Pastel (Warna Dikirim Random)\r\n\r\nLengkapi koleksi aksesori rambutmu dengan Set Ikat Rambut Scrunchie 3 Pcs berwarna pastel yang cantik.\r\n\r\nTambahkan Set Ikat Rambut Scrunchie 3 Pcs ke koleksimu sekarang!", 'image'=>'products/tlbTFoLq4GJMkcwH0mTkA12pDqTKyKvMHVvZZ0VZ.jpg'],
        ];

        $clothingSizes = ['S', 'M', 'L', 'XL'];

        foreach ($products as $p) {
            // Cek apakah produk sudah ada
            $existing = DB::table('products')->where('slug', $p['slug'])->first();

            DB::table('products')->updateOrInsert(
                ['slug' => $p['slug']],
                [
                    'category_id'  => $p['category_id'],
                    'name'         => $p['name'],
                    'description'  => $p['description'],
                    'price'        => $p['price'],
                    'stock'        => $p['stock'],
                    // Kalau sudah ada gambar di DB, pakai yang lama. Kalau belum, pakai dari command
                    'image'        => ($existing && $existing->image) ? $existing->image : $p['image'],
                    'is_featured'  => $p['is_featured'],
                    'updated_at'   => now(),
                    'created_at'   => now(),
                ]
            );

            $this->info("Synced product: {$p['name']}");

            // Ambil id produk
            $productId = DB::table('products')->where('slug', $p['slug'])->value('id');

            // Sync product_sizes
            if ($p['category_id'] <= 4) {
                // Pakaian: S, M, L, XL
                $stockPerSize = intdiv($p['stock'], count($clothingSizes));
                foreach ($clothingSizes as $size) {
                    DB::table('product_sizes')->updateOrInsert(
                        ['product_id' => $productId, 'size' => $size],
                        [
                            'color' => null,
                            'stock' => $stockPerSize,
                        ]
                    );
                }
                $this->info("  → Sizes synced: S, M, L, XL (stock @{$stockPerSize})");
            } else {
                // Aksesoris: One Size
                DB::table('product_sizes')->updateOrInsert(
                    ['product_id' => $productId, 'size' => 'One Size'],
                    [
                        'color' => null,
                        'stock' => $p['stock'],
                    ]
                );
                $this->info("  → Size synced: One Size (stock {$p['stock']})");
            }
        }

        $this->info('Selesai! Semua produk & ukuran berhasil di-sync.');
    }
}