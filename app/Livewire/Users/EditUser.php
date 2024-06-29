<?php

namespace App\Livewire\Users;

use App\Actions\Users\UpdateUserAction;
use App\Enums\System\DefaultRoles;
use App\Models\User;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Livewire\Component;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Hash;

class EditUser extends Component implements HasForms
{
    use InteractsWithForms;

    public ?array $data = [];

    public User $user;

    public function mount(): void
    {
        $this->form->fill($this->user->attributesToArray());
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label(__('Name'))
                    ->maxLength(255),
                TextInput::make('email')
                    ->label(__('Email'))
                    ->email()
                    ->maxLength(255),
                Select::make('roles')
                    ->label(__('Rol'))
                    ->relationship(name: 'roles', titleAttribute: 'name')
                    ->getOptionLabelFromRecordUsing(fn ($record): string => DefaultRoles::tryFrom($record->name)?->getLabel() ?? ucfirst($record->name))
                    ->multiple()
                    ->native(false)
                    ->preload(),
                Select::make('microsite_id')
                    ->label(__('Microsite'))
                    ->relationship(name: 'microsite', titleAttribute: 'name')
                    ->getOptionLabelFromRecordUsing(fn ($record): string => ucfirst($record->name))
                    ->native(false)
                    ->preload(),
                TextInput::make('password')
                    ->label(__('Password'))
                    ->password()
                    ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                    ->dehydrated(fn ($state) => filled($state))
                    ->maxLength(255),
            ])
            ->columns(2)
            ->statePath('data')
            ->model($this->user);
    }

    public function save(): void
    {
        UpdateUserAction::exec($this->data, $this->user);
        redirect()->route('users.index');
    }

    public function render(): View
    {
        return view('livewire.users.edit-user');
    }
}
