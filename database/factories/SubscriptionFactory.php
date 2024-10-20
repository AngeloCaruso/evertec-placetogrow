<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\Gateways\GatewayType;
use App\Enums\System\IdTypes;
use App\Models\Microsite;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Crypt;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class SubscriptionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'email' => $this->faker->unique()->safeEmail,
            'microsite_id' => 1,
            'subscription_name' => $this->faker->name,
            'amount' => $this->faker->randomFloat(2, 1, 100),
            'currency' => 'USD',
            'description' => $this->faker->sentence,
        ];
    }

    public function withMicrosite(Microsite $site): static
    {
        return $this->state(fn(array $attributes) => [
            'microsite_id' => $site->id,
        ]);
    }

    public function withEmail($email): static
    {
        return $this->state(fn(array $attributes) => [
            'email' => $email,
        ]);
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

    public function requestId($id): static
    {
        return $this->state(fn(array $attributes) => [
            'request_id' => $id,
        ]);
    }

    public function fakeToken(): static
    {
        return $this->state(fn(array $attributes) => [
            'token' => Crypt::encryptString($this->faker->uuid),
            'sub_token' =>  Crypt::encryptString($this->faker->uuid),
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
            'expires_at' => now()->addHours(2),
        ]);
    }

    public function fakeCardData(): static
    {
        return $this->state(fn(array $attributes) => [
            'valid_until' => Crypt::encryptString($this->faker->creditCardExpirationDate->format('m/y')),
            'last_digits' => Crypt::encryptString($this->faker->creditCardNumber),
            'franchise' => Crypt::encryptString($this->faker->creditCardType),
        ]);
    }

    public function fakeUserData(): static
    {
        return $this->state(fn(array $attributes) => [
            'additional_attributes' => [
                'name' => $this->faker->firstName,
                'surname' => $this->faker->lastName,
                'document' => "{$this->faker->randomNumber(8)}",
                'document_type' => $this->faker->randomElement(IdTypes::values()),
            ],
        ]);
    }

    public function approved(): static
    {
        return $this->state(fn(array $attributes) => [
            'gateway_status' => GatewayType::tryFrom($attributes['gateway'])->getGatewayStatuses()::Approved->value,
        ]);
    }
}
