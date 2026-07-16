<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE returns MODIFY status ENUM('pending','approved','rejected','item_received','refunded') DEFAULT 'pending'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE returns MODIFY status ENUM('pending','approved','rejected') DEFAULT 'pending'");
    }
};