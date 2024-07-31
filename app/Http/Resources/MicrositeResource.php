<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class MicrositeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'logo' => Storage::url($this->logo),
            'categories' => $this->categories,
            'currency' => $this->currency,
            'expiration_payment_time' => $this->expiration_payment_time,
            'type' => $this->type,
            'active' => $this->active,
            'details_url' => route('public.microsite.show', ['microsite' => $this->slug]),
        ];
    }
}
