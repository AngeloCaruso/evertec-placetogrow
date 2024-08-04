<?php

namespace App\Actions\Users;

use App\Enums\System\AccessRules;
use Illuminate\Database\Eloquent\Model;

class GetAllUsersWithAclAction
{
    public static function exec($user, Model $model)
    {
        $acl = $user->acl()->where('controllable_type', $model::class)->get();
        return $model->query()->whereIn('id', self::getIds($acl));
    }

    private static function getIds($acl): array
    {
        $groupedAcl = $acl->groupBy('rule');
        $allowedIds = self::applyAllow($groupedAcl);
        $deniedIds = self::applyDeny($groupedAcl);
        return $allowedIds->diff($deniedIds)->toArray();
    }

    private static function applyAllow($acl)
    {
        if (!$acl->has(AccessRules::Allow->value)) {
            return collect([]);
        }
        return $acl->get(AccessRules::Allow->value)->pluck('controllable_id');
    }

    private static function applyDeny($acl)
    {
        if (!$acl->has(AccessRules::Deny->value)) {
            return collect([]);
        }
        return $acl->get(AccessRules::Deny->value)->pluck('controllable_id');
    }
}
