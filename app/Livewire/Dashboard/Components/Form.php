<?php

namespace App\Livewire\Dashboard\Components;

use App\Livewire\Forms\Dashboard\GenericForm;
use App\Services\AuxService;
use Exception;
use Livewire\Component;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Str;

class Form extends Component
{
    public string $route;
    public array $columns;
    public string $model;

    protected AuxService $auxService;

    public GenericForm $form;

    public function boot(AuxService $auxService)
    {
        $this->auxService = $auxService;
    }

    public function mount()
    {
        $this->form->setModel($this->model);
    }

    public function updated($property)
    {
        if (Str::startsWith($property, 'form.data.')) {
            $this->validateOnly($property);
        }
    }

    public function save(bool $addOther = false)
    {
        $this->form->validate();

        try {
            $this->auxService->store($this->model, $this->form->data);

            if ($addOther) {
                return $this->dispatch('swal:redirect', [
                    'title' => 'Sucesso',
                    'text' => 'Item criado com sucesso, iremos te redirecionar para criar outro.',
                    'icon' => 'success',
                    'redirectUrl' => $this->route
                ]);
            }
            return redirect()->route(Str::beforeLast($this->route, '.'));
        } catch (AuthorizationException $e) {
            $this->dispatch('swal:message', [
                'icon' => 'error', 'title' => 'Acesso negado!', 'text' => $e->getMessage()
            ]);
        } catch (Exception $e) {
            $this->dispatch('swal:message', [
                'icon' => 'error', 'title' => 'Ocorreu um erro', 'text' => $e->getMessage()
            ]);
        }
    }

    public function render()
    {
        return view('livewire.dashboard.components.form');
    }
}
