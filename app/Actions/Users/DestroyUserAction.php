<?php

namespace App\Actions\Users;

use App\Actions\BaseActionInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class DestroyUserAction implements BaseActionInterface
{
    public function exec(Request $request, Model $model): mixed
    {
        return $model->delete();
    }
}
