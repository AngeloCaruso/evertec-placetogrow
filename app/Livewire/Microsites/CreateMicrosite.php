<?php

declare(strict_types=1);

namespace App\Livewire\Microsites;

use App\Actions\Microsites\StoreMicrositeAction;
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
use Filament\Forms\Set;
use Livewire\Component;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;

class CreateMicrosite extends Component implements HasForms
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
            ->columns(3)
            ->statePath('data')
            ->model(Microsite::class)
            ->schema([
                Section::make(__('Microsite info'))
                    ->columns(1)
                    ->columnSpan(1)
                    ->schema([
                        TextInput::make('slug')
                            ->label(__('Slug'))
                            ->required()
                            ->readOnly()
                            ->unique('microsites', 'slug')
                            ->maxLength(60),
                        TextInput::make('name')
                            ->label(__('Name'))
                            ->required()
                            ->maxLength(60)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn(Set $set, ?string $state) => $set('slug', Str::slug($state))),
                        Select::make('type')
                            ->label(__('Type'))
                            ->required()
                            ->native(false)
                            ->live()
                            ->options(MicrositeType::class),
                        TagsInput::make('categories')
                            ->label(__('Categories'))
                            ->required()
                            ->separator(','),
                        Group::make()
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
                            ])
                            ->columns(2),
                        Group::make()
                            ->columns(3)
                            ->visible(fn(Get $get): bool => in_array($get('type'), [MicrositeType::Billing->value]))
                            ->schema([
                                TextInput::make('penalty_fee')
                                    ->columnSpan(2)
                                    ->label(__('Penalty fee'))
                                    ->placeholder(__('Penalty fee'))
                                    ->required()
                                    ->numeric()
                                    ->minValue(0)
                                    ->suffix(fn(Get $get): string => $get('penalty_is_percentage') ? '% ' . __('per day') : $get('currency') . ' ' . __('per day')),
                                Toggle::make('penalty_is_percentage')
                                    ->label(__('Percentage'))
                                    ->inline(false)
                                    ->live(),
                            ]),
                        Group::make()
                            ->columns(2)
                            ->visible(fn(Get $get): bool => in_array($get('type'), [MicrositeType::Subscription->value]))
                            ->schema([
                                TextInput::make('payment_retries')
                                    ->label(__('Payment Retries'))
                                    ->placeholder(__('Retries'))
                                    ->required()
                                    ->numeric()
                                    ->minValue(1)
                                    ->suffix(__('Times')),
                                TextInput::make('payment_retry_interval')
                                    ->label(__('Interval'))
                                    ->placeholder(__('Interval'))
                                    ->hintIcon('heroicon-m-question-mark-circle', tooltip: __('Interval between retries'))
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
                    ->description(__('* A field Amount and Gateway will be added automatically to Microsites type Donation or Billing. The Email field will be added to all types of microsite.'))
                    ->compact()
                    ->columns(1)
                    ->columnSpan(2)
                    ->hidden(fn(Get $get): bool => !$get('./')['type'] || $get('./')['type'] === MicrositeType::Subscription->value)
                    ->schema([
                        Repeater::make('form_fields')
                            ->label('')
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
                                            ->label(__('Custom options'))
                                            ->placeholder(__('Options'))
                                            ->separator(',')
                                            ->visible(fn(Get $get): bool => $get('type') === MicrositeFormFieldTypes::Select->value),
                                        Group::make()
                                            ->columns(3)
                                            ->schema([
                                                Toggle::make('input_active')
                                                    ->label(__('Active'))
                                                    ->onIcon('heroicon-s-check')
                                                    ->offIcon('heroicon-s-minus')
                                                    ->inline(false)
                                                    ->default(true),
                                                Toggle::make('input_mandatory')
                                                    ->label(__('Mandatory'))
                                                    ->onIcon('heroicon-s-check')
                                                    ->offIcon('heroicon-s-minus')
                                                    ->inline(false)
                                                    ->default(false),
                                            ]),
                                    ]),
                                TextInput::make('input_rules')
                                    ->label(__('Input rules'))
                                    ->placeholder(__('Ex: string|alpha_num'))
                                    ->helperText(fn() => view('laravel-validation-hint')),
                            ])
                            ->defaultItems(0)
                            ->cloneable()
                            ->live()
                            ->addActionLabel(__('Add field'))
                            ->itemLabel(fn(array $state): ?string => __($state['name']) ?? null),
                    ]),
                Section::make(__('Plans'))
                    ->description(__('Subscription plans'))
                    ->compact()
                    ->columns(1)
                    ->columnSpan(2)
                    ->hidden(fn(Get $get): bool => !$get('./')['type'] || in_array($get('./')['type'], [MicrositeType::Donation->value, MicrositeType::Billing->value]))
                    ->schema([
                        Group::make()
                            ->columns(4)
                            ->schema([
                                Checkbox::make('is_paid_monthly')
                                    ->label(__('Paid monthly'))
                                    ->required(fn(Get $get): bool => !$get('is_paid_yearly'))
                                    ->inline()
                                    ->live()
                                    ->inlineLabel(false),
                                Checkbox::make('is_paid_yearly')
                                    ->label(__('Paid yearly'))
                                    ->required(fn(Get $get): bool => !$get('is_paid_monthly'))
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
                            ->itemLabel(fn(array $state): ?string => __($state['name']) ?? null)
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
                                                    ->required(fn(Get $get): bool => $get('../../is_paid_monthly')),
                                                TextInput::make('price_yearly')
                                                    ->label(__('Price yearly'))
                                                    ->placeholder(__('Price yearly'))
                                                    ->required(fn(Get $get): bool => $get('../../is_paid_yearly')),
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
                            ]),
                    ]),
            ])
        ;
    }

    public function create(): void
    {
        $site = StoreMicrositeAction::exec($this->form->getState(), new Microsite());
        $this->form->model($site)->saveRelationships();
        redirect()->route('microsites.index');
    }

    public function render(): View
    {
        return view('livewire.microsites.create-microsite');
    }
}
