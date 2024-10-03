<?php

declare(strict_types=1);

namespace Tests\Feature\Import;

use App\Enums\Imports\ImportPermissions;
use App\Livewire\DataImports\CreateImport;
use App\Models\DataImport;
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

    public Role $testRole;

    public function setup(): void
    {
        parent::setUp();

        $permission = Permission::firstWhere('name', ImportPermissions::Create);
        $this->testRole = Role::factory()->create();
        $this->testRole->givePermissionTo($permission);
    }

    public function test_logged_user_can_create_imports(): void
    {
        $this->actingAs(User::factory()->create()->assignRole($this->testRole));

        $response = $this->get(route('data-imports.create'));
        $response->assertStatus(200);
    }

    public function test_logged_user_can_see_data_imports_creation_form_fields(): void
    {
        $this->actingAs(User::factory()->create()->assignRole($this->testRole));

        Livewire::test(CreateImport::class)
            ->assertSee('Entity')
            ->assertSee('File');
    }

    public function test_logged_user_can_submit_and_create_an_import(): void
    {
        $this->actingAs(User::factory()->create()->assignRole($this->testRole));

        Storage::fake('public');

        $import = DataImport::factory()->make();

        Livewire::test(CreateImport::class)
            ->fillForm($import->toArray())
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('data_imports', [
            'entity' => $import->entity,
        ]);

        $import = DataImport::where('entity', $import->entity)->first();
        Storage::disk('public')->assertExists($import->file);
    }
}
