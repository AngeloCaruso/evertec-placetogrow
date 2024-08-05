<?php

declare(strict_types=1);

namespace App\Actions\Users;

use Illuminate\Database\Eloquent\Model;

class DestroyUserAction
{
    public static function exec(array $data, Model $model): bool
    {
        return $model->delete();
    }
}
