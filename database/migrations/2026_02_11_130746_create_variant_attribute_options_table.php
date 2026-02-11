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
        Schema::create('variant_attribute_options', function (Blueprint $table) {
            $table->id();

            $table->foreignId('variant_attribute_id')
                ->constrained('variant_attributes')
                ->cascadeOnDelete();

            $table->string('value'); // Red, Blue, M, L

            $table->timestamps();

            $table->unique(['variant_attribute_id', 'value']);

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('variant_attribute_options');
    }
};
