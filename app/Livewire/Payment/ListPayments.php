<?php

namespace App\Livewire\Payment;

use App\Enums\Payments\PaymentPermissions;
use App\Models\Payment;
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

class ListPayments extends Component implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    public function table(Table $table): Table
    {
        $user = auth()->user();
        return $table
            ->query(function () use ($user) {
                if ($user->is_admin) {
                    return Payment::query();
                }

                return Payment::query()->where('email', $user->email);
            })
            ->columns([
                ImageColumn::make('microsite.logo')
                    ->circular(),
                TextColumn::make('microsite.name')
                    ->label(__('Microsite'))
                    ->badge()
                    ->sortable(),
                TextColumn::make('full_name')
                    ->label(__('Full Name'))
                    ->searchable(),
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
                TextColumn::make('request_id')
                    ->label(__('Request ID'))
                    ->searchable(),
                TextColumn::make('gateway_status')
                    ->label(__('Status'))
                    ->badge()
                    ->color(fn (Payment $record) => $record->gateway->getGatewayStatuses()::tryFrom($record->gateway_status)->getColor())
                    ->icon(fn (Payment $record) => $record->gateway->getGatewayStatuses()::tryFrom($record->gateway_status)->getIcon())
                    ->searchable(),
                TextColumn::make('expires_at')
                    ->label(__('Expires At'))
                    ->dateTime()
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
                Action::make('details')
                    ->label(__('Details'))
                    ->action(fn (Payment $record) => $this->redirect(route('payments.show', $record), false))
                    ->button()
                    ->icon('heroicon-s-eye')
                    ->color('info')
                    ->visible(fn (): bool => auth()->user()->hasPermissionTo(PaymentPermissions::View)),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    //
                ]),
            ]);
    }

    public function render(): View
    {
        return view('livewire.payment.list-payments');
    }
}
