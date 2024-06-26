<?php

namespace App\Livewire\Microsites;

use App\Enums\Microsites\MicrositeType;
use App\Models\Microsite;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
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
        return $form
            ->schema([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                FileUpload::make('logo')
                    ->required()
                    ->image()
                    ->imageEditor(),
                TextInput::make('category')
                    ->required()
                    ->maxLength(255),
                TextInput::make('payment_config')
                    ->maxLength(255),
                Select::make('type')
                    ->required()
                    ->options(MicrositeType::class),
                Toggle::make('active')
                    ->required(),
            ])
            ->statePath('data')
            ->model($this->site);
    }

    public function save(): void
    {
        $data = $this->form->getState();

        $this->site->update($data);

        redirect()->route('microsites.index');
    }

    public function render(): View
    {
        return view('livewire.microsites.edit-microsite');
    }
}
