<?php

namespace App\Http\Requests;

use App\Enums\Microsites\MicrositeType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateMicrositeRequest extends FormRequest
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
            'name' => 'string',
            'logo' => 'string',
            'category' => 'string',
            'payment_config' => 'string',
            'type' => [Rule::enum(MicrositeType::class)],
        ];
    }
}
