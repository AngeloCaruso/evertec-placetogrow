<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Enums\Gateways\GatewayType;
use App\Enums\System\IdTypes;
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
            'slug' => $this->slug,
            'name' => $this->name,
            'logo' => Storage::url($this->logo),
            'categories' => explode(',', $this->categories),
            'currency' => $this->currency,
            'expiration_payment_time' => $this->expiration_payment_time,
            'type' => $this->type->value,
            'active' => $this->active,
            'details_url' => route('public.microsite.show', ['microsite' => $this->slug]),
            'primary_color' => $this->primary_color,
            'accent_color' => $this->accent_color,
            'show' => true,
            'gateways' => GatewayType::values(),
            'form_fields' => empty($this->form_fields) ? [] : MicrositeFormFieldsResource::collection($this->form_fields),
            'is_paid_monthly' => $this->is_paid_monthly,
            'is_paid_yearly' => $this->is_paid_yearly,
            'document_types' => IdTypes::values(),
            'plans' => empty($this->plans) ? [] : MicrositeSubscriptionPlanResource::collection($this->plans),
        ];
    }
}
