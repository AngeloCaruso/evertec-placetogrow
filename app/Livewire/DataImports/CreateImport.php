<?php

namespace App\Livewire\DataImports;

use App\Actions\DataImports\StoreDataImportsAction;
use App\Enums\Imports\ImportEntity;
use App\Models\DataImport;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Livewire\Component;
use Illuminate\Contracts\View\View;

class CreateImport extends Component implements HasForms
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
            ->schema([
                Section::make(__('Import info'))
                    ->columns(1)
                    ->columnSpan(1)
                    ->schema([
                        Select::make('entity')
                            ->label(__('Entity'))
                            ->native(false)
                            ->required()
                            ->options(ImportEntity::class),
                        FileUpload::make('file')
                            ->label(__('File'))
                            ->required(),
                    ]),
            ])
            ->statePath('data')
            ->model(DataImport::class);
    }

    public function create(): void
    {
        $site = StoreDataImportsAction::exec($this->form->getState(), new DataImport());
        $this->form->model($site)->saveRelationships();
        redirect()->route('data-imports.index');
    }

    public function render(): View
    {
        return view('livewire.data-imports.create-import');
    }
}
