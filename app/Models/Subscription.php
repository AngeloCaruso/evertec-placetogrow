<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\Gateways\GatewayType;
use App\Enums\Microsites\MicrositeCurrency;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Subscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'email',
        'microsite_id',
        'subscription_name',
        'amount',
        'currency',
        'reference',
        'description',
        'gateway',
        'gateway_status',
        'request_id',
        'token',
        'sub_token',
        'return_url',
        'payment_url',
        'expires_at',
        'active',
        'additional_attributes',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'active' => 'boolean',
        'amount' => 'float',
        'additional_attributes' => 'array',
        'currency' => MicrositeCurrency::class,
        'gateway' => GatewayType::class,
    ];

    public function getRouteKeyName(): string
    {
        return 'reference';
    }

    public function microsite(): BelongsTo
    {
        return $this->belongsTo(Microsite::class);
    }

    public function status(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->gateway->getGatewayStatuses()::tryFrom($this->gateway_status),
        );
    }

    public function statusIsApproved(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->status == $this->gateway->getGatewayStatuses()::Approved,
        );
    }

    public function statusIsPending(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->status == $this->gateway->getGatewayStatuses()::Pending,
        );
    }

    public function amountCurrency(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->amount ? number_format($this->amount) . ' ' . $this->currency->value : null,
        );
    }
}
