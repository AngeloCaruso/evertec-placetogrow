<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\System\DefaultRoles;
use Illuminate\Database\Eloquent\Factories\Factory;

class RoleFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => "{$this->faker->unique()->word} {$this->faker->unique()->word}",
            'guard_name' => 'web',
        ];
    }

    public function admin(): self
    {
        return $this->state([
            'name' => DefaultRoles::Admin,
        ]);
    }
}
