<?php

use App\Enums\Gateways\GatewayType;
use App\Enums\Microsites\MicrositeCurrency;
use App\Enums\System\IdTypes;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('microsite_id')->constrained();
            $table->enum('id_type', IdTypes::values());
            $table->string('id_number');
            $table->string('name');
            $table->string('last_name');
            $table->string('email')->index();
            $table->string('phone');

            $table->enum('gateway', GatewayType::values());
            $table->string('gateway_status')->nullable();
            $table->string('reference');
            $table->text('description')->nullable();
            $table->decimal('amount', 8, 2);
            $table->enum('currency', MicrositeCurrency::values());
            $table->string('return_url')->nullable();
            $table->string('payment_url')->nullable();
            $table->dateTime('expires_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
