<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MicrositeSubscriptionPlanResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'name' => $this['name'],
            'price_monthly' => $this['price_monthly'],
            'price_yearly' => $this['price_yearly'],
            'features' => explode(',', $this['features']),
            'featured' => $this['featured'],
        ];
    }
}
