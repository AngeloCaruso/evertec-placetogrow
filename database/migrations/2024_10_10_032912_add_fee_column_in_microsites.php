<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('microsites', function (Blueprint $table) {
            $table->decimal('penalty_fee', 8, 2)->after('expiration_payment_time')->nullable();
            $table->boolean('penalty_is_percentage')->after('penalty_fee')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('microsites', function (Blueprint $table) {
            $table->dropColumn('penalty_fee');
            $table->dropColumn('penalty_is_percentage');
        });
    }
};
