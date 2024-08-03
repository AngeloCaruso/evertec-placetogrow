<?php

namespace App\Actions\Payments;

use Illuminate\Database\Eloquent\Model;

class UpdatePaymentAction
{
    public static function exec(Model $model)
    {
        $model->update();
        return $model;
    }
}
