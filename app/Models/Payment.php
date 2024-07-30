<?php

namespace App\Models;

use App\Enums\Gateways\GatewayType;
use App\Enums\Microsites\MicrositeCurrency;
use App\Enums\System\IdTypes;
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

    public function microsite()
    {
        return $this->belongsTo(Microsite::class);
    }
}
