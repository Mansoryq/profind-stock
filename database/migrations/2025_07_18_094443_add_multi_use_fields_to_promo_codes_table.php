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
        Schema::table('promo_codes', function (Blueprint $table) {
            $table->integer('max_uses')->default(1)->after('is_active');
            $table->integer('current_uses')->default(0)->after('max_uses');
            $table->timestamp('expires_at')->nullable()->after('current_uses');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('promo_codes', function (Blueprint $table) {
            $table->dropColumn(['max_uses', 'current_uses', 'expires_at']);
        });
    }
};
