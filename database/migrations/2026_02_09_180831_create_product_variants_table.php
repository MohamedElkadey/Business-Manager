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
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->cascadeOnDelete();

            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();

            $table->string('sku');
            $table->decimal('price_override', 12, 4)->nullable();
            $table->integer('stock_quantity')->default(0);

            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->unique(['product_id','sku']);
            $table->index('product_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_variants');
    }
};
