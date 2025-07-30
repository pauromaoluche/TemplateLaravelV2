<?php

namespace App\Livewire\Dashboard\Pages\Institutional;

use App\Models\Institutional;
use Livewire\Component;

class InstitutionalForm extends Component
{
    public string $model;
    public array $columns = [];

    public function mount()
    {
        $this->model = Institutional::class;
        $modelInstance = new $this->model;
        $this->columns = $modelInstance->getTableColumnTypesSimple();
    }

    public function render()
    {
        return view('livewire.dashboard.pages.institutional.institutional-form')->layout('livewire.dashboard.layouts.app');
    }
}
