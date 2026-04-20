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
        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companys')->cascadeOnDelete();
            $table->foreignId('customer_id')->nullable()->constrained('custmers')->nullOnDelete();
            $table->string('status')->default('active');
            $table->timestamps();
            // pos_session_id
            $table->index(['company_id','customer_id']);
            DB::statement("CREATE UNIQUE INDEX unique_active_customer_cart
                ON carts (customer_id)
                WHERE status = 'active' AND customer_id IS NOT NULL;");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('carts');
    }
};
