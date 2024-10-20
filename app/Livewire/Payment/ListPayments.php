<?php

declare(strict_types=1);

namespace App\Livewire\Payment;

use App\Actions\Payments\GetAllPaymentsWithAclAction;
use App\Enums\Payments\PaymentPermissions;
use App\Models\Payment;
use Filament\Tables\Actions\Action;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Livewire\Component;
use Illuminate\Contracts\View\View;

class ListPayments extends Component implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    public function table(Table $table): Table
    {
        $user = auth()->user();
        return $table
            ->headerActions([])
            ->query(function () use ($user) {
                return GetAllPaymentsWithAclAction::exec($user, new Payment());
            })
            ->columns([
                ImageColumn::make('microsite.logo')
                    ->circular(),
                TextColumn::make('microsite.name')
                    ->label(__('Microsite'))
                    ->badge()
                    ->sortable(),
                TextColumn::make('email')
                    ->searchable(),
                TextColumn::make('gateway')
                    ->label(__('Gateway'))
                    ->badge()
                    ->color('info'),
                TextColumn::make('reference')
                    ->label(__('Reference'))
                    ->searchable(),
                TextColumn::make('amount_currency')
                    ->label(__('Amount'))
                    ->sortable(),
                TextColumn::make('gateway_status')
                    ->label(__('Status'))
                    ->badge()
                    ->color(fn(Payment $record) => $record->gateway->getGatewayStatuses()::tryFrom($record->gateway_status)->getColor())
                    ->icon(fn(Payment $record) => $record->gateway->getGatewayStatuses()::tryFrom($record->gateway_status)->getIcon())
                    ->searchable(),
                TextColumn::make('limit_date')
                    ->label(__('Expires At'))
                    ->formatStateUsing(fn($state) => $state->isToday() ? __('Today') : $state->diffForHumans())
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label(__('Creation Date'))
                    ->dateTime('d/m/Y H:i A')
                    ->sortable(),
                TextColumn::make('updated_at')
                    ->dateTime('d/m/Y H:i A')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->actions([
                Action::make('details')
                    ->label(__('Details'))
                    ->action(fn(Payment $record) => $this->redirect(route('payments.show', $record), false))
                    ->button()
                    ->icon('heroicon-s-eye')
                    ->color('info')
                    ->visible(fn(): bool => auth()->user()->hasPermissionTo(PaymentPermissions::View)),
            ]);
    }

    public function render(): View
    {
        return view('livewire.payment.list-payments');
    }
}
