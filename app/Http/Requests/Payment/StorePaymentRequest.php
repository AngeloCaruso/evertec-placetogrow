<?php

declare(strict_types=1);

namespace App\Http\Requests\Payment;

use App\Enums\Gateways\GatewayType;
use App\Enums\Microsites\MicrositeCurrency;
use App\Enums\Microsites\MicrositeFormFieldTypes;
use App\Enums\Microsites\MicrositeType;
use App\Models\Microsite;
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
        $validations = [
            'microsite_id' => 'required|integer|exists:microsites,id',
            'payment_data' => 'array',
            'email' => 'required|email',
            'amount' => 'required|numeric|min:1',
            'gateway' => ['required', 'string', Rule::enum(GatewayType::class)],
            'currency' => ['required', 'string', Rule::enum(MicrositeCurrency::class)],
            'description' => 'string|max:500',
        ];

        $microsite = Microsite::find($this->microsite_id);

        if ($microsite->type === MicrositeType::Billing) {
            $validations['reference'] = 'required|exists:payments,reference';
        }

        if ($this->payment_data) {
            foreach ($this->payment_data as $index => $field) {
                $validations["payment_data.{$index}.id"] = 'string';
                $validations["payment_data.{$index}.name"] = 'string';
                $validations["payment_data.{$index}.value"] = $this->loadDefaultRules($field);
            }
        }

        return $validations;
    }

    private function loadDefaultRules(array $field): array
    {
        $rules = $field['rules'] ? explode('|', $field['rules']) : [];
        $rules[] = $field['required'] ? 'required' : 'nullable';

        return array_merge($rules, MicrositeFormFieldTypes::from($field['type'])->getDefaultRules($field['options'] ?? []));
    }
}
