<?php

namespace App\Livewire\Forms\Dashboard;

use Illuminate\Validation\Rule;
use Livewire\Attributes\Validate;
use Livewire\Form;

class UserForm extends Form
{

    public ?int $userId = null;

    #[Validate]
    public ?string $name = '';
    #[Validate]
    public ?string $email = '';
    #[Validate]
    public bool $is_admin = false;
    #[Validate]
    public ?string $password = '';
    #[Validate]
    public ?string $password_confirmation = '';

    protected function rules(): array
    {

        $passwordRules = $this->userId ?
            ['nullable', 'min:8', 'confirmed'] :
            ['required', 'min:8', 'confirmed'];

        return [
            'name' => ['required', 'string'],
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($this->userId)
            ],
            'is_admin' => ['required', 'boolean'],
            'password' => $passwordRules,
        ];
    }
}
