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
        Schema::create('cart_item_pricing_inputs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pricing_template_input_id')->constrained('pricing_template_inputs')->cascadeOnDelete();
            $table->foreignId('cart_item_id')->constrained('cart_items')->cascadeOnDelete();

            $table->decimal('value_number',15,4)->nullable();
            $table->text('value_text')->nullable();
            $table->boolean('value_boolean')->nullable();
            $table->date('value_date')->nullable();
            $table->dateTime('value_datetime')->nullable();
            $table->jsonb('value_json')->nullable();
            $table->timestamps();

            $table->index(['pricing_template_input_id','cart_item_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cart_item_pricing_inputs');
    }
};
