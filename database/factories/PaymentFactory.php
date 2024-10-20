<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\Gateways\GatewayType;
use App\Enums\Microsites\MicrositeCurrency;
use App\Enums\Microsites\MicrositeType;
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
        $gateway = GatewayType::Placetopay;
        $site = Microsite::factory()->type(MicrositeType::Donation)->create();

        return [
            'microsite_id' => $site->id,
            'email' => $this->faker->email,
            'gateway' => $gateway->value,
            'description' => $this->faker->sentence,
            'amount' => $this->faker->randomFloat(2, 1000, 100000),
            'currency' => $this->faker->randomElement(MicrositeCurrency::values()),
            'payment_url' => $this->faker->url,
        ];
    }

    public function withPlacetopayGateway(): static
    {
        return $this->state(fn(array $attributes) => [
            'gateway' => GatewayType::Placetopay->value,
        ]);
    }

    public function withDefaultStatus(): static
    {
        return $this->state(fn(array $attributes) => [
            'gateway_status' => GatewayType::tryFrom($attributes['gateway'])->getGatewayStatuses()::Pending->value,
        ]);
    }

    public function withEmail($email): static
    {
        return $this->state(fn(array $attributes) => [
            'email' => $email,
        ]);
    }

    public function fakeReference(): static
    {
        return $this->state(fn(array $attributes) => [
            'reference' => $this->faker->slug,
        ]);
    }

    public function fakeReturnUrl(): static
    {
        return $this->state(fn(array $attributes) => [
            'return_url' => $this->faker->url,
        ]);
    }

    public function fakeExpiresAt(): static
    {
        return $this->state(fn(array $attributes) => [
            'expires_at' => now()->addHours(2)->format('c'),
        ]);
    }

    public function expired(): static
    {
        return $this->state(fn(array $attributes) => [
            'expires_at' => now()->subHours(2)->format('c'),
        ]);
    }

    public function requestId($id): static
    {
        return $this->state(fn(array $attributes) => [
            'request_id' => $id,
        ]);
    }

    public function approved(): static
    {
        return $this->state(fn(array $attributes) => [
            'gateway_status' => GatewayType::tryFrom($attributes['gateway'])->getGatewayStatuses()::Approved->value,
        ]);
    }

    public function rejected(): static
    {
        return $this->state(fn(array $attributes) => [
            'gateway_status' => GatewayType::tryFrom($attributes['gateway'])->getGatewayStatuses()::Rejected->value,
        ]);
    }
}
