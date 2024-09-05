<?php

declare(strict_types=1);

namespace App\Http\Requests\Payment;

use App\Enums\Gateways\GatewayType;
use App\Enums\Microsites\MicrositeCurrency;
use App\Enums\System\IdTypes;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'microsite_id' => 'required|integer|exists:microsites,id',
            // 'id_type' => ['required', 'string', Rule::enum(IdTypes::class)],
            // 'id_number' => 'required|integer',
            // 'name' => 'required|string|max:255',
            // 'last_name' => 'required|string|max:255',
            // 'email' => 'required|email|max:255',
            // 'phone' => 'required|string|max:255',

            'payment_data' => 'required|array',

            'amount' => 'required|numeric|min:1',
            'gateway' => ['required', 'string', Rule::enum(GatewayType::class)],
            'currency' => ['required', 'string', Rule::enum(MicrositeCurrency::class)],
            'description' => 'string|max:500',
        ];
    }
}
