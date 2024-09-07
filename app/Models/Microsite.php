<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\Microsites\MicrositeType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Microsite extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'logo',
        'categories',
        'currency',
        'expiration_payment_time',
        'type',
        'form_fields',
        'primary_color',
        'is_paid_monthtly',
        'is_paid_yearly',
        'charge_collect',
        'plans',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
        'is_paid_monthtly' => 'boolean',
        'is_paid_yearly' => 'boolean',
        'plans' => 'array',
        'form_fields' => 'array',
        'type' => MicrositeType::class,
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function scopeActive(Builder $query): void
    {
        $query->where('active', true);
    }

    public function scopeType(Builder $query, $type): void
    {
        if (!empty($type) && MicrositeType::tryFrom(strtolower($type))) {
            $query->where('type', $type);
        }
    }

    public function scopeSearch(Builder $query, $search): void
    {
        if (!empty($search)) {
            $query->where('name', 'like', "%$search%");
        }
    }
}
