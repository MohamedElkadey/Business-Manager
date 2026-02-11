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
        Schema::create('pricing_template_inputs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('template_id')->constrained('templates')->cascadeOnDelete();

            $table->string('key',50)->nullable(false); // width, height, quantity
            $table->string('label');

            $table->enum('input_type', [
                'number', 'string', 'boolean', 'date', 'select'
            ])->default('number');

            $table->string('unit',50);
            $table->jsonb('options')->nullable();
            $table->timestamps();

            $table->unique(['template_id','key']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pricing_template_inputs');
    }
};
