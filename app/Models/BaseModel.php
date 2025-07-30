<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class BaseModel extends Model
{
    public function getTableColumns(array $except = ['id', 'created_at', 'updated_at'])
    {
        return collect(Schema::getColumnListing($this->getTable()))
            ->reject(fn ($column) => in_array($column, $except))
            ->values()
            ->toArray();
    }

    public function getTableColumnTypesSimple(array $except = ['id', 'created_at', 'updated_at']): array
    {
        $columns = collect(Schema::getColumnListing($this->getTable()))
            ->reject(fn ($column) => in_array($column, $except));

        $typeMap = [
            'string' => 'text',
            'text' => 'textarea',
            'integer' => 'number',
            'bigint' => 'number',
            'smallint' => 'number',
            'boolean' => 'checkbox',
            'tinyint(1)' => 'checkbox',
            'tinyint' => 'checkbox',
            'date' => 'date',
            'datetime' => 'datetime-local',
            'timestamp' => 'datetime-local',
            'time' => 'time',
            'float' => 'number',
            'double' => 'number',
            'decimal' => 'number',
        ];

        $result = [];

        $database = DB::getDatabaseName();

        foreach ($columns as $column) {
            $type = Schema::getColumnType($this->getTable(), $column);

            // Se for string, mas coluna é tinyint(1), trate como checkbox
            if ($type === 'string') {
                $columnTypeInfo = DB::table('information_schema.columns')
                    ->select('column_type')
                    ->where('table_schema', $database)
                    ->where('table_name', $this->getTable())
                    ->where('column_name', $column)
                    ->value('column_type');

                if (preg_match('/tinyint\(1\)/i', $columnTypeInfo)) {
                    $type = 'boolean';
                }
            }

            $result[$column] = $typeMap[$type] ?? 'text';
        }

        return $result;
    }
}
