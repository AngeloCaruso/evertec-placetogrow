<?php

declare(strict_types=1);

namespace App\Livewire\Microsites;

use App\Actions\Microsites\UpdateMicrositeAction;
use App\Enums\Microsites\MicrositeCurrency;
use App\Enums\Microsites\MicrositeFormFieldTypes;
use App\Enums\Microsites\MicrositeType;
use App\Enums\Microsites\SubscriptionCollectType;
use App\Models\Microsite;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Forms\Get;
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
        return $form
            ->columns(3)
            ->statePath('data')
            ->model($this->site)
            ->schema([
                Section::make(__('Microsite info'))
                    ->columns(1)
                    ->columnSpan(1)
                    ->schema([
                        TextInput::make('slug')
                            ->label(__('Slug'))
                            ->readOnly()
                            ->required()
                            ->maxLength(60),
                        TextInput::make('name')
                            ->label(__('Name'))
                            ->required()
                            ->maxLength(60),
                        Select::make('type')
                            ->label(__('Type'))
                            ->required()
                            ->native(false)
                            ->disabled()
                            ->options(MicrositeType::class),
                        TagsInput::make('categories')
                            ->label(__('Categories'))
                            ->required()
                            ->separator(','),
                        Group::make()
                            ->columns(2)
                            ->schema([
                                Select::make('currency')
                                    ->label(__('Currency'))
                                    ->placeholder(__('Options'))
                                    ->required()
                                    ->native(false)
                                    ->options(MicrositeCurrency::class),
                                TextInput::make('expiration_payment_time')
                                    ->label(__('Expiration time'))
                                    ->required()
                                    ->numeric()
                                    ->minValue(1)
                                    ->suffix(__('Hours')),
                            ]),
                        ColorPicker::make('primary_color')
                            ->label(__('Primary color')),
                        FileUpload::make('logo')
                            ->required()
                            ->image()
                            ->imageEditor()
                            ->directory('logos'),
                        Toggle::make('active')
                            ->label(__('Active'))
                            ->onIcon('heroicon-s-check')
                            ->offIcon('heroicon-s-minus')
                            ->default(true),
                    ]),
                Section::make(__('Form fields'))
                    ->description(__('* A field Amount and Gateway will be added automatically to Microsites type Donation or Billing.'))
                    ->compact()
                    ->columns(1)
                    ->columnSpan(2)
                    ->hidden(fn (Get $get): bool => $get('./')['type'] === MicrositeType::Subscription->value)
                    ->schema([
                        Repeater::make('form_fields')
                            ->label('')
                            ->defaultItems(0)
                            ->cloneable()
                            ->live()
                            ->addActionLabel(__('Add field'))
                            ->itemLabel(fn (array $state): ?string => __($state['name']) ?? null)
                            ->schema([
                                Group::make()
                                    ->columns(5)
                                    ->schema([
                                        TextInput::make('name')
                                            ->label(__('Name'))
                                            ->placeholder(__('Name'))
                                            ->required(),
                                        Select::make('type')
                                            ->label(__('Type'))
                                            ->placeholder(__('Options'))
                                            ->required()
                                            ->native(false)
                                            ->columnSpan(2)
                                            ->options(MicrositeFormFieldTypes::class),
                                        TagsInput::make('select_options')
                                            ->label(__('Options'))
                                            ->placeholder(__('Options'))
                                            ->separator(',')
                                            ->disabled(fn (Get $get): bool => $get('type') !== MicrositeFormFieldTypes::Select),
                                        Group::make()
                                            ->schema([
                                                Toggle::make('input_active')
                                                    ->label(__('Active'))
                                                    ->onIcon('heroicon-s-check')
                                                    ->offIcon('heroicon-s-minus')
                                                    ->inline(false)
                                                    ->default(true),
                                                Toggle::make('input_mandatory')
                                                    ->label(__('Required'))
                                                    ->onIcon('heroicon-s-check')
                                                    ->offIcon('heroicon-s-minus')
                                                    ->inline(false)
                                                    ->default(false),
                                            ])
                                            ->columns(3),
                                    ]),
                                TextInput::make('input_rules')
                                    ->label(__('Input rules'))
                                    ->placeholder(__('Ex: string|alpha_num'))
                                    ->helperText(fn () => view('laravel-validation-hint')),
                            ])
                    ]),
                Section::make(__('Plans'))
                    ->description(__('Subscription plans'))
                    ->compact()
                    ->columns(1)
                    ->columnSpan(2)
                    ->hidden(fn (Get $get): bool => in_array($get('./')['type'], [MicrositeType::Donation, MicrositeType::Billing]))
                    ->schema([
                        Group::make()
                            ->columns(4)
                            ->schema([
                                Checkbox::make('is_paid_monthtly')
                                    ->label(__('Paid monthly'))
                                    ->inline()
                                    ->live()
                                    ->inlineLabel(false),
                                Checkbox::make('is_paid_yearly')
                                    ->label(__('Paid yearly'))
                                    ->inline()
                                    ->live()
                                    ->inlineLabel(false),
                                ToggleButtons::make('charge_collect')
                                    ->label(__('Charge collect'))
                                    ->options(SubscriptionCollectType::class)
                                    ->inline()
                                    ->inlineLabel(false)
                                    ->columnSpan(2),
                            ]),
                        Repeater::make('plans')
                            ->label('')
                            ->columns(2)
                            ->defaultItems(0)
                            ->cloneable()
                            ->live()
                            ->addActionLabel(__('Add field'))
                            ->itemLabel(fn (array $state): ?string => __($state['name']) ?? null)
                            ->schema([
                                Group::make()
                                    ->schema([
                                        TextInput::make('name')
                                            ->label(__('Name'))
                                            ->placeholder(__('Name'))
                                            ->required(),
                                        Group::make()
                                            ->schema([
                                                TextInput::make('price_monthly')
                                                    ->label(__('Price monthly'))
                                                    ->placeholder(__('Price monthly'))
                                                    ->required(fn (Get $get): bool => $get('../../is_paid_monthtly')),
                                                TextInput::make('price_yearly')
                                                    ->label(__('Price yearly'))
                                                    ->placeholder(__('Price yearly'))
                                                    ->required(fn (Get $get): bool => $get('../../is_paid_yearly')),
                                            ])
                                            ->columns(2),
                                    ]),
                                Group::make()
                                    ->schema([
                                        TagsInput::make('features')
                                            ->label(__('Features'))
                                            ->placeholder(__('Features'))
                                            ->separator(',')
                                            ->required(),
                                        Toggle::make('featured')
                                            ->label(__('Featured'))
                                            ->onIcon('heroicon-s-check')
                                            ->offIcon('heroicon-s-minus')
                                            ->inline(false)
                                            ->default(false),
                                    ]),
                            ])
                    ]),
            ]);
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
