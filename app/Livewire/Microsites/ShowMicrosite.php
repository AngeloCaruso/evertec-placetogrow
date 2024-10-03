<?php

declare(strict_types=1);

namespace App\Livewire\Microsites;

use App\Enums\Microsites\MicrositeFormFieldTypes;
use App\Enums\Microsites\MicrositeType;
use App\Enums\Microsites\SubscriptionCollectType;
use App\Models\Microsite;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Placeholder;
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
use Illuminate\Contracts\View\View;
use Livewire\Component;

class ShowMicrosite extends Component implements HasForms
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
                        Group::make()
                            ->columns(2)
                            ->schema([
                                Placeholder::make('name')
                                    ->label(__('Name'))
                                    ->content(fn(Microsite $site) => $site->name),
                                Toggle::make('active')
                                    ->label(__('Active'))
                                    ->inline(false)
                                    ->onIcon('heroicon-s-check')
                                    ->offIcon('heroicon-s-minus')
                                    ->default(true)
                                    ->disabled(),
                            ]),
                        Placeholder::make('type')
                            ->label(__('Type'))
                            ->content(fn(Microsite $site) => __($site->type->getLabel())),
                        Placeholder::make('categories')
                            ->label(__('Categories'))
                            ->content(fn(Microsite $site) => $site->categories),
                        Group::make()
                            ->columns(2)
                            ->schema([
                                Placeholder::make('currency')
                                    ->label(__('Currency'))
                                    ->content(fn(Microsite $site) => $site->currency),
                                Placeholder::make('expiration_payment_time')
                                    ->label(__('Expiration time'))
                                    ->content(fn(Microsite $site) => $site->expiration_payment_time . ' ' . __('Hours')),
                            ]),
                        ColorPicker::make('primary_color')
                            ->label(__('Primary color'))
                            ->disabled(),
                        FileUpload::make('logo')
                            ->required()
                            ->image()
                            ->deletable(false)
                            ->directory('logos')
                            ->disabled(),

                    ]),
                Section::make(__('Form fields'))
                    ->compact()
                    ->hidden(fn(Get $get): bool => $get('./')['type'] === MicrositeType::Subscription->value)
                    ->columns(1)
                    ->columnSpan(2)
                    ->schema([
                        Repeater::make('form_fields')
                            ->disabled()
                            ->label('')
                            ->defaultItems(0)
                            ->collapsed()
                            ->cloneable()
                            ->addActionLabel(__('Add field'))
                            ->itemLabel(fn(array $state): ?string => __($state['name']) ?? null)
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
                                            ->options(MicrositeFormFieldTypes::class),
                                        TagsInput::make('select_options')
                                            ->label(__('Custom options'))
                                            ->placeholder(__('Options'))
                                            ->separator(',')
                                            ->disabled(fn(Get $get): bool => $get('type') !== MicrositeFormFieldTypes::Select),
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
                                    ->placeholder(__('Ex: string|alpha_num')),
                            ]),
                    ]),
                Section::make(__('Plans'))
                    ->description(__('Subscription plans'))
                    ->compact()
                    ->columns(1)
                    ->disabled()
                    ->columnSpan(2)
                    ->hidden(fn(Get $get): bool => in_array($get('./')['type'], [MicrositeType::Donation->value, MicrositeType::Billing->value]))
                    ->schema([
                        Group::make()
                            ->columns(4)
                            ->schema([
                                Checkbox::make('is_paid_monthly')
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
                            ->disabled()
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
            ]);
    }

    public function render(): View
    {
        return view('livewire.microsites.show-microsite');
    }
}
