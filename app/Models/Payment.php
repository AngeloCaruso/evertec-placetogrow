<?php

namespace App\Models;

use App\Enums\Gateways\GatewayType;
use App\Enums\Microsites\MicrositeCurrency;
use App\Enums\System\IdTypes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'microsite_id',
        'id_type',
        'id_number',
        'name',
        'last_name',
        'email',
        'phone',
        'gateway',
        'gateway_status',
        'reference',
        'description',
        'amount',
        'currency',
        'return_url',
        'payment_url',
        'expires_at',
    ];

    protected $casts = [
        'id_type' => IdTypes::class,
        'gateway' => GatewayType::class,
        'currency' => MicrositeCurrency::class,
        'expires_at' => 'datetime',
    ];

    public function getRouteKeyName(): string
    {
        return 'reference';
    }

    public function fullName(): Attribute
    {
        return Attribute::make(
            get: fn () => "{$this->name} {$this->last_name}",
        );
    }

    public function status(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->gateway->getGatewayStatuses()::tryFrom($this->gateway_status),
        );
    }

    public function amountCurrency(): Attribute
    {
        return Attribute::make(
            get: fn () => number_format($this->amount) . ' ' . $this->currency->value,
        );
    }

    public function microsite()
    {
        return $this->belongsTo(Microsite::class);
    }
}
