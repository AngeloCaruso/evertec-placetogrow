<?php

declare(strict_types=1);

namespace Tests\Feature\Import;

use App\Actions\DataImports\StoreDataImportsAction;
use App\Enums\Imports\ImportPermissions;
use App\Enums\Microsites\MicrositeCurrency;
use App\Jobs\ProcessDataImport;
use App\Livewire\DataImports\CreateImport;
use App\Models\DataImport;
use App\Models\Microsite;
use App\Models\Payment;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Queue;
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

        Queue::fake();

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

    public function test_store_data_import_action(): void
    {
        $file = UploadedFile::fake()->create('test.csv', 100);

        $data = [
            'entity' => Payment::class,
            'file' => $file,
        ];

        $result = StoreDataImportsAction::exec($data, new DataImport());

        $this->assertInstanceOf(DataImport::class, $result);
        $this->assertDatabaseHas('data_imports', $data);

        Queue::assertPushed(ProcessDataImport::class, function ($job) use ($result) {
            return $job->dataImport->id === $result->id;
        });
    }

    public function test_store_data_import_action_with_invalid_data(): void
    {
        $data = [
            'entity' => 'InvalidEntity',
        ];

        $result = StoreDataImportsAction::exec($data, new DataImport());

        $this->assertNull($result);
        $this->assertDatabaseMissing('data_imports', [
            'entity' => 'InvalidEntity',
        ]);

        Queue::assertNothingPushed();
    }
}
