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
        Schema::table('profession_talent', function (Blueprint $table) {
            $table->integer('talent_id')->nullable()->change();
            $table->integer('intellect_id')->nullable();

            $table->foreign('intellect_id')->references('id')->on('intellects')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('profession_talent', function (Blueprint $table) {

        });
    }
};
