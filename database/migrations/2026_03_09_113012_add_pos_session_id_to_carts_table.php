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
        Schema::table('carts', function (Blueprint $table) {
            //
            $table->foreignId('pos_session_id')->nullable()->constrained('pos_sessions')->cascadeOnDelete();
            $table->index(['company_id', 'pos_session_id']);
            DB::statement("CREATE UNIQUE INDEX unique_active_pos_cart
                ON carts (pos_session_id)
                WHERE status = 'active' AND pos_session_id IS NOT NULL;");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('carts', function (Blueprint $table) {
            //
            $table->dropConstrainedForeignId('pos_session_id');
        });
    }
};
