<?php

declare(strict_types=1);

namespace App\Actions\Microsites;

use App\Enums\System\AccessRules;
use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class GetAllMicrositesWithAclAction
{
    public static function exec($user, Model $model): Builder
    {
        return $model->query()->whereIn('id', self::getIds($user->acl));
    }

    private static function getIds($acl): array
    {
        $groupedAcl = $acl->groupBy('rule');
        $allowedIds = self::applyAllow($groupedAcl);
        $deniedIds = self::applyDeny($groupedAcl);
        return $allowedIds->diff($deniedIds)->toArray();
    }

    private static function applyAllow($acl): Collection
    {
        if (!$acl->has(AccessRules::Allow->value)) {
            return collect([]);
        }
        return $acl->get(AccessRules::Allow->value)->pluck('controllable_id');
    }

    private static function applyDeny($acl): Collection
    {
        if (!$acl->has(AccessRules::Deny->value)) {
            return collect([]);
        }
        return $acl->get(AccessRules::Deny->value)->pluck('controllable_id');
    }
}
