<?php

declare(strict_types=1);

namespace App\Livewire\Subscriptions;

use App\Http\Resources\MicrositeResource;
use App\Http\Resources\SubscriptionResource;
use App\Models\Subscription;
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

class ShowSubscription extends Component implements HasForms
{
    use InteractsWithForms;

    public ?array $data = [];

    public Subscription $subscription;

    public function mount(): void
    {
        $this->form->fill($this->subscription->attributesToArray());
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Split::make([
                    Group::make()
                        ->schema([
                            Section::make(__('Payment Info'))
                                ->columns(2)
                                ->schema([
                                    TextInput::make('microsite_id')->label(__('Microsite'))
                                        ->formatStateUsing(fn () => $this->subscription->microsite->name),
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
                        ->view('livewire.subscriptions.views.invoice', [
                            'subscription' => (new SubscriptionResource($this->subscription))->response()->getData(),
                            'site' => (new MicrositeResource($this->subscription->microsite))->response()->getData(),
                        ]),
                ])
            ])
            ->statePath('data')
            ->model($this->subscription);
    }

    public function render(): View
    {
        return view('livewire.subscriptions.show-subscription');
    }
}
