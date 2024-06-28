<?php

namespace Tests\Feature\Microsites;

use App\Actions\Microsites\UpdateMicrositeAction;
use App\Enums\Microsites\MicrositePermissions;
use App\Enums\Microsites\MicrositeType;
use App\Enums\Roles\RolePermissions;
use App\Enums\Users\UserPermissions;
use App\Livewire\Microsites\EditMicrosite;
use App\Models\Microsite;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class UpdateTest extends TestCase
{
    use RefreshDatabase;

    public $testRole;

    public function setup(): void
    {
        parent::setUp();

        $permission = Permission::firstWhere('name', MicrositePermissions::Update);
        $this->testRole = Role::factory()->create();
        $this->testRole->givePermissionTo($permission);
    }

    public function test_logged_user_can_see_microsites_update_form(): void
    {
        $this->actingAs(User::factory()->create()->assignRole($this->testRole));
        $site = Microsite::factory()->create();

        $response = $this->get(route('microsites.edit', $site));
        $response->assertStatus(200);
    }

    public function test_logged_user_can_see_microsites_update_form_fields(): void
    {
        $this->actingAs(User::factory()->create()->assignRole($this->testRole));
        $site = Microsite::factory()->create();

        Livewire::test(EditMicrosite::class, ['site' => $site])
            ->assertSee('Name')
            ->assertSee('Type')
            ->assertSee('Categories')
            ->assertSee('Currency')
            ->assertSee('Expiration time')
            ->assertSee('Logo')
            ->assertSee('Active')
            ->assertSee('Save');
    }

    public function test_logged_user_can_submit_and_update_microsites(): void
    {
        $this->actingAs(User::factory()->create()->assignRole($this->testRole));
        $now = now();

        Storage::fake('public');

        $site = Microsite::factory()->create();
        $updatedSite = [
            'name' => "Test Microsite updated $now",
            'type' => fake()->randomElement(MicrositeType::values()),
            'categories' => ['updated1', 'updated2'],
            'currency' => fake()->randomElement(MicrositeType::values()),
            'expiration_payment_time' => 123,
            'logo' => UploadedFile::fake()->image('logo.jpg'),
            'active' => fake()->boolean,
        ];

        Livewire::test(EditMicrosite::class, ['site' => $site])
            ->fillForm($updatedSite)
            ->call('save')
            ->assertHasNoFormErrors();

        $site->refresh();

        $this->assertDatabaseHas('microsites', [
            'name' => $updatedSite['name'],
            'type' => $updatedSite['type'],
            'currency' => $updatedSite['currency'],
            'expiration_payment_time' => $updatedSite['expiration_payment_time'],
            'active' => $updatedSite['active'],
        ]);

        $this->assertEquals(implode(',', $updatedSite['categories']), $site->categories);

        Storage::disk('public')->assertExists($site->logo);
    }

    public function test_update_microsites_action(): void
    {
        $now = now();

        $site = Microsite::factory()->create();
        $updatedSite = [
            'name' => "Test Microsite updated $now",
            'type' => fake()->randomElement(MicrositeType::values()),
            'categories' => ['updated1', 'updated2'],
            'currency' => fake()->randomElement(MicrositeType::values()),
            'expiration_payment_time' => 123,
            'active' => fake()->boolean,
        ];

        $site = UpdateMicrositeAction::exec($updatedSite, $site);

        $this->assertDatabaseHas('microsites', [
            'name' => $updatedSite['name'],
            'type' => $updatedSite['type'],
            'currency' => $updatedSite['currency'],
            'expiration_payment_time' => $updatedSite['expiration_payment_time'],
            'active' => $updatedSite['active'],
        ]);

        $this->assertEquals($updatedSite['categories'], $site->categories);
    }
}
