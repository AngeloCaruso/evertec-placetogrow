<?php

declare(strict_types=1);

use App\Enums\Microsites\SubscriptionCollectType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('microsites', function (Blueprint $table) {
            $table->after('form_fields', function ($table) {
                $table->boolean('is_paid_monthly')->default(0);
                $table->boolean('is_paid_yearly')->default(0);
                $table->enum('charge_collect', SubscriptionCollectType::values())->nullable();
                $table->json('plans')->nullable();
            });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('microsites', function (Blueprint $table) {
            $table->dropColumn('is_paid_monthly');
            $table->dropColumn('is_paid_yearly');
            $table->dropColumn('charge_collect');
            $table->dropColumn('plans');
        });
    }
};
