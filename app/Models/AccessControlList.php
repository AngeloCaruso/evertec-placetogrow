<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\Acl\ControllableTypes;
use App\Enums\System\AccessRules;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class AccessControlList extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'rule',
        'controllable_id',
        'controllable_type',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'controllable_type' => ControllableTypes::class,
        'rule' => AccessRules::class
    ];

    public function controllable(): MorphTo
    {
        return $this->morphTo();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
