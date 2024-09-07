<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
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
            $table->string('price');
            $table->string('currency');
            $table->string('reference');
            $table->string('status');
            $table->string('request_id')->nullable();
            $table->string('token')->nullable();
            $table->string('sub_token')->nullable();
            $table->dateTime('expires_at')->nullable();
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
