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
        Schema::create('templates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companys')->cascadeOnDelete();

            $table->string('name',100)->nullable(false);
            $table->text('description')->nullable();

            $table->text('expression')->nullable(); // pricing formula
            $table->integer('pricing_version')->default(1);

            $table->foreignId('parent_template_id')->nullable()->constrained('templates')->nullOnDelete();
            
            $table->boolean('is_active')->default(true);
            $table->softDeletes();
            $table->timestamps();

            $table->index('company_id');
            $table->unique(['company_id','name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('templates');
    }
};
