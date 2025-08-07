<?php

namespace App\Livewire\Dashboard\Pages\Config;

use App\Models\Config;
use Livewire\Attributes\Title;
use Livewire\Component;

class ConfigList extends Component
{

    public string $model;

    public function mount()
    {
        $this->model = Config::class;
    }

    #[Title('Configurações')]
    public function render()
    {
        return view('livewire.dashboard.pages.config.config-list')->layout('livewire.dashboard.layouts.app');
    }
}
