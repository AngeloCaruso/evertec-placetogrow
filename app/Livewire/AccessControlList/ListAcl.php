<?php

namespace App\Livewire\AccessControlList;

use App\Actions\AccessControlList\DestroyAclAction;
use App\Enums\Acl\AccessControlListPermissions;
use App\Models\AccessControlList;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Livewire\Component;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;

class ListAcl extends Component implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    public function table(Table $table): Table
    {
        $user = auth()->user();

        return $table
            ->heading(__('ACL'))
            ->description(__('ACL for users'))
            ->query(AccessControlList::query())
            ->defaultGroup('user.name')
            ->headerActions([
                Action::make('create')
                    ->label(__('Create ACL'))
                    ->icon('heroicon-o-plus')
                    ->action(fn () => $this->redirect(route('acl.create'), false))
                    ->visible(fn () => $user->hasPermissionTo(AccessControlListPermissions::Create)),
            ])
            ->columns([
                TextColumn::make('user.name')
                    ->label(__('User'))
                    ->sortable(),
                TextColumn::make('rule')
                    ->label(__('Rule'))
                    ->badge(),
                TextColumn::make('controllable_type')
                    ->label(__('Entity Type'))
                    ->searchable()
                    ->badge(),
                TextColumn::make('controllable.name')
                    ->label(__('Entity'))
                    ->sortable(),
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
                    ->label(__('Edit'))
                    ->action(fn (AccessControlList $record) => $this->redirect(route('acl.edit', $record), false))
                    ->button()
                    ->icon('heroicon-s-pencil-square')
                    ->color('info')
                    ->visible(fn (): bool => $user->hasPermissionTo(AccessControlListPermissions::Update)),
                Action::make('delete')
                    ->label(__('Delete'))
                    ->requiresConfirmation()
                    ->icon('heroicon-s-trash')
                    ->color('danger')
                    ->button()
                    ->visible(fn (): bool => $user->hasPermissionTo(AccessControlListPermissions::Delete))
                    ->action(fn (AccessControlList $record) => DestroyAclAction::exec([], $record)),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    BulkAction::make('delete')
                        ->requiresConfirmation()
                        ->action(fn (Collection $records) => $records->each(fn (AccessControlList $record) => DestroyAclAction::exec([], $record)))
                ]),
            ]);
    }

    public function render(): View
    {
        return view('livewire.access-control-list.list-acl');
    }
}
