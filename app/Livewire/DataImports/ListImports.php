<?php

namespace App\Livewire\DataImports;

use App\Enums\Imports\ImportEntity;
use App\Enums\Imports\ImportPermissions;
use App\Models\DataImport;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Livewire\Component;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;

class ListImports extends Component implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    public function table(Table $table): Table
    {
        $user = auth()->user();

        return $table
            ->heading(__('Data Imports'))
            ->description(__('List of the data imports'))
            ->headerActions([
                Action::make('create')
                    ->label(__('Create New Import'))
                    ->icon('heroicon-o-plus')
                    ->action(fn() => $this->redirect(route('data-imports.create'), false))
                    ->visible(fn() => $user->hasPermissionTo(ImportPermissions::Create)),
            ])
            ->query(DataImport::query())
            ->columns([
                TextColumn::make('entity')
                    ->label(__('Entity'))
                    ->badge()
                    ->searchable(),
                TextColumn::make('status')
                    ->label(__('Status'))
                    ->badge()
                    ->searchable(),
                TextColumn::make('file')
                    ->label(__('File'))
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Action::make('show')
                    ->label(__('Show'))
                    ->action(fn(DataImport $record) => $this->redirect(route('data-imports.show', $record), false))
                    ->button()
                    ->icon('heroicon-s-eye')
                    ->color('info')
                    ->visible(fn(): bool => $user->hasPermissionTo(ImportPermissions::View)),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    //
                ]),
            ]);
    }

    public function render(): View
    {
        return view('livewire.data-imports.list-imports');
    }
}
