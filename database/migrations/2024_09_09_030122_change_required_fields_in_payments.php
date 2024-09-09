<?php

declare(strict_types=1);

use App\Enums\Gateways\GatewayType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->enum('gateway', GatewayType::values())->nullable()->change();
            $table->dateTime('expires_at')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->enum('gateway', GatewayType::values())->nullable(false)->change();
            $table->dateTime('expires_at')->nullable(false)->change();
        });
    }
};
