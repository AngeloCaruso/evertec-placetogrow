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
        Schema::table('microsites', function (Blueprint $table) {
            $table->string('primary_color', 8)->nullable();
            $table->string('accent_color', 8)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('microsites', function (Blueprint $table) {
            $table->dropColumn('primary_color');
            $table->dropColumn('accent_color');
        });
    }
};
