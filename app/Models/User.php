<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\System\DefaultRoles;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory;
    use Notifiable;
    use HasRoles;

    protected $fillable = [
        'name',
        'email',
        'microsite_id',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function microsite(): BelongsTo
    {
        return $this->belongsTo(Microsite::class);
    }

    public function acl(): HasMany
    {
        return $this->hasMany(AccessControlList::class);
    }

    public function isAdmin(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->hasRole(DefaultRoles::Admin),
        );
    }
}
