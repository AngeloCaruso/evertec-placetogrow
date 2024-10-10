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
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->text('franchise')->after('sub_token')->nullable();
            $table->text('last_digits')->after('sub_token')->nullable();
            $table->text('valid_until')->after('sub_token')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->dropColumn('franchise');
            $table->dropColumn('last_digits');
            $table->dropColumn('valid_until');
        });
    }
};
