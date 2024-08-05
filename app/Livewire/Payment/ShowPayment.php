<?php

declare(strict_types=1);

namespace App\Livewire\Payment;

use App\Http\Resources\MicrositeResource;
use App\Http\Resources\PaymentResource;
use App\Models\Payment;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Split;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Livewire\Component;
use Illuminate\Contracts\View\View;

class ShowPayment extends Component implements HasForms
{
    use InteractsWithForms;

    public ?array $data = [];

    public Payment $payment;

    public function mount(): void
    {
        $this->form->fill($this->payment->attributesToArray());
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Split::make([
                    Group::make()
                        ->schema([
                            Section::make(__('User Info'))
                                ->columns(2)
                                ->schema([
                                    TextInput::make('id_type')->label(__('ID Type')),
                                    TextInput::make('id_number')->label(__('ID Number')),
                                    TextInput::make('name')->label(__('Name')),
                                    TextInput::make('last_name')->label(__('Last Name')),
                                    TextInput::make('email')->label(__('Email')),
                                    TextInput::make('phone')->label(__('Phone')),
                                ])
                                ->disabled(),
                            Section::make(__('Payment Info'))
                                ->columns(2)
                                ->schema([
                                    TextInput::make('microsite_id')->label(__('Microsite'))
                                        ->formatStateUsing(fn () => $this->payment->microsite->name),
                                    TextInput::make('request_id')->label(__('Request ID')),
                                    TextInput::make('reference')->label(__('Reference')),
                                    TextInput::make('gateway')->label(__('Gateway')),
                                    DateTimePicker::make('expires_at')->label(__('Expires At')),
                                    TextInput::make('gateway_status')->label(__('Status')),
                                    TextInput::make('amount')->label(__('Amount')),
                                    TextInput::make('currency')->label(__('Currency')),
                                    Textarea::make('description')->label(__('Description'))
                                        ->columnSpanFull(),
                                ])
                                ->disabled(),
                        ]),

                    Placeholder::make('Invoice')
                        ->view('livewire.payment.views.invoice', [
                            'payment' => (new PaymentResource($this->payment))->response()->getData(),
                            'site' => (new MicrositeResource($this->payment->microsite))->response()->getData(),
                        ]),
                ])
            ])
            ->statePath('data')
            ->model($this->payment);
    }

    public function render(): View
    {
        return view('livewire.payment.show-payment');
    }
}
