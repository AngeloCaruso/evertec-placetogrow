<?php

namespace Database\Factories;

use App\Enums\Gateways\GatewayType;
use App\Enums\Microsites\MicrositeCurrency;
use App\Enums\System\IdTypes;
use App\Models\Microsite;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Payment>
 */
class PaymentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // $gateway = $this->faker->randomElement(GatewayType::cases());
        $gateway = GatewayType::Placetopay;
        $site = Microsite::factory()->create();

        return [
            'microsite_id' => $site->id,
            'id_type' => $this->faker->randomElement(IdTypes::values()),
            'id_number' => $this->faker->randomNumber(8),
            'name' => $this->faker->name,
            'last_name' => $this->faker->lastName,
            'email' => $this->faker->email,
            'phone' => $this->faker->phoneNumber,

            'gateway' => $gateway->value,
            'description' => $this->faker->sentence,
            'amount' => $this->faker->randomFloat(2, 1000, 100000),
            'currency' => $this->faker->randomElement(MicrositeCurrency::values()),
            'payment_url' => $this->faker->url,
        ];
    }

    public function withPlacetopayGateway(): static
    {
        return $this->state(fn (array $attributes) => [
            'gateway' => GatewayType::Placetopay->value,
        ]);
    }

    public function fakeReference(): static
    {
        return $this->state(fn (array $attributes) => [
            'reference' => $this->faker->slug,
        ]);
    }

    public function fakeReturnUrl(): static
    {
        return $this->state(fn (array $attributes) => [
            'return_url' => $this->faker->url,
        ]);
    }

    public function fakeExpiresAt(): static
    {
        return $this->state(fn (array $attributes) => [
            'expires_at' => now()->addHours(2)->format('c'),
        ]);
    }
}
