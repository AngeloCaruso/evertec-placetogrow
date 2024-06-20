<?php

namespace App\Actions;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

interface BaseActionInterface
{
    public function exec(Request $request, Model $model): mixed;
}
