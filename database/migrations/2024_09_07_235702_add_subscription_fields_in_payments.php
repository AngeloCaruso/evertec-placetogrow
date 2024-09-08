<?php

declare(strict_types=1);

use App\Enums\Payments\PaymentType;
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
            $table->foreignId('subscription_id')->after('microsite_id')->nullable()->constrained();
            $table->enum('payment_type', PaymentType::values())->after('payment_data')->default(PaymentType::Basic->value);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropForeign(['subscription_id']);
            $table->dropColumn('subscription_id');

            $table->dropColumn('payment_type');
        });
    }
};
