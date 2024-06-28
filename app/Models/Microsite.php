<?php

namespace App\Models;

use App\Enums\Microsites\MicrositeType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Microsite extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'logo',
        'categories',
        'currency',
        'expiration_payment_time',
        'type',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
        'type' => MicrositeType::class,
        'categories' => 'array',
    ];
}
