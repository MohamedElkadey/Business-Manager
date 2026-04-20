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
        Schema::create('pos_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained( 'companys')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->uuid('device_uuid')->nullable(false);
            $table->string('status',20)->default('active');
            $table->timestamp('opened_at')->default(now());
            $table->timestamp('closed_at');
            $table->timestamp('last_activity_at');
            $table->timestamps();

            $table->index(['company_id' , 'user_id']);
            $table->index(['company_id' , 'device_uuid']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pos_sessions');
    }
};
