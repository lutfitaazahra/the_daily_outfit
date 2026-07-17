<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Users
        DB::table('users')->insert([
            [
                'name'       => 'Admin TDO',
                'email'      => 'admin@dailyoutfit.com',
                'password'   => Hash::make('Admin123!'),
                'role'       => 'admin',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name'       => 'Siti Rahayu',
                'email'      => 'siti@email.com',
                'password'   => Hash::make('password'),
                'role'       => 'customer',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Categories
        DB::table('categories')->insert([
            ['name'=>'Tops',        'slug'=>'tops',        'description'=>'Kaos, blouse, kemeja', 'created_at'=>now(), 'updated_at'=>now()],
            ['name'=>'Bottoms',     'slug'=>'bottoms',     'description'=>'Celana dan rok',       'created_at'=>now(), 'updated_at'=>now()],
            ['name'=>'Dresses',     'slug'=>'dresses',     'description'=>'Dress casual & formal','created_at'=>now(), 'updated_at'=>now()],
            ['name'=>'Outerwear',   'slug'=>'outerwear',   'description'=>'Jaket dan cardigan',   'created_at'=>now(), 'updated_at'=>now()],
            ['name'=>'Accessories', 'slug'=>'accessories', 'description'=>'Tas dan aksesoris',    'created_at'=>now(), 'updated_at'=>now()],
        ]);

        // Products
        DB::table('products')->insert([
            ['category_id'=>1,'name'=>'Linen Blend Shirt',  'slug'=>'linen-blend-shirt',  'description'=>'Kemeja linen premium', 'price'=>249000,'stock'=>50,'is_featured'=>1,'created_at'=>now(),'updated_at'=>now()],
            ['category_id'=>1,'name'=>'Basic Cotton Tee',   'slug'=>'basic-cotton-tee',   'description'=>'Kaos katun premium',   'price'=>149000,'stock'=>100,'is_featured'=>1,'created_at'=>now(),'updated_at'=>now()],
            ['category_id'=>1,'name'=>'Soft Knit Blouse',   'slug'=>'soft-knit-blouse',   'description'=>'Blouse rajut lembut',  'price'=>299000,'stock'=>40,'is_featured'=>1,'created_at'=>now(),'updated_at'=>now()],
            ['category_id'=>2,'name'=>'Straight Cut Pants', 'slug'=>'straight-cut-pants', 'description'=>'Celana straight modern','price'=>329000,'stock'=>60,'is_featured'=>1,'created_at'=>now(),'updated_at'=>now()],
            ['category_id'=>2,'name'=>'Flowy Mini Skirt',   'slug'=>'flowy-mini-skirt',   'description'=>'Rok mini feminim',     'price'=>199000,'stock'=>45,'is_featured'=>0,'created_at'=>now(),'updated_at'=>now()],
            ['category_id'=>3,'name'=>'Summer Midi Dress',  'slug'=>'summer-midi-dress',  'description'=>'Dress midi floral',    'price'=>399000,'stock'=>30,'is_featured'=>1,'created_at'=>now(),'updated_at'=>now()],
            ['category_id'=>3,'name'=>'Wrap Dress',         'slug'=>'wrap-dress',         'description'=>'Dress wrap elegan',    'price'=>449000,'stock'=>25,'is_featured'=>1,'created_at'=>now(),'updated_at'=>now()],
            ['category_id'=>4,'name'=>'Oversized Cardigan', 'slug'=>'oversized-cardigan', 'description'=>'Cardigan oversized',   'price'=>359000,'stock'=>35,'is_featured'=>1,'created_at'=>now(),'updated_at'=>now()],
            ['category_id'=>4,'name'=>'Denim Jacket',       'slug'=>'denim-jacket',       'description'=>'Jaket denim klasik',   'price'=>489000,'stock'=>20,'is_featured'=>0,'created_at'=>now(),'updated_at'=>now()],
            ['category_id'=>5,'name'=>'Tote Bag Canvas',    'slug'=>'tote-bag-canvas',    'description'=>'Tas tote kanvas',      'price'=>179000,'stock'=>80,'is_featured'=>0,'created_at'=>now(),'updated_at'=>now()],
        ]);

        // Product sizes
        DB::table('product_sizes')->insert([
            ['product_id'=>1,'size'=>'S','stock'=>10],['product_id'=>1,'size'=>'M','stock'=>15],['product_id'=>1,'size'=>'L','stock'=>15],['product_id'=>1,'size'=>'XL','stock'=>10],
            ['product_id'=>2,'size'=>'S','stock'=>20],['product_id'=>2,'size'=>'M','stock'=>30],['product_id'=>2,'size'=>'L','stock'=>30],['product_id'=>2,'size'=>'XL','stock'=>20],
            ['product_id'=>3,'size'=>'S','stock'=>8], ['product_id'=>3,'size'=>'M','stock'=>16],['product_id'=>3,'size'=>'L','stock'=>10],['product_id'=>3,'size'=>'XL','stock'=>6],
            ['product_id'=>4,'size'=>'S','stock'=>10],['product_id'=>4,'size'=>'M','stock'=>20],['product_id'=>4,'size'=>'L','stock'=>20],['product_id'=>4,'size'=>'XL','stock'=>10],
            ['product_id'=>5,'size'=>'S','stock'=>10],['product_id'=>5,'size'=>'M','stock'=>15],['product_id'=>5,'size'=>'L','stock'=>15],['product_id'=>5,'size'=>'XL','stock'=>5],
            ['product_id'=>6,'size'=>'S','stock'=>8], ['product_id'=>6,'size'=>'M','stock'=>10],['product_id'=>6,'size'=>'L','stock'=>8], ['product_id'=>6,'size'=>'XL','stock'=>4],
            ['product_id'=>7,'size'=>'S','stock'=>6], ['product_id'=>7,'size'=>'M','stock'=>10],['product_id'=>7,'size'=>'L','stock'=>7], ['product_id'=>7,'size'=>'XL','stock'=>2],
            ['product_id'=>8,'size'=>'S','stock'=>8], ['product_id'=>8,'size'=>'M','stock'=>12],['product_id'=>8,'size'=>'L','stock'=>10],['product_id'=>8,'size'=>'XL','stock'=>5],
            ['product_id'=>9,'size'=>'S','stock'=>5], ['product_id'=>9,'size'=>'M','stock'=>8], ['product_id'=>9,'size'=>'L','stock'=>5], ['product_id'=>9,'size'=>'XL','stock'=>2],
            ['product_id'=>10,'size'=>'ONE SIZE','stock'=>80],
        ]);
    }
}