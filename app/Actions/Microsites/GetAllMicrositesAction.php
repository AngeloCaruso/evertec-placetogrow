<?php

declare(strict_types=1);

namespace App\Actions\Microsites;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class GetAllMicrositesAction
{
    public static function exec(array $data, Model $model): Collection
    {
        return $model->query()
            ->active()
            ->type($data['type'] ?? null)
            ->search($data['search'] ?? null)
            ->get();
    }
}
