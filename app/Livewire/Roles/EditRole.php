<?php

namespace App\Livewire\Roles;

use App\Actions\Roles\UpdateRoleAction;
use App\Enums\Microsites\MicrositePermissions;
use App\Enums\Roles\RolePermissions;
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

class EditRole extends Component implements HasForms
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
        $user = auth()->user();
        $fieldDisabled = $user->hasPermissionTo(RolePermissions::View) && !$user->hasPermissionTo(RolePermissions::Update);

        return $form
            ->schema([
                Group::make()
                    ->schema([
                        TextInput::make('name')
                            ->label(__('Name'))
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255)
                            ->disabled(fn (): bool => $fieldDisabled),
                        TextInput::make('guard_name')
                            ->required()
                            ->default('web')
                            ->disabled()
                            ->maxLength(255)
                            ->disabled(fn (): bool => $fieldDisabled),
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
                                modifyQueryUsing: fn (Builder $query) => $query->where('name', 'like', 'microsites.%')
                            )
                            ->bulkToggleable()
                            ->getOptionLabelFromRecordUsing(fn ($record): string => __(MicrositePermissions::tryFrom($record->name)->getLabel()))
                            ->disabled(fn (): bool => $fieldDisabled),
                        CheckboxList::make('user_permissions')
                            ->label(__('User Permissions'))
                            ->columns(3)
                            ->relationship(
                                name: 'permissions',
                                titleAttribute: 'name',
                                modifyQueryUsing: fn (Builder $query) => $query->where('name', 'like', 'users.%')
                            )
                            ->bulkToggleable()
                            ->getOptionLabelFromRecordUsing(fn ($record): string => __(UserPermissions::tryFrom($record->name)->getLabel()))
                            ->disabled(fn (): bool => $fieldDisabled),
                        CheckboxList::make('role_permissions')
                            ->label(__('Role Permissions'))
                            ->columns(3)
                            ->relationship(
                                name: 'permissions',
                                titleAttribute: 'name',
                                modifyQueryUsing: fn (Builder $query) => $query->where('name', 'like', 'roles.%')
                            )
                            ->bulkToggleable()
                            ->getOptionLabelFromRecordUsing(fn ($record): string => __(RolePermissions::tryFrom($record->name)->getLabel()))
                            ->disabled(fn (): bool => $fieldDisabled),
                    ])
            ])
            ->statePath('data')
            ->model($this->role);
    }

    public function save(): void
    {
        UpdateRoleAction::exec($this->data, $this->role);
        redirect()->route('roles.index');
    }

    public function render(): View
    {
        return view('livewire.roles.edit-role');
    }
}
