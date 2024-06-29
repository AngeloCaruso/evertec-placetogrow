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
        return $table
            ->heading('Roles')
            ->description('Manage all roles')
            ->headerActions([
                Action::make('create')
                    ->label('Create new Role')
                    ->icon('heroicon-o-plus')
                    ->url(route('roles.create')),
            ])
            ->query(Role::query())
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('guard_name')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
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
                    ->url(fn (Role $record): string => route('roles.edit', $record))
                    ->button()
                    ->icon('heroicon-s-pencil-square')
                    ->color('info')
                    ->hidden(fn (Role $record): bool => !auth()->user()->can(RolePermissions::Update->value, $record)),
                Action::make('delete')
                    ->requiresConfirmation()
                    ->icon('heroicon-s-trash')
                    ->color('danger')
                    ->button()
                    ->hidden(fn (Role $record): bool => !auth()->user()->can(RolePermissions::Delete->value, $record))
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
