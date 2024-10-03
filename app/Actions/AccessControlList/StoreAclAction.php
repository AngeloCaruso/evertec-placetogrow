<?php

declare(strict_types=1);

namespace App\Actions\AccessControlList;

use App\Models\AccessControlList;
use Illuminate\Database\Eloquent\Model;

class StoreAclAction
{
    public static function exec(array $data, Model $model): Model
    {
        $acls = array_map(
            fn($id) => [
                'user_id' => $data['user_id'],
                'rule' => $data['rule'],
                'controllable_id' => $id,
                'controllable_type' => $data['controllable_type'],
                'created_at' => now(),
                'updated_at' => now(),
            ],
            $data['controllable_id'],
        );

        AccessControlList::insert($acls);

        return $model;
    }
}
