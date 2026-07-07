<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('product_sizes')
            ->where('size', 'Free Size')
            ->update(['size' => 'All Size']);
    }

    public function down(): void
    {
        DB::table('product_sizes')
            ->where('size', 'All Size')
            ->update(['size' => 'Free Size']);
    }
};