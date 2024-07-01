<?php

namespace App\Livewire\Users;

use App\Actions\Users\StoreUserAction;
use App\Enums\System\DefaultRoles;
use App\Models\User;
use Filament\Forms\Components\Group;
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
                Group::make()
                    ->schema([
                        TextInput::make('name')
                            ->label(__('Name'))
                            ->required()
                            ->maxLength(255),
                        TextInput::make('email')
                            ->label(__('Email'))
                            ->email()
                            ->unique('users', 'email')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('password')
                            ->label(__('Password'))
                            ->password()
                            ->required()
                            ->maxLength(255),
                    ]),

                Group::make()
                    ->schema([
                        Select::make('microsite_id')
                            ->label(__('Microsite'))
                            ->relationship(name: 'microsite', titleAttribute: 'name')
                            ->getOptionLabelFromRecordUsing(fn ($record): string => ucfirst($record->name))
                            ->native(false)
                            ->preload(),
                        Select::make('roles')
                            ->label(__('Rol'))
                            ->relationship(name: 'roles', titleAttribute: 'name')
                            ->getOptionLabelFromRecordUsing(fn ($record): string => DefaultRoles::tryFrom($record->name)?->getLabel() ?? ucfirst($record->name))
                            ->multiple()
                            ->native(false)
                            ->required()
                            ->preload(),
                    ])
            ])
            ->columns(2)
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
