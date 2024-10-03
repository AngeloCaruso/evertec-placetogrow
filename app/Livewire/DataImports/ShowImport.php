<?php

declare(strict_types=1);

namespace App\Livewire\DataImports;

use App\Enums\Imports\ImportEntity;
use App\Models\DataImport;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Livewire\Component;
use Illuminate\Contracts\View\View;
use Illuminate\Support\HtmlString;

class ShowImport extends Component implements HasForms
{
    use InteractsWithForms;

    public ?array $data = [];

    public DataImport $import;

    public function mount(): void
    {
        $this->form->fill($this->import->attributesToArray());
    }

    public function form(Form $form): Form
    {
        return $form
            ->columns(3)
            ->schema([
                Section::make(__('Import info'))
                    ->columns(1)
                    ->columnSpan(1)
                    ->disabled()
                    ->schema([
                        Select::make('entity')
                            ->label(__('Entity'))
                            ->native(false)
                            ->required()
                            ->options(ImportEntity::class),
                        FileUpload::make('file')
                            ->label(__('File'))
                            ->downloadable()
                            ->required(),
                    ]),
                Section::make(__('Import results'))
                    ->columns(1)
                    ->columnSpan(2)
                    ->disabled()
                    ->schema([
                        Placeholder::make('errors')
                            ->content(fn() => view('livewire.data-imports.import-errors', ['errors' => $this->import->errors])),
                    ]),
            ])
            ->statePath('data')
            ->model($this->import);
    }

    public function render(): View
    {
        return view('livewire.data-imports.show-import');
    }
}
