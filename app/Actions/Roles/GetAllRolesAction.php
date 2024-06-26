<?php

namespace App\Actions\Roles;

use App\Actions\BaseActionInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class GetAllRolesAction implements BaseActionInterface
{
    public function exec(Request $request, Model $model): mixed
    {
        return $model->query()->get();
    }
}
