<?php

namespace Tests\Feature\Microsites;

use App\Enums\Microsites\MicrositeType;
use App\Livewire\Microsites\EditMicrosite;
use App\Models\Microsite;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class UpdateTest extends TestCase
{
    use RefreshDatabase;

    public function test_logged_user_can_see_microsites_update_form(): void
    {
        $this->actingAs(User::factory()->create());
        $site = Microsite::factory()->create();

        $response = $this->get(route('microsites.edit', $site));
        $response->assertStatus(200);
    }

    public function test_logged_user_can_see_microsites_update_form_fields(): void
    {
        $this->actingAs(User::factory()->create());
        $site = Microsite::factory()->create();

        Livewire::test(EditMicrosite::class, ['site' => $site])
            ->assertSee('Name')
            ->assertSee('Logo')
            ->assertSee('Category')
            ->assertSee('Payment config')
            ->assertSee('Type')
            ->assertSee('Active')
            ->assertSee('Submit');
    }

    public function test_logged_user_can_submit_and_update_microsites(): void
    {
        $this->actingAs(User::factory()->create());
        $now = now();

        Storage::fake('public');

        Livewire::test(EditMicrosite::class, ['site' => Microsite::factory()->create()])
            ->fillForm([
                'name' => "Test Microsite updated $now",
                'category' => 'Test Category updated',
                'type' => fake()->randomElement(MicrositeType::values()),
                'logo' => UploadedFile::fake()->image('logo.jpg'),
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertTrue(Microsite::where('name', "Test Microsite updated $now")->exists());
    }

    public function test_logged_user_can_update_microsites(): void
    {
        $this->actingAs(User::factory()->create());
        $site = Microsite::factory()->create();
        $now = now();
        $response = $this->patch(route('microsites.update', $site), [
            'name' => "Updated Name $now->timestamp",
            'category' => "updated-category $now->timestamp",
        ]);

        $response->assertStatus(302);
        $response->assertRedirect(route('microsites.index'));

        $this->assertDatabaseHas('microsites', [
            'name' => "Updated Name $now->timestamp",
            'category' => "updated-category $now->timestamp",
        ]);
    }
}
