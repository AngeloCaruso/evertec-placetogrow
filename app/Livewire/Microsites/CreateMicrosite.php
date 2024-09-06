<?php

declare(strict_types=1);

namespace App\Livewire\Microsites;

use App\Actions\Microsites\StoreMicrositeAction;
use App\Enums\Microsites\MicrositeCurrency;
use App\Enums\Microsites\MicrositeFormFieldTypes;
use App\Enums\Microsites\MicrositeType;
use App\Models\Microsite;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
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
            ->schema([
                Section::make(__('Microsite info'))
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
                    ])
                    ->columns(1)
                    ->columnSpan(1),
                Section::make(__('Form fields'))
                    ->description(__('* A field Amount and Gateway will be added automatically to Microsites type Donation or Billing.'))
                    ->compact()
                    ->schema([
                        Repeater::make('form_fields')
                            ->label('')
                            ->schema([
                                Group::make()
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
                                            ])
                                            ->columns(3),
                                    ])
                                    ->columns(5),
                                TextInput::make('input_rules')
                                    ->label(__('Input rules'))
                                    ->placeholder(__('Ex: string|alpha_num'))
                                    ->helperText(fn() => view('laravel-validation-hint')),
                            ])
                            ->defaultItems(0)
                            ->cloneable()
                            ->live()
                            ->addActionLabel(__('Add field'))
                            ->itemLabel(fn(array $state): ?string => __($state['name']) ?? null)
                    ])
                    ->columns(1)
                    ->columnSpan(2),
            ])
            ->columns(3)
            ->statePath('data')
            ->model(Microsite::class);
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
