<?php

namespace App\Actions\Microsites;

use Illuminate\Database\Eloquent\Model;

class GetAllMicrositesAction
{
    public static function exec(array $data, Model $model): mixed
    {
        return $model->query()
            ->active()
            ->type($data['type'] ?? null)
            ->search($data['search'] ?? null)
            ->get();
    }
}
