<?php

namespace App\Livewire\Dashboard\Pages\User;

use App\Models\User;
use Livewire\Attributes\Title;
use Livewire\Component;

class UserList extends Component
{
    public string $model;

    public function mount()
    {
        // if (!auth()->user()->is_admin) {
        //     return redirect()->route('dashboard.user.edit', ['id' => auth()->user()->id]);
        // }

        $this->model = User::class;
    }

    #[Title('Usuários')]
    public function render()
    {
        return view('livewire.dashboard.pages.user.user-list')->layout('livewire.dashboard.layouts.app');
    }
}
