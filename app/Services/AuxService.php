<?php

namespace App\Services;

use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

class AuxService
{

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
            Log::channel('db_errors')->error("Erro de BD ao criar {$modelClass}: " . $e->getMessage(), ['data' => $data]);
            throw new Exception("Não foi possível criar o item devido a um erro no banco de dados.");
        } catch (Exception $e) {
            Log::error("Erro inesperado ao criar {$modelClass}: " . $e->getMessage());
            throw new Exception("Ocorreu um erro inesperado ao tentar criar o item. Contate o administrador!");
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
            return $instance->delete();
        } catch (QueryException $e) {
            Log::channel('db_errors')->error("Erro de BD ao excluir {$modelClass} (ID: {$id}): " . $e->getMessage());
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
            return $modelClass::whereIn('id', $ids)->delete();
        } catch (QueryException $e) {
            Log::channel('db_errors')->error("Erro de BD ao excluir múltiplos {$modelClass} (IDs: " . implode(',', $ids) . "): " . $e->getMessage());
            throw new Exception("Não foi possível excluir os itens devido a um erro no banco de dados.");
        } catch (Exception $e) {
            Log::error("Erro inesperado ao excluir múltiplos {$modelClass} (IDs: " . implode(',', $ids) . "): " . $e->getMessage());
            throw new Exception("Ocorreu um erro inesperado ao tentar excluir os itens.");
        }
    }
}
