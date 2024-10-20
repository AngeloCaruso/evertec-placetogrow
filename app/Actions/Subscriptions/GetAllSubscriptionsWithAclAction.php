<?php

declare(strict_types=1);

namespace App\Actions\Subscriptions;

use App\Enums\System\AccessRules;
use App\Models\Microsite;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class GetAllSubscriptionsWithAclAction
{
    public static function exec(User $user, Model $model): Builder
    {
        if ($user->is_admin) {
            return $model->query();
        }

        $acl = $user->acl()->where('controllable_type', Microsite::class)->get();

        if ($acl->isNotEmpty()) {
            return $model->query()->whereIn('microsite_id', self::getIds($acl));
        }

        return Subscription::query()->where('email', $user->email);
    }

    private static function getIds(EloquentCollection $acl): array
    {
        $groupedAcl = $acl->groupBy('rule');
        $allowedIds = self::applyAllow($groupedAcl);
        $deniedIds = self::applyDeny($groupedAcl);

        return $allowedIds->diff($deniedIds)->toArray();
    }

    private static function applyAllow(EloquentCollection $acl): Collection
    {
        if (!$acl->has(AccessRules::Allow->value)) {
            return collect([]);
        }
        return $acl->get(AccessRules::Allow->value)->pluck('controllable_id');
    }

    private static function applyDeny(EloquentCollection $acl): Collection
    {
        if (!$acl->has(AccessRules::Deny->value)) {
            return collect([]);
        }
        return $acl->get(AccessRules::Deny->value)->pluck('controllable_id');
    }
}
