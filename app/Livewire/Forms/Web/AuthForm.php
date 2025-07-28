<?php

namespace App\Livewire\Forms\Web;

use Livewire\Attributes\Rule;
use Livewire\Form;

class AuthForm extends Form
{
    #[Rule(['required', 'email'])]
    public $email = '';
    #[Rule(['required'])]
    public $password = '';

    public function messages(): array
    {
        return [
            'email.required' => 'O campo e-mail é obrigatório.',
            'email.email' => 'Informe um e-mail válido.',

            'password.required' => 'A senha é obrigatória.',
        ];
    }
}
