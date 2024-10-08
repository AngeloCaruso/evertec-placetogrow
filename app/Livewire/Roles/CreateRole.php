<?php

declare(strict_types=1);

namespace App\Livewire\Roles;

use App\Actions\Roles\StoreRoleAction;
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
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Livewire\Component;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;

class CreateRole extends Component implements HasForms
{
    use InteractsWithForms;

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make()
                    ->schema([
                        TextInput::make('name')
                            ->label(__('Name'))
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),
                        TextInput::make('guard_name')
                            ->required()
                            ->default('web')
                            ->disabled()
                            ->maxLength(255),
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
                            ->getOptionLabelFromRecordUsing(fn($record): string => __(MicrositePermissions::tryFrom($record->name)->getLabel())),
                        CheckboxList::make('user_permissions')
                            ->label(__('User Permissions'))
                            ->columns(3)
                            ->relationship(
                                name: 'permissions',
                                titleAttribute: 'name',
                                modifyQueryUsing: fn(Builder $query) => $query->where('name', 'like', 'users.%'),
                            )
                            ->bulkToggleable()
                            ->getOptionLabelFromRecordUsing(fn($record): string => __(UserPermissions::tryFrom($record->name)->getLabel())),
                        CheckboxList::make('role_permissions')
                            ->label(__('Role Permissions'))
                            ->columns(3)
                            ->relationship(
                                name: 'permissions',
                                titleAttribute: 'name',
                                modifyQueryUsing: fn(Builder $query) => $query->where('name', 'like', 'roles.%'),
                            )
                            ->bulkToggleable()
                            ->getOptionLabelFromRecordUsing(fn($record): string => __(RolePermissions::tryFrom($record->name)->getLabel())),
                        CheckboxList::make('acl_permissions')
                            ->label(__('ACL Permissions'))
                            ->columns(3)
                            ->relationship(
                                name: 'permissions',
                                titleAttribute: 'name',
                                modifyQueryUsing: fn(Builder $query) => $query->where('name', 'like', 'acl.%'),
                            )
                            ->bulkToggleable()
                            ->getOptionLabelFromRecordUsing(fn($record): string => __(AccessControlListPermissions::tryFrom($record->name)->getLabel())),
                        CheckboxList::make('payment_permissions')
                            ->label(__('Payment Permissions'))
                            ->columns(3)
                            ->relationship(
                                name: 'permissions',
                                titleAttribute: 'name',
                                modifyQueryUsing: fn(Builder $query) => $query->where('name', 'like', 'payments.%'),
                            )
                            ->bulkToggleable()
                            ->getOptionLabelFromRecordUsing(fn($record): string => __(PaymentPermissions::tryFrom($record->name)->getLabel())),
                        CheckboxList::make('subscription_permissions')
                            ->label(__('Subscription Permissions'))
                            ->columns(3)
                            ->relationship(
                                name: 'permissions',
                                titleAttribute: 'name',
                                modifyQueryUsing: fn(Builder $query) => $query->where('name', 'like', 'subscriptions.%'),
                            )
                            ->bulkToggleable()
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
                            ->getOptionLabelFromRecordUsing(fn($record): string => __(ImportPermissions::tryFrom($record->name)->getLabel())),
                    ]),
            ])
            ->statePath('data')
            ->model(Role::class);
    }

    public function create(): void
    {
        StoreRoleAction::exec($this->data, new Role());
        redirect()->route('roles.index');
    }

    public function render(): View
    {
        return view('livewire.roles.create-role');
    }
}
