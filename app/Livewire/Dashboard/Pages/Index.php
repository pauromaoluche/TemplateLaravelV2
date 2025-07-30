<?php

namespace App\Livewire\Dashboard\Pages;

use Livewire\Attributes\Title;
use Livewire\Component;

class Index extends Component
{
    #[Title('Dashboard')]
    public function render()
    {
        return view('livewire.dashboard.pages.index')->layout('livewire.dashboard.layouts.app');
    }
}
