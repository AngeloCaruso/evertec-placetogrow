<?php

namespace Tests\Feature\Microsites;

use App\Actions\Microsites\StoreMicrositeAction;
use App\Enums\Microsites\MicrositePermissions;
use App\Livewire\Microsites\CreateMicrosite;
use App\Models\Microsite;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class CreateTest extends TestCase
{
    use RefreshDatabase;

    public $testRole;

    public function setup(): void
    {
        parent::setUp();

        $permission = Permission::firstWhere('name', MicrositePermissions::Create);
        $this->testRole = Role::factory()->create();
        $this->testRole->givePermissionTo($permission);
    }

    public function test_logged_user_can_access_microsites_creation_form(): void
    {
        $this->actingAs(User::factory()->create()->assignRole($this->testRole));

        $response = $this->get(route('microsites.create'));
        $response->assertStatus(200);
    }

    public function test_logged_user_can_see_microsites_creation_form_fields(): void
    {
        $this->actingAs(User::factory()->create()->assignRole($this->testRole));

        Livewire::test(CreateMicrosite::class)
            ->assertSee('Name')
            ->assertSee('Type')
            ->assertSee('Categories')
            ->assertSee('Currency')
            ->assertSee('Expiration time')
            ->assertSee('Logo')
            ->assertSee('Active')
            ->assertSee('Save');
    }

    public function test_logged_user_can_submit_and_create_microsites(): void
    {
        $this->actingAs(User::factory()->create()->assignRole($this->testRole));

        Storage::fake('public');

        $site = Microsite::factory()->make();

        Livewire::test(CreateMicrosite::class)
            ->fillForm($site->toArray())
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('microsites', [
            'name' => $site->name,
            'type' => $site->type,
            'categories' => '"' . implode(',', $site->categories) . '"',
            'currency' => $site->currency,
            'expiration_payment_time' => $site->expiration_payment_time,
            'active' => $site->active,
        ]);

        $site = Microsite::where('name', $site->name)->first();

        Storage::disk('public')->assertExists($site->logo);
    }

    public function test_create_microsite_action(): void
    {
        $data = Microsite::factory()->make()->toArray();
        $data['categories'] = implode(',', $data['categories']);
        $site = StoreMicrositeAction::exec($data, new Microsite());

        $this->assertDatabaseHas('microsites', [
            'name' => $data['name'],
            'type' => $data['type'],
            'categories' => '"' . $site['categories'] . '"',
            'currency' => $data['currency'],
            'expiration_payment_time' => $data['expiration_payment_time'],
            'active' => $data['active'],
        ]);
    }
}
