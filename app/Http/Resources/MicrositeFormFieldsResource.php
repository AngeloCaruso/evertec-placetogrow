<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Enums\Microsites\MicrositeFormFieldTypes;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class MicrositeFormFieldsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $options = $this['select_options'] ?? [];
        $isSelect = $this['type'] === MicrositeFormFieldTypes::Select->value;

        if (is_string($options)) {
            $options = explode(',', $options);
        }

        if (class_exists($this['type']) && method_exists($this['type'], 'values')) {
            $isSelect = true;
            $options = $this['type']::values();
        }

        return [
            'id' => Str::slug($this['name'], '_'),
            'name' => $this['name'],
            'type' => $this['type'],
            'is_select' => $isSelect,
            'value' => '',
            'required' => $this['input_mandatory'],
            'options' => $options,
            'rules' => $this['input_rules'],
            'active' => $this['input_active'],
        ];
    }
}
