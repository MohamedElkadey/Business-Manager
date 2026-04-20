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
        Schema::table('cart_items', function (Blueprint $table) {
            $table->foreignId('product_variant_id')
                ->nullable()
                ->constrained('product_variants')
                ->nullOnDelete();
            DB::statement("
                CREATE UNIQUE INDEX cart_items_unique_product_variant
                ON cart_items (
                    company_id,
                    cart_id,
                    product_id,
                    COALESCE(product_variant_id, 0)
                )
            ");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cart_items', function (Blueprint $table) {
            $table->dropConstrainedForeignId('product_variant_id');
            DB::statement("
                DROP INDEX IF EXISTS cart_items_unique_product_variant
            ");
        });
    }
};
