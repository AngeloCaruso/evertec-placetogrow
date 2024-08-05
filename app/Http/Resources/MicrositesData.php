<?php

namespace App\Http\Resources;

use App\Actions\Microsites\GetAllMicrositesAction;
use App\Models\Microsite;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MicrositesData extends JsonResource
{
    public function toArray(Request $request): array
    {
        $groupedSites = $this->resource->groupBy('type');
        return [
            'site_types' => $groupedSites->map(fn ($sites, $type) => [
                'name' => $type,
                'total' => $sites->count(),
            ])->values(),
            'total' => GetAllMicrositesAction::exec([], new Microsite())->count(),
        ];
    }
}
