<?php

namespace App\Services;

use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class AuxService
{

    public function find(string $modelClass, int $id): Model
    {
        $instance = $modelClass::find($id);

        if (!$instance) {
            throw new Exception("Item  não encontrado.");
        }

        return $instance;
    }

    public function store(string $modelClass, $data): Model
    {
        try {
            Gate::authorize('create', $modelClass);
        } catch (AuthorizationException $e) {
            Log::channel('saas')->warning("Permissão negada para criar {$modelClass} pelo usuário " . optional(auth()->user())->id . ": " . $e->getMessage());
            throw new AuthorizationException('Você não tem permissão para criar este item.');
        }

        try {
            $instance = $modelClass::create($data);
            return $instance;
        } catch (QueryException $e) {
            Log::channel('dbErrors')->error("Erro de BD ao criar {$modelClass}: " . $e->getMessage(), ['data' => $data]);
            throw new Exception("Não foi possível criar o item devido a um erro no banco de dados.");
        } catch (Exception $e) {
            Log::error("Erro inesperado ao criar {$modelClass}: " . $e->getMessage());
            throw new Exception("Ocorreu um erro inesperado ao tentar criar o item. Contate o administrador!");
        }
    }

    public function update(string $modelClass, $id, $data): Model
    {
        try {
            $instance = $modelClass::findOrFail($id);

            Gate::authorize('update', $instance);
        } catch (AuthorizationException $e) {
            Log::channel('saas')->warning("Permissão negada para atualizar {$modelClass} (ID: {$id}) pelo usuário " . optional(auth()->user())->id . ": " . $e->getMessage());
            throw new AuthorizationException('Você não tem permissão para atualizar este item.');
        } catch (Exception $e) {
            Log::error("Item a ser atualizado não encontrado: " . $e->getMessage());
            throw new Exception("Item a ser atualizado não encontrado.");
        }

        try {
            $instance->update($data);
            return $instance;
        } catch (QueryException $e) {
            Log::channel('dbErrors')->error("Erro de BD ao atualizar {$modelClass} (ID: {$id}): " . $e->getMessage(), ['data' => $data]);
            throw new Exception("Não foi possível atualizar o item devido a um erro no banco de dados.");
        } catch (Exception $e) {
            Log::error("Erro inesperado ao atualizar {$modelClass} (ID: {$id}): " . $e->getMessage());
            throw new Exception("Ocorreu um erro inesperado ao tentar atualizar o item. Contate o administrador!");
        }
    }

    public function delete(string $modelClass, int $id): bool
    {
        $instance = $modelClass::find($id);

        if (!$instance) {
            throw new Exception("Item a ser excluído não encontrado.");
        }

        try {
            Gate::authorize('delete', $instance);
        } catch (AuthorizationException $e) {
            Log::channel('saas')->warning("Permissão negada para criar {$modelClass} pelo usuário " . optional(auth()->user())->id . ": " . $e->getMessage());
            throw new AuthorizationException('Você não tem permissão para criar este item.');
        }

        try {
            $this->deleteModelImages($instance);

            return $instance->delete();
        } catch (QueryException $e) {
            Log::channel('dbErrors')->error("Erro de BD ao excluir {$modelClass} (ID: {$id}): " . $e->getMessage());
            throw new Exception("Não foi possível excluir o item devido a um erro no banco de dados.");
        } catch (Exception $e) {
            Log::error("Erro inesperado ao excluir {$modelClass} (ID: {$id}): " . $e->getMessage());
            throw new Exception("Ocorreu um erro inesperado ao tentar excluir o item.");
        }
    }

    public function deleteItems(string $modelClass, array $ids): bool
    {
        $instances = $modelClass::whereIn('id', $ids)->get();

        if ($instances->isEmpty()) {
            throw new Exception("Nenhum item válido para excluir foi encontrado.");
        }

        try {
            foreach ($instances as $instance) {
                Gate::authorize('delete', $instance);
            }
        } catch (AuthorizationException $e) {
            Log::channel('saas')->warning("Permissão negada para deletar {$modelClass}, (IDs: " . implode(',', $ids) . ") pelo usuário " . optional(auth()->user())->id . ": " . $e->getMessage());
            throw new AuthorizationException('Você não tem permissão para deletar estes itens.');
        }

        try {
            $allDeleted = true;
            foreach ($instances as $instance) {
                $this->deleteModelImages($instance);

                if (!$instance->delete()) {
                    $allDeleted = false;
                }
            }
            return $allDeleted;
        } catch (QueryException $e) {
            Log::channel('dbErrors')->error("Erro de BD ao excluir múltiplos {$modelClass} (IDs: " . implode(',', $ids) . "): " . $e->getMessage());
            throw new Exception("Não foi possível excluir os itens devido a um erro no banco de dados.");
        } catch (Exception $e) {
            Log::error("Erro inesperado ao excluir múltiplos {$modelClass} (IDs: " . implode(',', $ids) . "): " . $e->getMessage());
            throw new Exception("Ocorreu um erro inesperado ao tentar excluir os itens.");
        }
    }

    private function deleteModelImages(Model $instance): void
    {
        if (method_exists($instance, 'images')) {
            try {
                $instance->load('images');

                // Itera sobre todas as imagens associadas
                foreach ($instance->images as $image) {
                    if (Storage::disk('public')->exists($image->path)) {
                        Storage::disk('public')->delete($image->path);
                    }

                    $image->delete();
                }
            } catch (Exception $e) {
                Log::error("Erro ao excluir imagens para o modelo " . get_class($instance) . " com ID " . $instance->id . ": " . $e->getMessage());

                throw new Exception("Não foi possível excluir as imagens do item devido a um erro: " . $e->getMessage());
            }
        }
    }
}
