<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table("test_sessions", function (Blueprint $table) {
            $table->string("promo_code")->nullable()->after("payer_name");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table("test_sessions", function (Blueprint $table) {
            $table->dropColumn("promo_code");
        });
    }
};
