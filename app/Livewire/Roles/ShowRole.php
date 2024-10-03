<?php

declare(strict_types=1);

namespace App\Livewire\Roles;

use App\Enums\Acl\AccessControlListPermissions;
use App\Enums\Imports\ImportPermissions;
use App\Enums\Microsites\MicrositePermissions;
use App\Enums\Payments\PaymentPermissions;
use App\Enums\Roles\RolePermissions;
use App\Enums\Subscriptions\SubscriptionPermissions;
use App\Enums\Users\UserPermissions;
use App\Models\Role;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;

class ShowRole extends Component implements HasForms
{
    use InteractsWithForms;

    public ?array $data = [];

    public Role $role;

    public function mount(Role $role): void
    {
        $this->form->fill($role->toArray());
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make()
                    ->schema([
                        Placeholder::make('name')
                            ->label(__('Name'))
                            ->content(fn(Role $role) => $role->name),
                        Placeholder::make('guard_name')
                            ->label(__('Guard Name'))
                            ->content(fn(Role $role) => $role->guard_name),
                    ])
                    ->columns(2),
                Group::make()
                    ->schema([
                        CheckboxList::make('microsite_permissions')
                            ->label(__('Microsites Permissions'))
                            ->columns(3)
                            ->relationship(
                                name: 'permissions',
                                titleAttribute: 'name',
                                modifyQueryUsing: fn(Builder $query) => $query->where('name', 'like', 'microsites.%'),
                            )
                            ->bulkToggleable()
                            ->getOptionLabelFromRecordUsing(fn($record): string => __(MicrositePermissions::tryFrom($record->name)->getLabel()))
                            ->disabled(),
                        CheckboxList::make('user_permissions')
                            ->label(__('User Permissions'))
                            ->columns(3)
                            ->relationship(
                                name: 'permissions',
                                titleAttribute: 'name',
                                modifyQueryUsing: fn(Builder $query) => $query->where('name', 'like', 'users.%'),
                            )
                            ->bulkToggleable()
                            ->getOptionLabelFromRecordUsing(fn($record): string => __(UserPermissions::tryFrom($record->name)->getLabel()))
                            ->disabled(),
                        CheckboxList::make('role_permissions')
                            ->label(__('Role Permissions'))
                            ->columns(3)
                            ->relationship(
                                name: 'permissions',
                                titleAttribute: 'name',
                                modifyQueryUsing: fn(Builder $query) => $query->where('name', 'like', 'roles.%'),
                            )
                            ->bulkToggleable()
                            ->getOptionLabelFromRecordUsing(fn($record): string => __(RolePermissions::tryFrom($record->name)->getLabel()))
                            ->disabled(),
                        CheckboxList::make('acl_permissions')
                            ->label(__('ACL Permissions'))
                            ->columns(3)
                            ->relationship(
                                name: 'permissions',
                                titleAttribute: 'name',
                                modifyQueryUsing: fn(Builder $query) => $query->where('name', 'like', 'acl.%'),
                            )
                            ->bulkToggleable()
                            ->getOptionLabelFromRecordUsing(fn($record): string => __(AccessControlListPermissions::tryFrom($record->name)->getLabel()))
                            ->disabled(),
                        CheckboxList::make('payment_permissions')
                            ->label(__('Payment Permissions'))
                            ->columns(3)
                            ->relationship(
                                name: 'permissions',
                                titleAttribute: 'name',
                                modifyQueryUsing: fn(Builder $query) => $query->where('name', 'like', 'payments.%'),
                            )
                            ->bulkToggleable()
                            ->getOptionLabelFromRecordUsing(fn($record): string => __(PaymentPermissions::tryFrom($record->name)->getLabel()))
                            ->disabled(),
                        CheckboxList::make('subscription_permissions')
                            ->label(__('Subscription Permissions'))
                            ->columns(3)
                            ->relationship(
                                name: 'permissions',
                                titleAttribute: 'name',
                                modifyQueryUsing: fn(Builder $query) => $query->where('name', 'like', 'subscriptions.%'),
                            )
                            ->bulkToggleable()
                            ->disabled()
                            ->getOptionLabelFromRecordUsing(fn($record): string => __(SubscriptionPermissions::tryFrom($record->name)->getLabel())),
                        CheckboxList::make('data-import_permissions')
                            ->label(__('Data Import Permissions'))
                            ->columns(3)
                            ->relationship(
                                name: 'permissions',
                                titleAttribute: 'name',
                                modifyQueryUsing: fn(Builder $query) => $query->where('name', 'like', 'imports.%'),
                            )
                            ->bulkToggleable()
                            ->disabled()
                            ->getOptionLabelFromRecordUsing(fn($record): string => __(ImportPermissions::tryFrom($record->name)->getLabel())),
                    ]),
            ])
            ->statePath('data')
            ->model($this->role);
    }

    public function render(): View
    {
        return view('livewire.roles.show-role');
    }
}
