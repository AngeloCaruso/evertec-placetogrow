<?php

namespace App\Livewire\Microsites;

use App\Actions\Microsites\UpdateMicrositeAction;
use App\Enums\Microsites\MicrositeCurrency;
use App\Enums\Microsites\MicrositePermissions;
use App\Enums\Microsites\MicrositeType;
use App\Models\Microsite;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Livewire\Component;
use Illuminate\Contracts\View\View;

class EditMicrosite extends Component implements HasForms
{
    use InteractsWithForms;

    public ?array $data = [];

    public Microsite $site;

    public function mount(): void
    {
        $this->form->fill($this->site->attributesToArray());
    }

    public function form(Form $form): Form
    {
        $user = auth()->user();
        $fieldDisabled = $user->hasPermissionTo(MicrositePermissions::View) && !$user->hasPermissionTo(MicrositePermissions::Update);

        return $form
            ->schema([
                Section::make(__('Microsite info'))
                    ->schema([
                        TextInput::make('name')
                            ->label(__('Name'))
                            ->required()
                            ->maxLength(60)
                            ->disabled(fn (): bool => $fieldDisabled),
                        Select::make('type')
                            ->label(__('Type'))
                            ->required()
                            ->native(false)
                            ->options(MicrositeType::class)
                            ->disabled(fn (): bool => $fieldDisabled),
                        TagsInput::make('categories')
                            ->label(__('Categories'))
                            ->required()
                            ->separator(',')
                            ->disabled(fn (): bool => $fieldDisabled),
                        Group::make()
                            ->schema([
                                Select::make('currency')
                                    ->label(__('Currency'))
                                    ->required()
                                    ->native(false)
                                    ->options(MicrositeCurrency::class)
                                    ->disabled(fn (): bool => $fieldDisabled),
                                TextInput::make('expiration_payment_time')
                                    ->label('Expiration time')
                                    ->label(__('Expiration time'))
                                    ->required()
                                    ->numeric()
                                    ->minValue(1)
                                    ->suffix(__('Hours'))
                                    ->disabled(fn (): bool => $fieldDisabled),
                            ])
                            ->columns(2),
                    ])
                    ->columns(2)
                    ->columnSpan(2),
                Section::make('')
                    ->schema([
                        FileUpload::make('logo')
                            ->required()
                            ->image()
                            ->imageEditor()
                            ->directory('logos')
                            ->disabled(fn (): bool => $fieldDisabled),
                        Toggle::make('active')
                            ->label(__('Active'))
                            ->onIcon('heroicon-s-check')
                            ->offIcon('heroicon-s-minus')
                            ->default(true)
                            ->disabled(fn (): bool => $fieldDisabled),
                    ])
                    ->columnSpan(1),
            ])
            ->columns(3)
            ->statePath('data')
            ->model($this->site);
    }

    public function save(): void
    {
        UpdateMicrositeAction::exec($this->form->getState(), $this->site);
        $this->form->model($this->site)->saveRelationships();
        redirect()->route('microsites.index');
    }

    public function render(): View
    {
        return view('livewire.microsites.edit-microsite');
    }
}
