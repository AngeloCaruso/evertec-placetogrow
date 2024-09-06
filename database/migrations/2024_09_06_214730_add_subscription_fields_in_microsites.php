<?php

use App\Enums\Microsites\SubscriptionCollectType;
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
            $table->after('form_fields', function ($table) {
                $table->text('plan_features')->nullable();
                $table->boolean('is_paid_monthtly')->default(0);
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
            $table->dropColumn('plan_features');
            $table->dropColumn('is_paid_monthtly');
            $table->dropColumn('is_paid_yearly');
            $table->dropColumn('charge_collect');
            $table->dropColumn('plans');
        });
    }
};
