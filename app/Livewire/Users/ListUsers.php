<?php

namespace App\Livewire\Users;

use App\Actions\Microsites\GetAllUsersWithAclAction;
use App\Actions\Users\DestroyUserAction;
use App\Actions\Users\GetAllUsersWithAclAction as UsersGetAllUsersWithAclAction;
use App\Enums\Users\UserPermissions;
use App\Models\User;
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

class ListUsers extends Component implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    public function table(Table $table): Table
    {
        $user = auth()->user();
        return $table
            ->heading(__('Users'))
            ->description(__('Manage system users'))
            ->headerActions([
                Action::make('create')
                    ->label(__('Create new User'))
                    ->icon('heroicon-o-plus')
                    ->action(fn () => $this->redirect(route('users.create'), false))
                    ->visible(fn () => $user->hasAnyPermission([UserPermissions::Create])),
            ])
            ->query(function () use ($user) {
                if ($user->is_admin) {
                    return User::query();
                }
                return UsersGetAllUsersWithAclAction::exec($user, new User());
            })
            ->columns([
                TextColumn::make('name')
                    ->label(__('Name'))
                    ->searchable(),
                TextColumn::make('email')
                    ->label(__('Email'))
                    ->searchable(),
                TextColumn::make('roles')
                    ->label(__('Roles'))
                    ->badge()
                    ->color('primary')
                    ->separator(',')
                    ->formatStateUsing(fn (User $record): string => $record->roles->pluck('name')->join(', ')),
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
                    ->action(fn (User $record) => $this->redirect(route('users.show', $record), false))
                    ->button()
                    ->visible(fn (User $record): bool => $record->id !== $user->id && $user->hasPermissionTo(UserPermissions::View))
                    ->icon('heroicon-s-eye')
                    ->color('info'),
                Action::make('edit')
                    ->label(__('Edit'))
                    ->action(fn (User $record) => $this->redirect(route('users.edit', $record), false))
                    ->button()
                    ->visible(fn (User $record): bool => $record->id !== $user->id && $user->hasPermissionTo(UserPermissions::Update))
                    ->icon('heroicon-s-pencil-square')
                    ->color('info'),
                Action::make('delete')
                    ->label(__('Delete'))
                    ->requiresConfirmation()
                    ->icon('heroicon-s-trash')
                    ->color('danger')
                    ->button()
                    ->visible(fn (User $record): bool => $record->id !== $user->id && $user->can(UserPermissions::Delete->value, $record))
                    ->action(fn (User $record) => DestroyUserAction::exec([], $record)),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    BulkAction::make('delete')
                        ->requiresConfirmation()
                        ->action(fn (Collection $records) => $records->each(fn (User $record) => DestroyUserAction::exec([], $record))),
                ]),
            ]);
    }

    public function render(): View
    {
        return view('livewire.users.list-users');
    }
}
