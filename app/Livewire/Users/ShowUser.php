<?php

declare(strict_types=1);

namespace App\Livewire\Users;

use App\Enums\System\DefaultRoles;
use App\Models\User;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class ShowUser extends Component implements HasForms
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
                Placeholder::make('name')
                    ->label(__('Name'))
                    ->content(fn(User $user) => $user->name),
                Placeholder::make('email')
                    ->label(__('Email'))
                    ->content(fn(User $user) => $user->email),
                Select::make('roles')
                    ->label(__('Rol'))
                    ->relationship(name: 'roles', titleAttribute: 'name')
                    ->getOptionLabelFromRecordUsing(fn($record): string => DefaultRoles::tryFrom($record->name)?->getLabel() ?? ucfirst($record->name))
                    ->multiple()
                    ->native(false)
                    ->preload()
                    ->disabled(),
            ])
            ->columns(2)
            ->statePath('data')
            ->model($this->user);
    }

    public function render(): View
    {
        return view('livewire.users.show-user');
    }
}
