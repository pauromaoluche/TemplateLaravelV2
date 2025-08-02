<?php

namespace App\Livewire\Forms\Dashboard;

use Livewire\Form;
use Illuminate\Support\Str;

class GenericForm extends Form
{
    public string $modelClass;
    protected  array $dynamicRules = [];
    public array $data = [];

    public function setModel(string $modelClass, ?int $id = null): void
    {
        $this->modelClass = $modelClass;

        $rulesClass = str_replace('App\\Models\\', 'App\\Rules\\', $modelClass) . 'Rules';

        if (class_exists($rulesClass)) {
            $this->dynamicRules = $rulesClass::rules($id);

            // Adiciona o prefixo "data." às chaves das regras, como antes.
            // Isso é necessário porque o wire:model está em form.data.{{$key}}
            $prefixedRules = [];
            foreach ($this->dynamicRules as $key => $rule) {
                $prefixedRules["data.{$key}"] = $rule;
            }
            $this->dynamicRules = $prefixedRules;

        } else {
            $this->dynamicRules = [];
            \Log::warning("Classe de regras não encontrada para o modelo: {$modelClass} ({$rulesClass})");
        }

        // Isso é crucial para inicializar 'data' e evitar erros de chave indefinida
        foreach ($this->dynamicRules as $key => $rule) {
            $cleanKey = Str::after($key, 'data.');
            if (!isset($this->data[$cleanKey])) {
                $this->data[$cleanKey] = '';
            }
        }
    }

    public function setData(array $data): void
    {
        $this->data = $data;
    }

    public function validationAttributes(): array
    {
        $attributes = [];

        // Itera sobre as regras dinâmicas para obter os nomes "limpos" dos campos
        foreach ($this->dynamicRules as $keyWithPrefix => $rule) {
            $cleanKey = Str::after($keyWithPrefix, 'data.'); // Remove o prefixo 'data.'

            $translatedAttribute = trans('validation.attributes.' . $cleanKey);

            if ($translatedAttribute !== 'validation.attributes.' . $cleanKey) {
                $attributes[$keyWithPrefix] = $translatedAttribute;
            } else {
                $attributes[$keyWithPrefix] = ucfirst(str_replace('_', ' ', $cleanKey));
            }
        }

        return $attributes;
    }

    public function rules(): array
    {
        return $this->dynamicRules;
    }
}
