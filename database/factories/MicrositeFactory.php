<?php

namespace Database\Factories;

use App\Enums\Microsites\MicrositeType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Microsite>
 */
class MicrositeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->company,
            'logo' => $this->faker->imageUrl,
            'category' => $this->faker->word,
            'payment_config' => $this->faker->word,
            'type' => $this->faker->randomElement(MicrositeType::values()),
            'active' => $this->faker->boolean,
        ];
    }
}
