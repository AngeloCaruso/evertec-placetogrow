<?php

declare(strict_types=1);

use App\Enums\Microsites\MicrositeType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('microsites', function (Blueprint $table) {
            $table->id();
            $table->string('name', 60);
            $table->string('logo');
            $table->json('categories');
            $table->string('currency', 5);
            $table->unsignedInteger('expiration_payment_time');
            $table->enum('type', MicrositeType::values());
            $table->boolean('active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('microsites');
    }
};
