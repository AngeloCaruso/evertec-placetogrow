<?php

namespace App\Livewire\Microsites;

use App\Actions\Microsites\DestroyMicrositeAction;
use App\Enums\Microsites\MicrositePermissions;
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
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\App;

class ListMicrosites extends Component implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    public function table(Table $table): Table
    {
        return $table
            ->heading(__('Microsites'))
            ->description(__('List of all microsites'))
            ->headerActions([
                Action::make('create')
                    ->label(__('Create Microsite'))
                    ->icon('heroicon-o-plus')
                    ->action(fn () => $this->redirect(route('microsites.create'), true)),
            ])
            ->query(function (): mixed {
                $query = Microsite::query();
                $user = auth()->user();

                if (!$user->isAdmin()) {
                    $query->where('id', $user->microsite_id);
                }

                return $query;
            })
            ->columns([
                ImageColumn::make('logo')
                    ->circular(),
                TextColumn::make('name')
                    ->label(__('Name'))
                    ->searchable(),
                TextColumn::make('categories')
                    ->label(__('Categories'))
                    ->searchable()
                    ->badge()
                    ->color('info')
                    ->separator(','),
                TextColumn::make('currency')
                    ->label(__('Currency'))
                    ->badge(),
                TextColumn::make('expiration_payment_time')
                    ->label(__('Expiration Time'))
                    ->suffix(__(' Hours')),
                TextColumn::make('type')
                    ->label(__('Type'))
                    ->badge(),
                IconColumn::make('active')
                    ->label(__('Active'))
                    ->boolean(),
                TextColumn::make('created_at')
                    ->label(__('Creation Date'))
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('updated_at')
                    ->label(__('Last Update'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Action::make('edit')
                    ->label(__('Edit'))
                    ->action(fn (Microsite $record) => $this->redirect(route('microsites.edit', $record), true))
                    ->button()
                    ->icon('heroicon-s-pencil-square')
                    ->color('info')
                    ->hidden(fn (Microsite $record): bool => !auth()->user()->can(MicrositePermissions::Update->value, $record)),
                Action::make('delete')
                    ->label(__('Delete'))
                    ->requiresConfirmation()
                    ->icon('heroicon-s-trash')
                    ->color('danger')
                    ->button()
                    ->hidden(fn (Microsite $record): bool => !auth()->user()->can(MicrositePermissions::Delete->value, $record))
                    ->action(fn (Microsite $record) => DestroyMicrositeAction::exec([], $record))
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    BulkAction::make('delete')
                        ->requiresConfirmation()
                        ->action(fn (Collection $records) => $records->each(fn (Microsite $record) => DestroyMicrositeAction::exec([], $record)))
                ]),
            ]);
    }

    public function render(): View
    {
        return view('livewire.microsites.list-microsites');
    }
}
