<?php

namespace App\Livewire\Microsites;

use App\Models\Microsite;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Livewire\Component;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class ListMicrosites extends Component implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    public function table(Table $table): Table
    {
        return $table
            ->heading('Microsites')
            ->description('List of all microsites')
            ->headerActions([
                Action::make('create')
                    ->label('Create Microsite')
                    ->icon('heroicon-o-plus')
                    ->url(route('microsites.create')),
            ])
            ->query(Microsite::query())
            ->columns([
                ImageColumn::make('logo')
                    ->circular(),
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('category')
                    ->searchable(),
                TextColumn::make('payment_config')
                    ->searchable(),
                TextColumn::make('type')
                    ->badge(),
                IconColumn::make('active')
                    ->boolean(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Action::make('edit')
                    ->url(fn (Microsite $record): string => route('microsites.edit', $record))
                    ->button()
                    ->icon('heroicon-s-pencil-square')
                    ->color('info'),
                Action::make('delete')
                    ->requiresConfirmation()
                    ->icon('heroicon-s-trash')
                    ->color('danger')
                    ->button()
                    ->action(fn (Microsite $record) => $record->delete())
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    BulkAction::make('delete')
                        ->requiresConfirmation()
                        ->action(fn (Collection $records) => $records->each->delete())
                ]),
            ]);
    }

    public function render(): View
    {
        return view('livewire.microsites.list-microsites');
    }
}
