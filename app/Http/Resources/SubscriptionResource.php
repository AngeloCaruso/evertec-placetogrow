<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SubscriptionResource extends JsonResource
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
            'email' => $this->email,
            'reference' => $this->reference,
            'amount' => number_format((float) $this->amount),
            'currency' => $this->currency,
            'gateway_status' => $this->gateway_status,
            'status_label' => $this->status->getLabel(),
            'date' => $this->created_at->format('d/m/Y H:i A'),
        ];
    }
}
