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
        Schema::table('products', function (Blueprint $table) {
            $table->integer('free_delivery_quantity')->nullable()->comment('Minimum quantity for free delivery');
            $table->decimal('delivery_fee', 8, 2)->nullable()->comment('Delivery fee when below free delivery quantity');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['free_delivery_quantity', 'delivery_fee']);
        });
    }
};
