<?php

namespace App\Livewire\Dashboard\Components;

use App\Livewire\Forms\Dashboard\GenericForm;
use App\Services\AuxService;
use Exception;
use Livewire\Component;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Str;
use Livewire\WithFileUploads;
use Illuminate\Validation\Rules\File;

class Form extends Component
{
    use WithFileUploads;

    public string $route;
    public array $columns;
    public string $model;
    public $images = [];
    public array $existingImages = [];
    public string $activeTab = 'home';
    public ?int $id = null;
    public $data;

    protected AuxService $auxService;

    public GenericForm $form;

    public function boot(AuxService $auxService)
    {
        $this->auxService = $auxService;
    }

    public function mount(?int $id = null)
    {
        $this->id = $id;

        if ($this->id) {
            $this->form->setModel($this->model, $this->id);
            $modelInstance = $this->auxService->find($this->model, $this->id);
            $this->existingImages = $modelInstance->images()->get()->toArray();
            $this->form->setData($modelInstance->toArray());
        }
    }

    public function hydrate()
    {
        $this->form->setModel($this->model, $this->id);
    }

    protected function rules()
    {
        return [
            'images' => 'nullable|array',
            'images.*' => [
                File::image()
                    ->types(['jpeg', 'png', 'jpg', 'gif'])
                    ->max(2048),
            ],
        ];
    }
    protected $messages = [
        'images.*.max' => 'Cada imagem não pode ter mais de 2MB.',
        'images.*.image' => 'O arquivo deve ser uma imagem.',
        'images.*.mimes' => 'O tipo de imagem não é permitido.',
    ];

    public function updated($property)
    {
        if (Str::startsWith($property, 'form.data.')) {
            $this->validateOnly($property);
        }
    }

    public function updatedImages()
    {
        $this->validate([
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);
    }

    public function removeTemporaryImage(int $index): void
    {
        if (isset($this->images[$index])) {
            unset($this->images[$index]);
            $this->images = array_values($this->images);
            $this->validate();
        }
    }

    public function save(bool $addOther = false)
    {
        $this->validate();

        try {
            $savedModel = null;

            if ($this->id) {
                $savedModel = $this->auxService->update($this->model, $this->id, $this->form->data);
            } else {
                $savedModel = $this->auxService->store($this->model, $this->form->data);
            }

            if ($savedModel && !empty($this->images)) {
                foreach ($this->images as $imageFile) {
                    $path = $imageFile->store(Str::beforeLast($this->route, '.'), 'public');

                    $savedModel->images()->create([
                        'path' => $path,
                    ]);
                }
                $this->images = [];
            }

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
