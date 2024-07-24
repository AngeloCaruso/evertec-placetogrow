<?php

namespace App\Livewire\Roles;

use App\Actions\Roles\DestroyRoleAction;
use App\Enums\Roles\RolePermissions;
use App\Models\Role;
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

class ListRoles extends Component implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    public function table(Table $table): Table
    {
        $user = auth()->user();
        return $table
            ->heading(__('Roles'))
            ->description(__('Manage system roles'))
            ->headerActions([
                Action::make('create')
                    ->label(__('Create new Role'))
                    ->icon('heroicon-o-plus')
                    ->action(fn () => $this->redirect(route('roles.create'), false))
                    ->visible(fn () => $user->hasAnyPermission([RolePermissions::Create])),
            ])
            ->query(Role::query())
            ->columns([
                TextColumn::make('name')
                    ->label(__('Name'))
                    ->searchable(),
                TextColumn::make('guard_name')
                    ->searchable(),
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
                Action::make('view')
                    ->label(__('View'))
                    ->action(fn (Role $record) => $this->redirect(route('roles.show', $record), false))
                    ->button()
                    ->icon('heroicon-s-eye')
                    ->color('info')
                    ->visible(fn (): bool => $user->hasPermissionTo(RolePermissions::View)),
                Action::make('edit')
                    ->label(__('Edit'))
                    ->action(fn (Role $record) => $this->redirect(route('roles.edit', $record), false))
                    ->button()
                    ->icon('heroicon-s-pencil-square')
                    ->color('info')
                    ->visible(fn (): bool => $user->hasPermissionTo(RolePermissions::Update)),
                Action::make('delete')
                    ->label(__('Delete'))
                    ->requiresConfirmation()
                    ->icon('heroicon-s-trash')
                    ->color('danger')
                    ->button()
                    ->visible(fn (Role $record): bool => $user->can(RolePermissions::Delete->value, $record))
                    ->action(fn (Role $record) => DestroyRoleAction::exec([], $record)),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    BulkAction::make('delete')
                        ->requiresConfirmation()
                        ->action(fn (Collection $records) => $records->each(fn (Role $record) => DestroyRoleAction::exec([], $record))),
                ]),
            ]);
    }

    public function render(): View
    {
        return view('livewire.roles.list-roles');
    }
}
