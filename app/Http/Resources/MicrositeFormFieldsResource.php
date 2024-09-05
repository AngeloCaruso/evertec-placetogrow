<?php

namespace App\Http\Resources;

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
        $type = $this['type'];

        if (is_string($options)) {
            $options = explode(',', $options);
        }

        if (class_exists($this['type']) && method_exists($this['type'], 'values')) {
            $type = 'select';
            $options = $this['type']::values();
        }

        return [
            'id' => Str::slug($this['name'], '_'),
            'name' => $this['name'],
            'type' => $type,
            'value' => '',
            'required' => $this['input_mandatory'],
            'options' => $options,
            'active' => $this['input_active'],
        ];
    }
}
