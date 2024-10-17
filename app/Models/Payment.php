<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\Gateways\GatewayType;
use App\Enums\Microsites\MicrositeCurrency;
use App\Enums\Microsites\MicrositeType;
use App\Enums\Payments\PaymentType;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'microsite_id',
        'payment_data',
        'payment_type',
        'email',
        'gateway',
        'gateway_status',
        'reference',
        'description',
        'amount',
        'penalty_fee',
        'currency',
        'limit_date',
        'return_url',
        'payment_url',
        'expires_at',
    ];

    protected $casts = [
        'amount' => 'float',
        'penalty_fee' => 'float',
        'payment_data' => 'array',
        'gateway' => GatewayType::class,
        'currency' => MicrositeCurrency::class,
        'payment_type' => PaymentType::class,
        'expires_at' => 'datetime',
        'limit_date' => 'date',
    ];

    public function getRouteKeyName(): string
    {
        return 'reference';
    }

    public function scopeType($query, ?MicrositeType $type): void
    {
        if ($type) {
            $query->whereHas('microsite', fn($q) => $q->where('type', $type->value));
        }
    }

    public function fullName(): Attribute
    {
        return Attribute::make(
            get: fn() => "{$this->name} {$this->last_name}",
        );
    }

    public function status(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->gateway?->getGatewayStatuses()::tryFrom($this->gateway_status),
        );
    }

    public function statusIsPending(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->status->value === $this->gateway->getGatewayStatuses()::Pending->value,
        );
    }

    public function amountCurrency(): Attribute
    {
        return Attribute::make(
            get: fn() => number_format($this->amount) . ' ' . $this->currency->value,
        );
    }

    public function daysOverdue(): Attribute
    {
        return Attribute::make(
            get: fn() => max(0, $this->limit_date?->diffInDays(now()->format('Y-m-d')) ?? 0),
        );
    }

    public function penaltyAmout(): Attribute
    {
        return Attribute::make(
            get: function () {
                $penalty = $this->days_overdue * $this->microsite->penalty_fee;
                return $this->microsite->penalty_is_percentage ? $this->amount * ($penalty / 100) : $penalty;
            },
        );
    }

    public function totalAmount(): Attribute
    {
        return Attribute::make(
            get: function () {
                return $this->amount + $this->penalty_amout;
            },
        );
    }

    public function isPaid(): Attribute
    {
        return Attribute::make(
            get: function () {
                return $this->gateway_status && $this->gateway_status === $this->gateway->getGatewayStatuses()::Approved->value;
            },
        );
    }

    public function isRejected(): Attribute
    {
        return Attribute::make(
            get: function () {
                return $this->gateway_status && $this->gateway_status === $this->gateway->getGatewayStatuses()::Rejected->value;
            },
        );
    }

    public function isExpired(): Attribute
    {
        return Attribute::make(
            get: function () {
                return $this->limit_date?->isBefore(now()->format('Y-m-d'));
            },
        );
    }

    public function microsite(): BelongsTo
    {
        return $this->belongsTo(Microsite::class);
    }
}
