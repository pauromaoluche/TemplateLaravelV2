<?php

namespace App\Rules;
use Illuminate\Validation\Rule;

class ConfigRules
{
    public static function rules(?int $id = null): array
    {
        return [
            'title' => 'required|string',
            'value' => 'required|string',
            'code' => ['required', 'string', Rule::unique('configs', 'code')->ignore($id)],
            'active' => 'required|boolean',
        ];
    }
}
