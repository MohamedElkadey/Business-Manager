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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companys')->cascadeOnDelete();
            $table->foreignId('customer_id')->constrained('custmers')->cascadeOnDelete();
            $table->decimal('total_price',12,2)->default(0);
            $table->string('status')->default('pending');
            $table->jsonb('metadata')->nullable();

            $table->timestamps();

            $table->index(['company_id','customer_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
