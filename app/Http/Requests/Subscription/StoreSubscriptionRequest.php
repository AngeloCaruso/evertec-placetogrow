<?php

declare(strict_types=1);

namespace App\Http\Requests\Subscription;

use App\Enums\Gateways\GatewayType;
use App\Enums\Microsites\MicrositeCurrency;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreSubscriptionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'microsite_id' => 'required|integer|exists:microsites,id',
            'email' => 'required|email',
            'subscription_name' => 'required|string',
            'amount' => 'nullable|numeric|min:1',
            'gateway' => ['required', 'string', Rule::enum(GatewayType::class)],
            'currency' => ['required', 'string', Rule::enum(MicrositeCurrency::class)],
            'description' => 'string|max:500',
            'additional_attributes' => 'array',
            'additional_attributes.name' => 'string|required',
            'additional_attributes.surname' => 'string|required',
            'additional_attributes.document' => 'string|required',
            'additional_attributes.document_type' => 'string|required',
        ];
    }
}
