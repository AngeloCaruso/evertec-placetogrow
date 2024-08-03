<?php

namespace Database\Factories;

use App\Enums\Acl\ControllableTypes;
use App\Enums\System\AccessRules;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class AccessControlListFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => 1,
            'rule' => $this->faker->randomElement(AccessRules::values()),
            'controllable_type' => $this->faker->randomElement(ControllableTypes::values()),
            'controllable_id' => 1,
        ];
    }

    public function user(User $user): static
    {
        return $this->state(fn (array $attributes) => [
            'user_id' => $user->id,
        ]);
    }

    public function rule(string $rule): static
    {
        return $this->state(fn (array $attributes) => [
            'rule' => $rule,
        ]);
    }

    public function controllableType(string $controllableType): static
    {
        return $this->state(fn (array $attributes) => [
            'controllable_type' => $controllableType,
        ]);
    }

    public function controllableId(int $controllableId): static
    {
        return $this->state(fn (array $attributes) => [
            'controllable_id' => $controllableId,
        ]);
    }
}
