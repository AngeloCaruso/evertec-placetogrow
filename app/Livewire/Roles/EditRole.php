<?php

namespace App\Livewire\Roles;

use App\Enums\Microsites\MicrositePermissions;
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
        return $form
            ->schema([
                TextInput::make('name')
                    ->required()
                    // ->unique(ignoreRecord: true)
                    ->maxLength(255),
                TextInput::make('guard_name')
                    ->required()
                    ->default('web')
                    ->disabled()
                    ->maxLength(255),
                Group::make()
                    ->schema([
                        CheckboxList::make('microsite_permissions')
                            ->label('Microsites Permissions')
                            ->columns(2)
                            ->relationship(
                                name: 'permissions',
                                titleAttribute: 'name',
                                modifyQueryUsing: fn (Builder $query) => $query->where('name', 'like', 'microsites.%')
                            )
                            ->bulkToggleable()
                            ->getOptionLabelFromRecordUsing(fn ($record): string => MicrositePermissions::tryFrom($record->name)?->getLabel() ?? $record->name),
                    ])
            ])
            ->statePath('data')
            ->model($this->role);
    }

    public function save(): void
    {
        $data = $this->form->getState();

        $this->role->update($data);

        redirect()->route('roles.index');
    }

    public function render(): View
    {
        return view('livewire.roles.edit-role');
    }
}
