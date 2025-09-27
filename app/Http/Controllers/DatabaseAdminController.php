<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Throwable;

class DatabaseAdminController extends Controller
{
    protected array $hiddenTables = [
        'migrations',
        'cache',
        'cache_locks',
        'jobs',
        'job_batches',
        'failed_jobs',
        'password_reset_tokens',
        'sessions',
        'sqlite_sequence',
        'personal_access_tokens',
        'telescope_entries',
        'telescope_entries_tags',
        'telescope_monitoring',
        'database_audit_logs',
    ];

    protected array $systemSchemas = [
        'information_schema',
        'mysql',
        'performance_schema',
        'sys',
    ];

    protected array $protectedTables = [
        'users',
        'shops',
        'daily_collections',
        'database_audit_logs',
    ];

    public function index(Request $request)
    {
        $tables = $this->manageableTables();
        $requestedTable = $request->query('table');

        // Default screen: table catalog + basic metadata.
        if (! $requestedTable) {
            return view('admin.database-overview', [
                'tables' => $tables,
                'tableInfo' => $this->tableInfo($tables),
            ]);
        }

        if (empty($tables)) {
            return view('admin.database-overview', [
                'tables' => [],
                'tableInfo' => [],
            ]);
        }

        $currentTable = $requestedTable;
        abort_unless(in_array($currentTable, $tables, true), 404);

        try {
            $columns = Schema::getColumnListing($currentTable);
            $primaryKey = in_array('id', $columns, true) ? 'id' : ($columns[0] ?? 'id');
            $editId = $request->query('edit');

            $query = DB::table($currentTable)->orderBy($primaryKey, 'desc');
            $rows = $query->paginate(12)->appends($request->query());
            $editRow = null;

            if ($editId !== null) {
                $editRow = DB::table($currentTable)->where($primaryKey, $editId)->first();
            }
        } catch (Throwable $e) {
            $fallbackTable = $tables[0] ?? null;
            if ($fallbackTable === null || $fallbackTable === $currentTable) {
                throw $e;
            }

            return redirect()
                ->route('admin.database.index', ['table' => $fallbackTable])
                ->with('error', 'Selected table is not available in this database.');
        }

        return view('admin.database-admin', [
            'tables' => $tables,
            'currentTable' => $currentTable,
            'columns' => $columns,
            'rows' => $rows,
            'editRow' => $editRow,
            'primaryKey' => $primaryKey,
            'userOptions' => Schema::hasTable('users')
                ? DB::table('users')->select('id', 'name')->orderBy('name')->get()
                : collect(),
            'shopOptions' => Schema::hasTable('shops')
                ? DB::table('shops')->select('id', 'shop_number', 'name')->orderBy('shop_number')->get()
                : collect(),
            'tableTemplates' => $this->columnTemplates(),
        ]);
    }

    public function createTableView()
    {
        $tables = $this->manageableTables();

        return view('admin.database-create-table', [
            'tables' => $tables,
            'tableTemplates' => $this->columnTemplates(),
        ]);
    }

    public function createTable(Request $request)
    {
        $this->assertSchemaOperationsEnabled();

        $validated = $request->validate([
            'table_name' => ['required', 'regex:/^[a-z][a-z0-9_]*$/'],
            'columns' => ['required', 'array'],
            'columns.*.name' => ['nullable', 'regex:/^[a-z][a-z0-9_]*$/'],
            'columns.*.type' => ['nullable', Rule::in(array_keys($this->columnTemplates()))],
            'columns.*.nullable' => ['nullable'],
            'columns.*.unique' => ['nullable'],
            'columns.*.index' => ['nullable'],
            'columns.*.default' => ['nullable', 'string', 'max:255'],
            'columns.*.precision' => ['nullable', 'integer', 'min:1', 'max:65'],
            'columns.*.scale' => ['nullable', 'integer', 'min:0', 'max:30'],
            'columns.*.is_foreign' => ['nullable'],
            'columns.*.foreign_table' => ['nullable', 'regex:/^[a-z][a-z0-9_]*$/'],
            'columns.*.foreign_column' => ['nullable', 'regex:/^[a-z][a-z0-9_]*$/'],
            'columns.*.on_delete' => ['nullable', Rule::in(['cascade', 'restrict', 'set null', 'no action'])],
        ]);

        $tableName = $validated['table_name'];
        abort_if(Schema::hasTable($tableName), 422, 'Table already exists.');

        $rawColumns = array_values(array_filter($validated['columns'], function ($column) {
            return !empty($column['name']);
        }));
        abort_if(empty($rawColumns), 422, 'At least one column is required.');

        foreach ($rawColumns as $column) {
            abort_if(empty($column['type']), 422, 'Each column must have a valid type.');
        }

        $columnNames = array_map(fn ($c) => $c['name'], $rawColumns);
        abort_if(count($columnNames) !== count(array_unique($columnNames)), 422, 'Column names must be unique.');

        Schema::create($tableName, function (Blueprint $table) use ($rawColumns) {
            $table->id();

            foreach ($rawColumns as $column) {
                $definition = $this->addColumnByType($table, $column);

                if (($column['nullable'] ?? null) !== null) {
                    $definition->nullable();
                }

                if (($column['default'] ?? '') !== '') {
                    $definition->default($column['default']);
                }

                if (($column['unique'] ?? null) !== null) {
                    $table->unique($column['name']);
                } elseif (($column['index'] ?? null) !== null) {
                    $table->index($column['name']);
                }
            }

            $table->timestamps();
        });

        // Add foreign keys in a separate step after table creation.
        foreach ($rawColumns as $column) {
            if (($column['is_foreign'] ?? null) === null) {
                continue;
            }

            if (empty($column['foreign_table']) || empty($column['foreign_column'])) {
                continue;
            }

            if (! Schema::hasTable($column['foreign_table'])) {
                continue;
            }

            Schema::table($tableName, function (Blueprint $table) use ($column) {
                $foreign = $table->foreign($column['name'])
                    ->references($column['foreign_column'])
                    ->on($column['foreign_table']);

                $onDelete = $column['on_delete'] ?? 'cascade';
                if ($onDelete === 'set null') {
                    $foreign->nullOnDelete();
                } elseif ($onDelete === 'restrict') {
                    $foreign->restrictOnDelete();
                } elseif ($onDelete === 'no action') {
                    $foreign->noActionOnDelete();
                } else {
                    $foreign->cascadeOnDelete();
                }
            });
        }

        $this->logAudit(
            action: 'create_table',
            targetType: 'table',
            targetName: $tableName,
            rowIdentifier: null,
            beforeData: null,
            afterData: ['columns' => $rawColumns]
        );

        return redirect()
            ->route('admin.database.index', ['table' => $tableName])
            ->with('success', 'Table '.$tableName.' created successfully.');
    }

    public function dropTable(string $table)
    {
        $this->assertSchemaOperationsEnabled();

        abort_unless(in_array($table, $this->manageableTables(), true), 404);
        abort_if(in_array($table, $this->protectedTables, true), 422, 'Protected app tables cannot be dropped.');

        $columns = Schema::getColumnListing($table);
        $rowCount = DB::table($table)->count();

        Schema::dropIfExists($table);

        $this->logAudit(
            action: 'drop_table',
            targetType: 'table',
            targetName: $table,
            rowIdentifier: null,
            beforeData: ['columns' => $columns, 'rows_count' => $rowCount],
            afterData: null
        );

        return redirect()
            ->route('admin.database.index')
            ->with('success', 'Table '.$table.' dropped successfully.');
    }

    public function store(Request $request, string $table)
    {
        abort_unless(in_array($table, $this->manageableTables(), true), 404);

        $columns = Schema::getColumnListing($table);
        $rules = $this->rulesFor($table);
        $validated = empty($rules)
            ? $request->only($this->formColumns($columns))
            : $request->validate($rules);
        $data = $this->buildPayload($table, $columns, $validated, true);

        $primaryKey = in_array('id', $columns, true) ? 'id' : ($columns[0] ?? 'id');
        $rowIdentifier = null;

        if ($primaryKey === 'id') {
            $insertedId = DB::table($table)->insertGetId($data);
            $rowIdentifier = (string) $insertedId;
            $afterRow = DB::table($table)->where($primaryKey, $insertedId)->first();
        } else {
            DB::table($table)->insert($data);
            $afterRow = $data;
        }

        $this->logAudit(
            action: 'insert_row',
            targetType: 'table',
            targetName: $table,
            rowIdentifier: $rowIdentifier,
            beforeData: null,
            afterData: (array) $afterRow
        );

        return redirect()
            ->route('admin.database.index', ['table' => $table])
            ->with('success', ucfirst($table).' row created successfully.');
    }

    public function update(Request $request, string $table, string $id)
    {
        abort_unless(in_array($table, $this->manageableTables(), true), 404);

        $columns = Schema::getColumnListing($table);
        $primaryKey = in_array('id', $columns, true) ? 'id' : ($columns[0] ?? 'id');
        $beforeRow = DB::table($table)->where($primaryKey, $id)->first();
        abort_unless($beforeRow !== null, 404);

        $rules = $this->rulesFor($table, (int) $id);
        $validated = empty($rules)
            ? $request->only($this->formColumns($columns))
            : $request->validate($rules);
        $data = $this->buildPayload($table, $columns, $validated, false);

        if (empty($data)) {
            return redirect()
                ->route('admin.database.index', ['table' => $table])
                ->with('success', ucfirst($table).' row updated successfully.');
        }

        DB::table($table)->where($primaryKey, $id)->update($data);
        $afterRow = DB::table($table)->where($primaryKey, $id)->first();

        $this->logAudit(
            action: 'update_row',
            targetType: 'table',
            targetName: $table,
            rowIdentifier: (string) $id,
            beforeData: (array) $beforeRow,
            afterData: (array) $afterRow
        );

        return redirect()
            ->route('admin.database.index', ['table' => $table])
            ->with('success', ucfirst($table).' row updated successfully.');
    }

    public function destroy(string $table, string $id)
    {
        abort_unless(in_array($table, $this->manageableTables(), true), 404);

        $columns = Schema::getColumnListing($table);
        $primaryKey = in_array('id', $columns, true) ? 'id' : ($columns[0] ?? 'id');
        $beforeRow = DB::table($table)->where($primaryKey, $id)->first();
        abort_unless($beforeRow !== null, 404);

        if ($table === 'users' && (int) $id === (int) Auth::id()) {
            return redirect()
                ->route('admin.database.index', ['table' => $table])
                ->with('error', 'You cannot delete your own account.');
        }

        DB::table($table)->where($primaryKey, $id)->delete();

        $this->logAudit(
            action: 'delete_row',
            targetType: 'table',
            targetName: $table,
            rowIdentifier: (string) $id,
            beforeData: (array) $beforeRow,
            afterData: null
        );

        return redirect()
            ->route('admin.database.index', ['table' => $table])
            ->with('success', ucfirst($table).' row deleted successfully.');
    }

    protected function manageableTables(): array
    {
        $tables = Schema::getTableListing();

        $filtered = array_values(array_filter($tables, function ($rawTable) {
            if ($this->isSystemSchemaTable($rawTable)) {
                return false;
            }

            $table = Str::contains($rawTable, '.') ? Str::afterLast($rawTable, '.') : $rawTable;

            return ! in_array($table, $this->hiddenTables, true)
                && ! str_starts_with($table, 'sqlite_')
                && ! str_starts_with($table, 'pma__')
                && Schema::hasTable($table);
        }));

        $normalized = array_map(function ($table) {
            return Str::contains($table, '.') ? Str::afterLast($table, '.') : $table;
        }, $filtered);

        return array_values(array_unique($normalized));
    }

    protected function isSystemSchemaTable(string $rawTable): bool
    {
        if (! Str::contains($rawTable, '.')) {
            return false;
        }

        $schema = Str::before($rawTable, '.');

        return in_array($schema, $this->systemSchemas, true);
    }

    protected function formColumns(array $columns): array
    {
        return array_values(array_filter($columns, function ($column) {
            return ! in_array($column, ['id', 'created_at', 'updated_at'], true);
        }));
    }

    protected function rulesFor(string $table, ?int $id = null): array
    {
        if ($table === 'users') {
            $rules = [
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($id)],
                'user_type' => ['required', Rule::in(['admin', 'user', 'dbadmin'])],
            ];

            $rules['password'] = $id
                ? ['nullable', 'string', 'min:8']
                : ['required', 'string', 'min:8'];

            return $rules;
        }

        if ($table === 'shops') {
            return [
                'user_id' => ['required', 'exists:users,id'],
                'shop_number' => ['required', 'string', 'max:100', Rule::unique('shops', 'shop_number')->ignore($id)],
                'name' => ['required', 'string', 'max:255'],
                'address' => ['nullable', 'string', 'max:255'],
            ];
        }

        if ($table === 'daily_collections') {
            return [
                'user_id' => ['required', 'exists:users,id'],
                'shop_id' => ['required', 'exists:shops,id'],
                'date' => ['required', 'date'],
                'till_time' => ['required'],
                'online_collection' => ['required', 'numeric'],
                'offline_collection' => ['required', 'numeric'],
            ];
        }

        // Generic fallback for other tables.
        return [];
    }

    protected function buildPayload(string $table, array $columns, array $validated, bool $isCreate): array
    {
        $fillableColumns = $this->formColumns($columns);

        $data = [];

        foreach ($fillableColumns as $column) {
            if (! array_key_exists($column, $validated)) {
                continue;
            }

            $value = $validated[$column];
            $data[$column] = $value === '' ? null : $value;
        }

        if ($table === 'users') {
            if (array_key_exists('password', $data) && $data['password']) {
                $data['password'] = Hash::make($data['password']);
            } else {
                unset($data['password']);
            }
        }

        if ($table === 'daily_collections') {
            $online = (float) ($validated['online_collection'] ?? 0);
            $offline = (float) ($validated['offline_collection'] ?? 0);
            $data['total_collection'] = $online + $offline;
        }

        // Keep created_at/updated_at database-managed when possible.
        if (! $isCreate) {
            unset($data['id']);
        }

        return $data;
    }

    protected function columnTemplates(): array
    {
        return [
            'string' => 'string',
            'text' => 'text',
            'integer' => 'integer',
            'bigInteger' => 'bigInteger',
            'unsignedBigInteger' => 'unsignedBigInteger',
            'decimal' => 'decimal',
            'boolean' => 'boolean',
            'date' => 'date',
            'dateTime' => 'dateTime',
            'time' => 'time',
        ];
    }

    protected function addColumnByType(Blueprint $table, array $column)
    {
        $name = $column['name'];
        $type = $column['type'];

        return match ($type) {
            'text' => $table->text($name),
            'integer' => $table->integer($name),
            'bigInteger' => $table->bigInteger($name),
            'unsignedBigInteger' => $table->unsignedBigInteger($name),
            'decimal' => $table->decimal(
                $name,
                (int) ($column['precision'] ?? 10),
                (int) ($column['scale'] ?? 2)
            ),
            'boolean' => $table->boolean($name),
            'date' => $table->date($name),
            'dateTime' => $table->dateTime($name),
            'time' => $table->time($name),
            default => $table->string($name),
        };
    }

    protected function schemaOperationsEnabled(): bool
    {
        if (app()->environment('production')) {
            return (bool) env('DB_ADMIN_ALLOW_SCHEMA_OPERATIONS', false);
        }

        return (bool) env('DB_ADMIN_ALLOW_SCHEMA_OPERATIONS', true);
    }

    protected function assertSchemaOperationsEnabled(): void
    {
        abort_unless(
            $this->schemaOperationsEnabled(),
            403,
            'Schema operations are disabled. Set DB_ADMIN_ALLOW_SCHEMA_OPERATIONS=true to enable.'
        );
    }

    protected function logAudit(
        string $action,
        ?string $targetType,
        ?string $targetName,
        ?string $rowIdentifier,
        ?array $beforeData,
        ?array $afterData
    ): void {
        try {
            DB::table('database_audit_logs')->insert([
                'user_id' => Auth::id(),
                'user_email' => Auth::user()?->email,
                'action' => $action,
                'target_type' => $targetType,
                'target_name' => $targetName,
                'row_identifier' => $rowIdentifier,
                'before_data' => $beforeData ? json_encode($beforeData) : null,
                'after_data' => $afterData ? json_encode($afterData) : null,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } catch (Throwable $e) {
            // Logging must not block user operations.
        }
    }

    protected function tableInfo(array $tables): array
    {
        $info = [];

        foreach ($tables as $table) {
            try {
                $columns = Schema::getColumnListing($table);
                $count = DB::table($table)->count();
                $updatedAt = in_array('updated_at', $columns, true)
                    ? DB::table($table)->max('updated_at')
                    : null;

                $info[$table] = [
                    'columns_count' => count($columns),
                    'rows_count' => $count,
                    'has_timestamps' => in_array('created_at', $columns, true) && in_array('updated_at', $columns, true),
                    'last_updated' => $updatedAt,
                ];
            } catch (Throwable $e) {
                $info[$table] = [
                    'columns_count' => 0,
                    'rows_count' => 0,
                    'has_timestamps' => false,
                    'last_updated' => null,
                ];
            }
        }

        return $info;
    }
}
