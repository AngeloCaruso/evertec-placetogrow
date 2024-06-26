<?php

namespace App\Actions\Microsites;

use App\Actions\BaseActionInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class DestroyMicrositeAction implements BaseActionInterface
{
    public function exec(Request $request, Model $model): mixed
    {
        return $model->delete();
    }
}
