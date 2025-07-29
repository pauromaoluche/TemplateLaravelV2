<?php

namespace App\Services;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Gate;

class AuxService
{
    public function delete(string $modelClass, int $id, ?string $policyAbility = 'manage-users'): bool
    {
        $instance = $modelClass::find($id);

        if (!$instance) {
            return false;
        }

        if ($policyAbility && Gate::denies($policyAbility, $instance)) {
            throw new AuthorizationException('Você não tem permissão para excluir este item.');
        }

        return $instance->delete();
    }

    public function deleteItems(string $modelClass, array $ids, ?string $policyAbility = 'manage-users'): bool
    {
        $instance = $modelClass::whereIn('id', $ids)->get();

        if ($instance->isEmpty()) {
            return false;
        }

        if ($policyAbility && Gate::denies($policyAbility, $instance)) {
            throw new AuthorizationException('Você não tem permissão para excluir este item.');
        }

        return $modelClass::whereIn('id', $ids)->delete();
    }
}
