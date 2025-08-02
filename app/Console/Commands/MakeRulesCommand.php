<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class MakeRulesCommand extends Command
{

    protected $signature = 'make:rules {name}';
    protected $description = 'Gera uma classe de validação baseada na estrutura do banco de dados';

    public function handle()
    {
        $name = Str::studly($this->argument('name'));
        $className = "{$name}Rules";
        $path = app_path("Rules/{$className}.php");
        $table = Str::snake(Str::plural($name)); // ex: Produto -> produtos

        if (!Schema::hasTable($table)) {
            $this->error("Tabela '{$table}' não encontrada.");
            return Command::FAILURE;
        }

        if (File::exists($path)) {
            $this->error("A classe {$className} já existe.");
            return Command::FAILURE;
        }

        $stubPath = base_path('stubs/rules.stub');
        if (!File::exists($stubPath)) {
            $this->error("Stub não encontrado: {$stubPath}");
            return Command::FAILURE;
        }

        $rulesArray = $this->getRulesFromDatabase($table);
        $stub = File::get($stubPath);
        $content = str_replace(
            ['{{ class }}', '{{ rules }}'],
            [$name, $rulesArray],
            $stub
        );

        File::ensureDirectoryExists(app_path('Rules'));
        File::put($path, $content);

        $this->info("Classe {$className} criada com sucesso em: {$path}");
        return Command::SUCCESS;
    }

    protected function getRulesFromDatabase(string $table): string
    {
        $columns = Schema::getColumnListing($table);
        $rules = [];

        foreach ($columns as $column) {
            if (in_array($column, ['id', 'created_at', 'updated_at', 'deleted_at'])) {
                continue;
            }

            // Obtém o tipo de dado do Laravel (que já usa o DBAL internamente, mas não diretamente o getDoctrineColumn)
            // No entanto, para tipos mais genéricos, isso funciona.
            $type = DB::getSchemaBuilder()->getColumnType($table, $column);

            // Tentar obter a informação de nulidade diretamente do INFORMATION_SCHEMA (MySQL-specific)
            $columnInfo = DB::table('INFORMATION_SCHEMA.COLUMNS')
                ->select('IS_NULLABLE')
                ->where('TABLE_SCHEMA', DB::getDatabaseName()) // Nome do banco de dados atual
                ->where('TABLE_NAME', $table)
                ->where('COLUMN_NAME', $column)
                ->first();

            $isNullable = ($columnInfo && $columnInfo->IS_NULLABLE === 'YES');


            $ruleParts = [];

            // Required ou Nullable
            $ruleParts[] = $isNullable ? 'nullable' : 'required';

            // Tipo de validação
            switch ($type) {
                case 'integer':
                case 'bigint':
                case 'smallint':
                    $ruleParts[] = 'integer';
                    break;
                case 'boolean':
                case 'tinyint':
                    $ruleParts[] = 'boolean';
                    break;
                case 'float':
                case 'double':
                case 'decimal':
                    $ruleParts[] = 'numeric';
                    break;
                case 'date':
                case 'datetime':
                case 'timestamp':
                    $ruleParts[] = 'date';
                    break;
                case 'string':
                case 'text':
                default:
                    $ruleParts[] = 'string';
                    break;
            }

            $isUnique = DB::table('INFORMATION_SCHEMA.STATISTICS')
                ->where('TABLE_SCHEMA', DB::getDatabaseName())
                ->where('TABLE_NAME', $table)
                ->where('COLUMN_NAME', $column)
                ->where('NON_UNIQUE', 0)
                ->exists();

            if ($isUnique) {
                $ruleParts[] = "Rule::unique('{$table}', '{$column}')->ignore(\$id)";
                $rules[] = "'$column' => [" . implode(', ', array_map(function ($rule) {
                    return Str::startsWith($rule, 'Rule::') ? $rule : "'$rule'";
                }, $ruleParts)) . "],";
            } else {
                $rules[] = "'$column' => '" . implode('|', $ruleParts) . "',";
            };
        }

        return implode("\n            ", $rules);
    }
}
