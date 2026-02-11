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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companys')->cascadeOnDelete();
            $table->foreignId('template_id')->constrained('templates')->cascadeOnDelete();

            $table->string('name');
            $table->string('sku');
            $table->string('description')->nullable();
            
            $table->decimal('base_rate',12,2)->default(0); // Base pricing reference

            $table->boolean('is_active')->default(true);
            $table->jsonb('extra')->nullable();

            $table->timestamps();

            $table->index('company_id');
            $table->unique(['company_id', 'sku']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
