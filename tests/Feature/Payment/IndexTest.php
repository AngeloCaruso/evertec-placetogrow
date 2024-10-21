<?php

declare(strict_types=1);

namespace Tests\Feature\Payment;

use App\Actions\AccessControlList\StoreAclAction;
use App\Actions\Payments\GetAllPaymentsAction;
use App\Enums\Payments\PaymentPermissions;
use App\Enums\System\AccessRules;
use App\Livewire\Payment\ListPayments;
use App\Models\AccessControlList;
use App\Models\Microsite;
use App\Models\Payment;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class IndexTest extends TestCase
{
    use RefreshDatabase;

    public Role $testRole;
    public Permission $permission;

    public function setup(): void
    {
        parent::setUp();

        $this->permission = Permission::firstWhere('name', PaymentPermissions::ViewAny);
        $this->testRole = Role::factory()->create();
        $this->testRole->givePermissionTo($this->permission);
    }

    public function test_logged_user_can_see_payments(): void
    {
        $user = User::factory()->create()->assignRole($this->testRole);
        $this->actingAs($user);
        $payment = Payment::factory()
            ->count(5)
            ->withPlacetopayGateway()
            ->withDefaultStatus()
            ->requestId(1)
            ->fakeReference()
            ->fakeExpiresAt()
            ->fakeReturnUrl()
            ->create();

        $response = $this->get(route('payments.index'));
        $response->assertStatus(200);
    }

    public function test_get_all_payments_action(): void
    {
        $this->actingAs(User::factory()->create()->assignRole($this->testRole));

        $payment = Payment::factory()
            ->count(5)
            ->withPlacetopayGateway()
            ->withDefaultStatus()
            ->requestId(1)
            ->fakeReference()
            ->fakeExpiresAt()
            ->fakeReturnUrl()
            ->create();

        $retrieved = GetAllPaymentsAction::exec([], new Payment());

        $this->assertEquals($payment->count(), $retrieved->count());
    }

    public function test_logged_user_can_see_only_his_payments(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user->assignRole($this->testRole));

        $userPayments = Payment::factory()
            ->count(3)
            ->withEmail($user->email)
            ->withPlacetopayGateway()
            ->withDefaultStatus()
            ->requestId(1)
            ->fakeReference()
            ->fakeExpiresAt()
            ->fakeReturnUrl()
            ->create();

        $otherPayments = Payment::factory()
            ->count(2)
            ->withPlacetopayGateway()
            ->withDefaultStatus()
            ->requestId(1)
            ->fakeReference()
            ->fakeExpiresAt()
            ->fakeReturnUrl()
            ->create();

        Livewire::test(ListPayments::class)
            ->assertCanSeeTableRecords($userPayments)
            ->assertCanNotSeeTableRecords($otherPayments)
            ->assertCountTableRecords(3);
    }

    public function test_admin_user_can_see_all_payments(): void
    {
        $adminRole = Role::factory()->admin()->create();
        $adminRole->givePermissionTo($this->permission);
        $user = User::factory()->create();
        $this->actingAs($user->assignRole($adminRole));

        $userPayments = Payment::factory()
            ->count(3)
            ->withPlacetopayGateway()
            ->withDefaultStatus()
            ->requestId(1)
            ->fakeReference()
            ->fakeExpiresAt()
            ->fakeReturnUrl()
            ->create();

        Livewire::test(ListPayments::class)
            ->assertCanSeeTableRecords($userPayments)
            ->assertCountTableRecords(3);
    }

    public function test_logged_user_can_see_payments_via_acl(): void
    {
        $user = User::factory()->create()->assignRole($this->testRole);
        $this->actingAs($user);

        $site = Microsite::factory()->create();

        $acl = AccessControlList::factory()
            ->user($user)
            ->rule(AccessRules::Allow->value)
            ->controllableType(Microsite::class)
            ->controllableIds([$site->id])
            ->make();

        StoreAclAction::exec($acl->toArray(), new AccessControlList());

        $userPayments = Payment::factory()
            ->count(3)
            ->withEmail($user->email)
            ->withPlacetopayGateway()
            ->withDefaultStatus()
            ->requestId(1)
            ->fakeReference()
            ->fakeExpiresAt()
            ->fakeReturnUrl()
            ->create([
                'microsite_id' => $site->id,
            ]);

        Livewire::test(ListPayments::class)
            ->assertCanSeeTableRecords($userPayments);
    }

    public function test_logged_user_can_not_see_payments_via_acl(): void
    {
        $user = User::factory()->create()->assignRole($this->testRole);
        $this->actingAs($user);

        $siteDenied = Microsite::factory()->create();

        $deniedAcl = AccessControlList::factory()
            ->user($user)
            ->rule(AccessRules::Deny->value)
            ->controllableType(Microsite::class)
            ->controllableIds([$siteDenied->id])
            ->make();

        StoreAclAction::exec($deniedAcl->toArray(), new AccessControlList());

        $otherPayments = Payment::factory()
            ->count(2)
            ->withPlacetopayGateway()
            ->withDefaultStatus()
            ->requestId(1)
            ->fakeReference()
            ->fakeExpiresAt()
            ->fakeReturnUrl()
            ->create([
                'microsite_id' => $siteDenied->id,
            ]);

        Livewire::test(ListPayments::class)
            ->assertCanNotSeeTableRecords($otherPayments);
    }
}
