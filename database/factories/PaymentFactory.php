<?php

namespace Database\Factories;

use App\Enums\Gateways\GatewayType;
use App\Enums\Microsites\MicrositeCurrency;
use App\Enums\System\IdTypes;
use App\Models\Microsite;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

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
            'gateway_status' => $this->faker->randomElement($gateway->getGatewayStatuses())->value,
            'reference' => 'PAYMENT-' . $this->faker->unique()->randomNumber(8),
            'description' => $this->faker->sentence,
            'amount' => $this->faker->randomFloat(2, 1000, 100000),
            'currency' => $this->faker->randomElement(MicrositeCurrency::values()),
            'return_url' => 'http://localhost:8001',
            'payment_url' => $this->faker->url,
        ];
    }

    public function withPlacetopayGateway(): static
    {
        return $this->state(fn (array $attributes) => [
            'gateway' => GatewayType::Placetopay->value,
        ]);
    }
}
