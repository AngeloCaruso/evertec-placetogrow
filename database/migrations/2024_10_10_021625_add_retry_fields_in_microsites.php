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
            $table->unsignedInteger('payment_retries')->after('expiration_payment_time')->nullable();
            $table->unsignedInteger('payment_retry_interval')->after('expiration_payment_time')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('microsites', function (Blueprint $table) {
            $table->dropColumn('payment_retries');
            $table->dropColumn('payment_retry_interval');
        });
    }
};
