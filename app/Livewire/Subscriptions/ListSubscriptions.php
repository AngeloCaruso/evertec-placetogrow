<?php

declare(strict_types=1);

namespace App\Livewire\Subscriptions;

use App\Actions\Subscriptions\CancelSubscriptionAction;
use App\Actions\Subscriptions\GetAllSubscriptionsWithAclAction;
use App\Enums\Subscriptions\SubscriptionPermissions;
use App\Models\Subscription;
use Filament\Tables\Actions\Action;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Livewire\Component;
use Illuminate\Contracts\View\View;

class ListSubscriptions extends Component implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    public function table(Table $table): Table
    {
        $user = auth()->user();

        return $table
            ->query(function () use ($user) {
                if ($user->is_admin) {
                    return Subscription::query();
                }

                return GetAllSubscriptionsWithAclAction::exec($user, new Subscription());
            })
            ->columns([
                ImageColumn::make('microsite.logo')
                    ->circular(),
                TextColumn::make('microsite.name')
                    ->label(__('Microsite'))
                    ->badge()
                    ->sortable(),
                TextColumn::make('reference')
                    ->label(__('Reference'))
                    ->searchable(),
                TextColumn::make('email')
                    ->searchable(),
                TextColumn::make('gateway')
                    ->label(__('Gateway'))
                    ->badge()
                    ->color('info'),
                TextColumn::make('subscription_name')
                    ->searchable(),
                TextColumn::make('amount_currency')
                    ->label(__('Amount'))
                    ->sortable(),
                TextColumn::make('active')
                    ->label(__('Active'))
                    ->badge()
                    ->formatStateUsing(function (Subscription $record) {
                        if ($record->active && $record->gateway_status === $record->gateway->getGatewayStatuses()::Pending->value) {
                            return __('Pending');
                        }

                        return $record->active ? __('Active') : __('Suspended');
                    })
                    ->color(function (Subscription $record) {
                        if ($record->active && $record->gateway_status === $record->gateway->getGatewayStatuses()::Pending->value) {
                            return 'warning';
                        }

                        return $record->active ? 'success' : 'danger';
                    })
                    ->icon(function (Subscription $record) {
                        if ($record->active && $record->gateway_status === $record->gateway->getGatewayStatuses()::Pending->value) {
                            return 'heroicon-s-clock';
                        }

                        return $record->active ? 'heroicon-s-check-circle' : 'heroicon-s-minus-circle';
                    })
                    ->searchable(),
                TextColumn::make('created_at')
                    ->label(__('Creation Date'))
                    ->dateTime('d/m/Y H:i A')
                    ->sortable(),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->actions([
                Action::make('details')
                    ->label(__('Details'))
                    ->action(fn(Subscription $record) => $this->redirect(route('subscriptions.show', $record), false))
                    ->button()
                    ->icon('heroicon-s-eye')
                    ->color('info')
                    ->visible(fn(): bool => auth()->user()->hasPermissionTo(SubscriptionPermissions::View)),
                Action::make('cancel')
                    ->label(__('Cancel subscription'))
                    ->requiresConfirmation()
                    ->icon('heroicon-s-trash')
                    ->color('danger')
                    ->button()
                    ->visible(fn(Subscription $record): bool => $record->active)
                    ->action(fn(Subscription $record) => CancelSubscriptionAction::exec([], $record))
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    //
                ]),
            ]);
    }

    public function render(): View
    {
        return view('livewire.subscriptions.list-subscriptions');
    }
}
