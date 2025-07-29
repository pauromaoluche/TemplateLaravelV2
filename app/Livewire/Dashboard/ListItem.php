<?php

namespace App\Livewire\Dashboard;

use Illuminate\Support\Collection;
use Livewire\Component;

class ListItem extends Component
{
    public $columns;
    public string $route;
    public bool $selectAll = false;
    public array $selectedItems = [];
    public string $model;
    public Collection $data;

    public function mount(string $model, string $route): void
    {
        $this->model = $model;
        $this->route = $route;
        $this->data = $this->model::all();
    }

    public function updatedSelectAll($value)
    {
        if ($value) {
            $this->selectedItems = collect($this->data)->pluck('id')->map(fn ($id) => (string) $id)->toArray();
        } else {
            $this->selectedItems = [];
        }
    }

    public function render()
    {
        return view('livewire.dashboard.list-item', [
            'data' => $this->data,
            'route' => $this->route,
        ]);
    }
}
