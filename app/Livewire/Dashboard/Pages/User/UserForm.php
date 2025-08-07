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

    public ?int $id = null;
    public $profilImage;
    public array $imagesToRemove = [];
    public $image;

    protected AuxService $auxService;

    public UserLivewireForm $form;

    public function boot(AuxService $auxService)
    {
        $this->auxService = $auxService;
    }

    public function mount(?int $id = null)
    {
        $this->id = $id;

        if ($this->id) {
            if ($this->id != auth()->user()->id && !auth()->user()->is_admin) {
                return redirect()->route('dashboard.user.edit', ['id' => auth()->user()->id]);
            }
            $modelInstance = $this->auxService->find(User::class, $this->id);

            $this->form->userId = $this->id;
            $this->form->fill($modelInstance);

            $this->profilImage = $modelInstance->images()->first();
        }
    }

    public function reload()
    {
        if ($this->id) {
            $modelInstance = $this->auxService->find(User::class, $this->id);
            $this->profilImage = $modelInstance->images()->first();
        }
    }

    protected function rules()
    {
        return [
            'image' => 'nullable',
            'image' => [
                'nullable',
                File::image()
                    ->types(['jpeg', 'png', 'jpg', 'gif'])
                    ->max(2048),
            ],
        ];
    }

    protected $messages = [
        'image.max' => 'A imagem não pode ter mais de 2MB.',
        'image.image' => 'O arquivo deve ser uma imagem.',
        'image.mimes' => 'O tipo de imagem não é permitido.',
    ];

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

                if (!empty($this->image) && $this->profilImage) {
                    $this->auxService->removeImage([$this->profilImage->id]);
                }

                $message = 'atualizado';
            } else {
                $savedModel = $this->auxService->store(User::class, $dataToSave);
                $this->id = $savedModel->id;

                $message = 'criado';
            }

            if ($savedModel && !empty($this->image)) {
                $this->auxService->uploadImage(User::class, $savedModel->id, [$this->image]);
                $this->image = null;
                $this->reload();
            }

            DB::commit();

            return $this->dispatch('swal:redirect', [
                'title' => 'Sucesso',
                'text' => "Usuario {$message} com sucesso.",
                'icon' => 'success',
                'redirectUrl' => route('dashboard.user.edit', ['id' => $this->id])
            ]);
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
