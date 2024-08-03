<?php

namespace App\Actions\AccessControlList;

use App\Models\AccessControlList;
use Illuminate\Database\Eloquent\Model;

class StoreAclAction
{
    public static function exec(array $data, Model $model): mixed
    {
        $acls = array_map(
            fn ($id) => [
                'user_id' => $data['user_id'],
                'rule' => $data['rule'],
                'controllable_id' => $id,
                'controllable_type' => $data['controllable_type'],
                'created_at' => now(),
                'updated_at' => now(),
            ],
            $data['controllable_id']
        );

        $record = AccessControlList::insert($acls);

        return $model;
    }
}
