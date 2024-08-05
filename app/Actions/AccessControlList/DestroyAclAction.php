<?php

declare(strict_types=1);

namespace App\Actions\AccessControlList;

use Illuminate\Database\Eloquent\Model;

class DestroyAclAction
{
    public static function exec(array $data, Model $model): bool
    {
        return $model->delete();
    }
}
