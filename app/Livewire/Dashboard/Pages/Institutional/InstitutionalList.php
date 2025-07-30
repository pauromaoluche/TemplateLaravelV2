<?php

namespace App\Livewire\Dashboard\Pages\Institutional;

use App\Models\Institutional;
use Illuminate\Support\Facades\Route;
use Livewire\Component;

class InstitutionalList extends Component
{

    public string $model;

    public function mount()
    {
        $this->model = Institutional::class;
    }

    public function render()
    {
        return view('livewire.dashboard.pages.institutional.institutional-list')->layout('livewire.dashboard.layouts.app');
    }
}
