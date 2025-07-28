<?php

namespace App\Livewire\Web;

use App\Livewire\Forms\Web\AuthForm;
use App\Services\AuthService;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class Auth extends Component
{

    public AuthForm $form;

    public bool $remember = false;

    public function login(AuthService $authService)
    {
        $this->form->validate();

        $loggedIn = $authService->login(
            [
                'email' => $this->form->email,
                'password' => $this->form->password,
            ],
            $this->remember
        );

        if (!$loggedIn) {
            throw ValidationException::withMessages([
                'form.error' => 'E-mail ou senha inválidos.',
            ]);
        }
        return redirect()->to(route('dashboard.index'));
    }

    public function render()
    {
        return view('livewire.web.auth');
    }
}
