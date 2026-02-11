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
        Schema::create('fields', function (Blueprint $table) {
            $table->id();
            $table->foreignId('template_id')->constrained('templates')->cascadeOnDelete();

            $table->string('key',50)->nullable(false); //internal identifier
            $table->string('label'); // الي هيظهر في UI
            $table->enum('field_type',['string','number','boolean','select','date','datetime'])->nullable(false);
            
            $table->string('unit',50)->nullable();
            $table->boolean('required')->default(true);
            $table->string('default_value')->nullable();
            $table->jsonb('options')->nullable(); //لما يكون نوعه select
            $table->timestamps();

            $table->unique(['template_id','key']);
            $table->index('template_id');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fields');
    }
};
