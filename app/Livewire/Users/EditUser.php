<?php

namespace App\Livewire\Users;

use App\Actions\Users\UpdateUserAction;
use App\Enums\System\DefaultRoles;
use App\Models\User;
use Filament\Forms;
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
                    ->maxLength(255),
                TextInput::make('email')
                    ->email()
                    ->maxLength(255),
                Select::make('roles')
                    ->label('Rol')
                    ->relationship(name: 'roles', titleAttribute: 'name')
                    ->multiple()
                    ->getOptionLabelFromRecordUsing(fn ($record): string => DefaultRoles::tryFrom($record->name)?->getLabel() ?? ucfirst($record->name))
                    ->preload(),
                TextInput::make('password')
                    ->password()
                    ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                    ->dehydrated(fn ($state) => filled($state))
                    ->maxLength(255),
            ])
            ->statePath('data')
            ->model($this->user);
    }

    public function save(): void
    {
        if ($this->data['password']) {
            $this->data['password'] = Hash::make($this->data['password']);
        } else {
            unset($this->data['password']);
        }

        UpdateUserAction::exec($this->data, $this->user);
        redirect()->route('users.index');
    }

    public function render(): View
    {
        return view('livewire.users.edit-user');
    }
}
