<?php

namespace App\Livewire\Dashboard\Pages\User;

use App\Livewire\Forms\Dashboard\UserForm as UserLivewireForm;
use App\Models\User;
use App\Services\AuxService;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Title;
use Illuminate\Validation\Rules\File;
use Livewire\Component;
use Livewire\WithFileUploads;

class UserForm extends Component
{
    use WithFileUploads;

    public int $id;
    public $profilImage;
    public array $imagesToRemove = [];
    public $images = [];

    protected AuxService $auxService;

    public UserLivewireForm $form;

    public function boot(AuxService $auxService)
    {
        $this->auxService = $auxService;
    }

    public function mount()
    {
        if ($this->id != auth()->user()->id) {
            return redirect()->route('dashboard.user.edit', ['id' => auth()->user()->id]);
        }

        if ($this->id) {
            $modelInstance = $this->auxService->find(User::class, $this->id);

            $this->form->userId = $this->id;
            $this->form->fill($modelInstance);

            $this->profilImage = $modelInstance->images()->first();
        }
    }

    public function reload()
    {
        $modelInstance = $this->auxService->find(User::class, $this->id);

        $this->profilImage = $modelInstance->images()->first();
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

    public function save()
    {
        $this->validate();

        DB::beginTransaction();

        try {
            $savedModel = null;
            $dataToSave = $this->form->all();

            if ($this->id) {
                if (empty($dataToSave['password'])) {
                    unset($dataToSave['password']);
                    unset($dataToSave['password_confirmation']);
                }
                $savedModel = $this->auxService->update(User::class, $this->id, $dataToSave);
            } else {
                $savedModel = $this->auxService->store(User::class, $this->form->all());
            }

            if ($savedModel && !empty($this->images)) {
                $this->auxService->removeImage([$this->profilImage->id]);
                $this->auxService->uploadImage(User::class, $savedModel->id, $this->images);
                $this->images = [];
                $this->reload();
            }

            $message = $this->id ? 'salvo' : 'criado';
            $this->dispatch('swal:message', [
                'title' => 'Sucesso',
                'text' => "Usuario {$message} com sucesso.",
                'icon' => 'success'
            ]);

            DB::commit();
        } catch (AuthorizationException $e) {
            DB::rollBack();
            $this->dispatch('swal:message', [
                'icon' => 'error', 'title' => 'Acesso negado!', 'text' => $e->getMessage()
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            $this->dispatch('swal:message', [
                'icon' => 'error', 'title' => 'Ocorreu um erro', 'text' => $e->getMessage()
            ]);
        }
    }

    #[Title('Usuario - Editar')]
    public function render()
    {
        return view('livewire.dashboard.pages.user.user-form')->layout('livewire.dashboard.layouts.app');
    }
}
