<?php

namespace App\Http\Requests\Payment;

use App\Enums\Gateways\GatewayType;
use App\Enums\Microsites\MicrositeCurrency;
use App\Enums\System\IdTypes;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePaymentRequest extends FormRequest
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
            'id_type' => ['required', 'string', Rule::enum(IdTypes::class)],
            'id_number' => 'required|integer',
            'name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:255',

            'gateway' => ['required', 'string', Rule::enum(GatewayType::class)],
            'reference' => 'required|string|max:255',
            'description' => 'string',
            'amount' => 'required|numeric',
            'currency' => ['required', 'string', Rule::enum(MicrositeCurrency::class)],
            'return_url' => 'url',
            'payment_url' => 'url',
        ];
    }
}
