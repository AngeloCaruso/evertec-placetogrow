<?php

namespace App\Livewire\Users;

use App\Actions\Users\StoreUserAction;
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

class CreateUser extends Component implements HasForms
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
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                TextInput::make('email')
                    ->email()
                    ->unique('users', 'email')
                    ->required()
                    ->maxLength(255),
                Select::make('roles')
                    ->label('Rol')
                    ->relationship(name: 'roles', titleAttribute: 'name')
                    ->multiple()
                    ->getOptionLabelFromRecordUsing(fn ($record): string => DefaultRoles::tryFrom($record->name)?->getLabel() ?? ucfirst($record->name))
                    ->required()
                    ->preload(),
                TextInput::make('password')
                    ->password()
                    ->required()
                    ->maxLength(255),
            ])
            ->statePath('data')
            ->model(User::class);
    }

    public function create(): void
    {
        StoreUserAction::exec($this->data, new User());
        redirect()->route('users.index');
    }

    public function render(): View
    {
        return view('livewire.users.create-user');
    }
}
