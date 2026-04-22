<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update any null timestamps with current timestamp
        \DB::table('products')
            ->whereNull('created_at')
            ->update(['created_at' => now(), 'updated_at' => now()]);

        \DB::table('products')
            ->whereNull('updated_at')
            ->update(['updated_at' => now()]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No need to reverse this fix
    }
};
