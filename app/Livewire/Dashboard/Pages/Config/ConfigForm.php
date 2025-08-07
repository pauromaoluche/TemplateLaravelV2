<?php

namespace App\Livewire\Dashboard\Pages\Config;

use App\Models\Config;
use Livewire\Attributes\Title;
use Livewire\Component;

class ConfigForm extends Component
{
    public string $model;
    public array $columns = [];
    public int $id;

    public function mount()
    {
        $this->model = Config::class;
        $modelInstance = new $this->model;
        $this->columns = $modelInstance->getTableColumnTypesSimple();
    }

    #[Title('Configurações - Editar')]
    public function render()
    {
        return view('livewire.dashboard.pages.config.config-form')->layout('livewire.dashboard.layouts.app');
    }
}
