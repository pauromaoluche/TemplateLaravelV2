<?php

namespace App\Rules;
use Illuminate\Validation\Rule;

class InstitutionalRules
{
    public static function rules(?int $id = null): array
    {
        return [
            'title' => 'required|string',
            'value' => 'required|string',
            'code' => ['required', 'string', Rule::unique('institutionals', 'code')->ignore($id)],
            'active' => 'required|boolean',
        ];
    }
}
