<?php

namespace App\Livewire\Dashboard\Components;

use App\Services\AuxService;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Collection;
use Livewire\Attributes\On;
use Livewire\Component;

class ListItem extends Component
{
    public $columns;
    public string $route;
    public bool $selectAll = false;
    public array $selectedItems = [];
    public string $model;
    public Collection $data;

    protected AuxService $auxService;

    public function mount(string $model, string $route, AuxService $auxService): void
    {
        $this->model = $model;
        $this->route = $route;
        $this->data = $this->model::all();
        $this->auxService = $auxService;
    }

    public function boot(AuxService $auxService)
    {
        $this->auxService = $auxService;
    }

    public function reloadData(): void
    {
        $this->data = $this->model::all();
        $this->reset(['selectedItems', 'selectAll']);
    }

    public function updatedSelectAll($value)
    {
        if ($value) {
            $this->selectedItems = collect($this->data)->pluck('id')->map(fn ($id) => (string) $id)->toArray();
        } else {
            $this->selectedItems = [];
        }
    }


    public function confirmDelete(int $id): void
    {
        $this->dispatch('swal:confirm', [
            'title' => 'Tem certeza?',
            'text' => 'Você realmente quer excluir este item? Isso não poderá ser revertido!',
            'icon' => 'warning',
            'onConfirmedEvent' => 'performDelete',
            'onConfirmedParams' => ['id' => $id]
        ]);
    }

    public function confirmDeleteSelected()
    {
        if (empty($this->selectedItems)) {
            $this->dispatch('swal:message', [
                'icon' => 'info', 'title' => 'Atenção!', 'text' => 'Nenhum item selecionado para exclusão.'
            ]);
            return;
        }

        $this->dispatch('swal:confirm', [
            'title' => 'Tem certeza?',
            'text' => 'Você realmente quer excluir os ' . count($this->selectedItems) . ' itens selecionados? Isso não poderá ser revertido!',
            'icon' => 'warning',
            'onConfirmedEvent' => 'performDeleteSelected',
        ]);
    }

    #[On('performDelete')]
    public function performDelete(array $params): void
    {
        $id = $params['id'] ?? null;
        if (!$id) {
            $this->dispatch('swal:message', ['icon' => 'error', 'title' => 'Erro!', 'text' => 'ID do item não fornecido para exclusão.']);
            return;
        }

        try {
            $this->auxService->delete($this->model, $id);

            $this->reloadData();
            $this->dispatch('swal:message', ['icon' => 'success', 'title' => 'Sucesso!', 'text' => 'Item deletado com sucesso.']);
        } catch (AuthorizationException $e) {
            $this->dispatch('swal:message', ['icon' => 'error', 'title' => 'Acesso negado!', 'text' => $e->getMessage()]);
        } catch (Exception $e) {
            $this->dispatch('swal:message', ['icon' => 'error', 'title' => 'Ocorreu um erro', 'text' => $e->getMessage()]);
        }
    }

    #[On('performDeleteSelected')]
    public function performDeleteSelected()
    {

        if (empty($this->selectedItems)) {
            $this->dispatch('swal:message', [
                'icon' => 'error',
                'title' => 'Erro!',
                'text' => 'Nenhum item selecionado para exclusão após confirmação.'
            ]);
            return;
        }

        try {
            $this->auxService->deleteItems($this->model, $this->selectedItems);

            $this->reloadData();
            $this->reset(['selectedItems', 'selectAll']);
            $this->dispatch('swal:message', ['icon' => 'success', 'title' => 'Sucesso!', 'text' => 'Itens deletados com sucesso!']);
        } catch (AuthorizationException $e) {
            $this->dispatch('swal:message', ['icon' => 'error', 'title' => 'Acesso negado!', 'text' => $e->getMessage()]);
        } catch (Exception $e) {
            $this->dispatch('swal:message', ['icon' => 'error', 'title' => 'Ocorreu um erro', 'text' => $e->getMessage()]);
        }
    }

    public function render()
    {
        return view('livewire.dashboard.components.list-item', [
            'data' => $this->data,
            'route' => $this->route,
        ]);
    }
}
