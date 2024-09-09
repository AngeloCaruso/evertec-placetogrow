<?php

namespace Database\Factories;

use App\Enums\Imports\ImportEntity;
use App\Enums\Imports\ImportStatus;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Http\UploadedFile;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DataImport>
 */
class DataImportFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'entity' => $this->faker->randomElement(ImportEntity::values()),
            'status' => $this->faker->randomElement(ImportStatus::values()),
            'file' =>  UploadedFile::fake()->create('data.csv', 20, 'text/csv'),
            'errors' => $this->faker->words,
        ];
    }
}
