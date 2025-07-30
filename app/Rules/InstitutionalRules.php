<?php

namespace App\Rules;

class InstitutionalRules
{
    public static function rules(): array
    {
        return [
            'title' => 'required|string',
            'value' => 'required|string',
            'code' => 'required|string|unique:institutionals,code',
            'active' => 'required|boolean',
        ];
    }
}
