<?php

namespace App\Livewire\Microsites;

use App\Enums\Microsites\MicrositeType;
use App\Models\Microsite;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
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
                        Placeholder::make('name')
                            ->label(__('Name'))
                            ->content(fn (Microsite $site) => $site->name),
                        Placeholder::make('type')
                            ->label(__('Type'))
                            ->content(fn (Microsite $site) => __($site->type->getLabel())),
                        Placeholder::make('categories')
                            ->label(__('Categories'))
                            ->content(fn (Microsite $site) => $site->categories),
                        Group::make()
                            ->schema([
                                Placeholder::make('currency')
                                    ->label(__('Currency'))
                                    ->content(fn (Microsite $site) => $site->currency),
                                Placeholder::make('expiration_payment_time')
                                    ->label(__('Expiration time'))
                                    ->content(fn (Microsite $site) => $site->expiration_payment_time . ' ' . __('Hours')),
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
                            ->deletable(false)
                            ->directory('logos')
                            ->disabled(),
                        Toggle::make('active')
                            ->label(__('Active'))
                            ->onIcon('heroicon-s-check')
                            ->offIcon('heroicon-s-minus')
                            ->default(true)
                            ->disabled(),
                    ])
                    ->columnSpan(1),
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
