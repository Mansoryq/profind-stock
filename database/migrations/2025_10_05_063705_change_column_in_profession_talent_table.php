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
            $table->dropUnique(['profession_id', 'talent_id']);
            $table->dropForeign(['profession_id']);
            $table->dropForeign(['talent_id']);

            $table->foreign('profession_id')->references('id')->on('professions')->onDelete('cascade');
            $table->foreign('talent_id')->references('id')->on('talents')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('profession_talent', function (Blueprint $table) {
            //
        });
    }
};
