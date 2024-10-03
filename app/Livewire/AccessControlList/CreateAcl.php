<?php

declare(strict_types=1);

namespace App\Livewire\AccessControlList;

use App\Actions\AccessControlList\StoreAclAction;
use App\Enums\Acl\ControllableTypes;
use App\Enums\System\AccessRules;
use App\Models\AccessControlList;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Livewire\Component;
use Illuminate\Contracts\View\View;
use Illuminate\Validation\Rules\Unique;

class CreateAcl extends Component implements HasForms
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
                Select::make('user_id')
                    ->label(__('User'))
                    ->relationship(name: 'user', titleAttribute: 'email')
                    ->native(false)
                    ->preload()
                    ->required(),
                Select::make('rule')
                    ->label(__('Rule'))
                    ->native(false)
                    ->options(AccessRules::class)
                    ->required(),
                Select::make('controllable_type')
                    ->label(__('Entity Type'))
                    ->options(ControllableTypes::class)
                    ->native(false)
                    ->live()
                    ->required(),
                Select::make('controllable_id')
                    ->label(__('Entity'))
                    ->options(fn(Get $get) => $get('controllable_type') ? $get('controllable_type')::pluck('name', 'id') : [])
                    ->multiple()
                    ->native(false)
                    ->unique(
                        modifyRuleUsing: fn(Unique $rule, Get $get) => $rule
                            ->where('user_id', $get('user_id'))
                            ->where('rule', $get('rule'))
                            ->where('controllable_type', $get('controllable_type'))
                            ->where('controllable_id', $get('controllable_id')),
                    )
                    ->required(),
            ])
            ->columns(2)
            ->statePath('data')
            ->model(AccessControlList::class);
    }

    public function create(): void
    {
        $acl = StoreAclAction::exec($this->form->getState(), new AccessControlList());
        $this->form->model($acl)->saveRelationships();
        redirect()->route('acl.index');
    }

    public function render(): View
    {
        return view('livewire.access-control-list.create-acl');
    }
}
