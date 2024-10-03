<?php

declare(strict_types=1);

use App\Enums\Gateways\GatewayType;
use App\Enums\Microsites\MicrositeCurrency;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->string('email')->index();
            $table->foreignId('microsite_id')->constrained();
            $table->string('subscription_name');
            $table->decimal('amount', 8, 2)->nullable();
            $table->enum('currency', MicrositeCurrency::values())->nullable();
            $table->string('reference');
            $table->string('description', 500);
            $table->enum('gateway', GatewayType::values());
            $table->string('gateway_status');
            $table->string('token')->nullable();
            $table->string('sub_token')->nullable();
            $table->string('return_url')->nullable();
            $table->string('payment_url')->nullable();
            $table->string('request_id')->nullable();
            $table->dateTime('expires_at')->nullable();
            $table->boolean('active')->default(true);
            $table->json('additional_attributes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};
