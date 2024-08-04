<?php

namespace Database\Factories;

use App\Enums\Microsites\MicrositeCurrency;
use App\Enums\Microsites\MicrositeType;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class MicrositeFactory extends Factory
{
    public function definition(): array
    {
        $name = $this->faker->company;
        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'type' => $this->faker->randomElement(MicrositeType::values()),
            'categories' => implode(',', $this->faker->words(5)),
            'currency' => $this->faker->randomElement(MicrositeCurrency::values()),
            'expiration_payment_time' => 1,
            'logo' => UploadedFile::fake()->image('logo.png'),
            'active' => $this->faker->boolean,
        ];
    }

    public function active(): MicrositeFactory
    {
        return $this->state([
            'active' => true,
        ]);
    }

    public function inactive(): MicrositeFactory
    {
        return $this->state([
            'active' => false,
        ]);
    }

    public function type(MicrositeType $type): MicrositeFactory
    {
        return $this->state([
            'type' => $type->value,
        ]);
    }
}
