<?php

declare(strict_types=1);

namespace App\Actions\AccessControlList;

use App\Enums\System\AccessRules;
use Illuminate\Database\Eloquent\Model;

class ApplyAclAction
{
    public static function exec($user, Model $model): bool
    {
        if ($user->is_admin) {
            return true;
        }

        $acl = $user->acl()
            ->where('controllable_type', $model::class)
            ->where('controllable_id', $model->id)
            ->get()
            ->groupBy('rule');

        return $acl->has(AccessRules::Allow->value) && !$acl->has(AccessRules::Deny->value);
    }
}
