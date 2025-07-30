<?php

namespace App\Livewire\Forms\Dashboard;

use Livewire\Form;
use Illuminate\Support\Str;

class GenericForm extends Form
{
    public string $modelClass;
    public array $dynamicRules = [];
    public array $data = [];

    public function setModel(string $modelClass): void
    {
        $this->modelClass = $modelClass;

        $rulesClass = str_replace('App\\Models\\', 'App\\Rules\\', $modelClass) . 'Rules';

        if (class_exists($rulesClass)) {
            // Carrega as regras da sua classe de regras estática
            $this->dynamicRules = (new $rulesClass)->rules();

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
            $cleanKey = Str::after($key, 'data.'); // Remove o prefixo 'data.'
            if (!isset($this->data[$cleanKey])) {
                $this->data[$cleanKey] = ''; // Inicializa com vazio, ajuste se precisar de outros padrões
            }
        }
    }

    // SOBRESCRITA DO MÉTODO validationAttributes()
    public function validationAttributes(): array
    {
        $attributes = [];

        // Itera sobre as regras dinâmicas para obter os nomes "limpos" dos campos
        foreach ($this->dynamicRules as $keyWithPrefix => $rule) {
            $cleanKey = Str::after($keyWithPrefix, 'data.'); // Remove o prefixo 'data.'

            // Agora, tentamos obter a tradução do arquivo validation.php
            // Laravel buscará 'attributes.cleanKey' (ex: 'attributes.title')
            $translatedAttribute = trans('validation.attributes.' . $cleanKey);

            // Se a tradução existir e não for igual ao 'attributes.cleanKey' (ou seja, foi traduzida)
            if ($translatedAttribute !== 'validation.attributes.' . $cleanKey) {
                $attributes[$keyWithPrefix] = $translatedAttribute;
            } else {
                // Se não houver tradução no validation.php, ou se o método attributes()
                // na sua classe de regras tivesse sido implementado e retornado algo,
                // você poderia ter uma lógica de fallback aqui.
                // Por enquanto, vamos deixar o Laravel usar o nome padrão se não traduzido.
                // OU, você pode gerar um nome amigável padrão:
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
