<?php

namespace App\Models;

use App\Enums\Imports\ImportEntity;
use App\Enums\Imports\ImportStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataImport extends Model
{
    use HasFactory;

    protected $fillable = [
        'entity',
        'status',
        'file',
        'errors',
    ];

    protected $casts = [
        'entity' => ImportEntity::class,
        'status' => ImportStatus::class,
        'errors' => 'array',
    ];
}
