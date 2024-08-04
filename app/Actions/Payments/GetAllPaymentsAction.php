<?php

namespace App\Actions\Payments;

use Illuminate\Database\Eloquent\Model;

class GetAllPaymentsAction
{
    public static function exec(array $data, Model $model)
    {
        return $model->query()->get();
    }
}
