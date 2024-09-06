<?php

declare(strict_types=1);

namespace App\Livewire\Microsites;

use App\Enums\Microsites\MicrositeFormFieldTypes;
use App\Models\Microsite;
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
            ->schema([
                Section::make(__('Microsite info'))
                    ->schema([
                        Group::make()
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
                            ])
                            ->columns(2),
                        Placeholder::make('type')
                            ->label(__('Type'))
                            ->content(fn(Microsite $site) => __($site->type->getLabel())),
                        Placeholder::make('categories')
                            ->label(__('Categories'))
                            ->content(fn(Microsite $site) => $site->categories),
                        Group::make()
                            ->schema([
                                Placeholder::make('currency')
                                    ->label(__('Currency'))
                                    ->content(fn(Microsite $site) => $site->currency),
                                Placeholder::make('expiration_payment_time')
                                    ->label(__('Expiration time'))
                                    ->content(fn(Microsite $site) => $site->expiration_payment_time . ' ' . __('Hours')),
                            ])
                            ->columns(2),
                        ColorPicker::make('primary_color')
                            ->label(__('Primary color'))
                            ->disabled(),
                        FileUpload::make('logo')
                            ->required()
                            ->image()
                            ->deletable(false)
                            ->directory('logos')
                            ->disabled(),

                    ])
                    ->columns(1)
                    ->columnSpan(1),

                Section::make(__('Form fields'))
                    ->description(__('* A field Amount and Gateway will be added automatically to Microsites type Donation or Billing.'))
                    ->compact()
                    ->schema([
                        Repeater::make('form_fields')
                            ->disabled()
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
                                    ->placeholder(__('Ex: string|alpha_num')),
                            ])
                            ->defaultItems(0)
                            ->collapsed()
                            ->cloneable()
                            ->addActionLabel(__('Add field'))
                            ->itemLabel(fn(array $state): ?string => __($state['name']) ?? null)
                    ])
                    ->columns(1)
                    ->columnSpan(2),
            ])
            ->columns(3)
            ->statePath('data')
            ->model($this->site);
    }

    public function render(): View
    {
        return view('livewire.microsites.show-microsite');
    }
}
