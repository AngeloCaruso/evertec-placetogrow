<?php

namespace App\Livewire\Microsites;

use App\Actions\Microsites\StoreMicrositeAction;
use App\Enums\Microsites\MicrositeType;
use App\Http\Controllers\MicrositeController;
use App\Http\Requests\StoreMicrositeRequest;
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
use Illuminate\Http\Request;

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
            ->model(Microsite::class);
    }

    public function create(): void
    {
        $data = $this->form->getState();

        $record = Microsite::create($data);

        $this->form->model($record)->saveRelationships();

        redirect()->route('microsites.index');
    }

    public function render(): View
    {
        return view('livewire.microsites.create-microsite');
    }
}
