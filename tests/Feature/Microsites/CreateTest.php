<?php

namespace Tests\Feature\Microsites;

use App\Enums\Microsites\MicrositeType;
use App\Livewire\Microsites\CreateMicrosite;
use App\Models\Microsite;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class CreateTest extends TestCase
{
    use RefreshDatabase;

    public function test_logged_user_can_access_microsites_creation_form(): void
    {
        $this->actingAs(User::factory()->create());

        $response = $this->get(route('microsites.create'));
        $response->assertStatus(200);
    }

    public function test_logged_user_can_see_microsites_creation_form_fields(): void
    {
        $this->actingAs(User::factory()->create());

        Livewire::test(CreateMicrosite::class)
            ->assertSee('Name')
            ->assertSee('Logo')
            ->assertSee('Category')
            ->assertSee('Payment config')
            ->assertSee('Type')
            ->assertSee('Active')
            ->assertSee('Submit');
    }

    public function test_logged_user_can_submit_and_create_microsites(): void
    {
        $this->actingAs(User::factory()->create());
        $now = now();

        Storage::fake('public');

        Livewire::test(CreateMicrosite::class)
            ->fillForm([
                'name' => "Test Microsite $now",
                'category' => 'Test Category',
                'type' => fake()->randomElement(MicrositeType::values()),
                'logo' => UploadedFile::fake()->image('logo.jpg'),
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertTrue(Microsite::where('name', "Test Microsite $now")->exists());
    }

    public function test_logged_user_can_create_microsites(): void
    {
        $this->actingAs(User::factory()->create());

        $site = Microsite::factory()->make()->toArray();
        $response = $this->post(route('microsites.store'), $site);

        $response->assertStatus(302);
        $response->assertRedirect(route('microsites.index'));
    }
}
