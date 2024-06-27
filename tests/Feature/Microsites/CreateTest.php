<?php

namespace Tests\Feature\Microsites;

use App\Actions\Microsites\StoreMicrositeAction;
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

        Storage::fake('public');
        $logo = UploadedFile::fake()->image('logo.png');

        $site = Microsite::factory()->make([
            'logo' => $logo,
        ]);

        Livewire::test(CreateMicrosite::class)
            ->fillForm($site->toArray())
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('microsites', [
            'name' => $site->name,
            'category' => $site->category,
            'payment_config' => $site->payment_config,
            'type' => $site->type,
            'active' => $site->active,
        ]);

        $site = Microsite::where('name', $site->name)->first();

        Storage::disk('public')->assertExists($site->logo);
    }

    public function test_create_microsite_action(): void
    {
        $data = Microsite::factory()->make()->toArray();
        $site = StoreMicrositeAction::exec($data, new Microsite());

        $this->assertDatabaseHas('microsites', [
            'name' => $data['name'],
            'category' => $data['category'],
            'payment_config' => $data['payment_config'],
            'type' => $data['type'],
            'active' => $data['active'],
        ]);
    }
}
