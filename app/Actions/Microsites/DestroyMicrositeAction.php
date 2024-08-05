<?php

declare(strict_types=1);

namespace App\Actions\Microsites;

use Illuminate\Database\Eloquent\Model;

class DestroyMicrositeAction
{
    public static function exec(array $data, Model $model): bool | null
    {
        return $model->delete();
    }
}
