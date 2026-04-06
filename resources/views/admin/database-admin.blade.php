@extends('admin.maindesign')

@section('content')
    @php
        $excludedColumns = ['id', 'created_at', 'updated_at'];
        $formColumns = array_values(array_filter($columns, fn ($col) => !in_array($col, $excludedColumns, true)));
    @endphp

    <style>
        .db-admin-wrap {
            padding: 18px;
        }

        .db-card {
            border: 1px solid #e7edf5;
            border-radius: 16px;
            background: #fff;
            box-shadow: 0 12px 30px rgba(16, 24, 40, 0.08);
            margin-bottom: 16px;
        }

        .db-card-head {
            padding: 16px 18px;
            border-bottom: 1px solid #edf2f8;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            flex-wrap: wrap;
        }

        .db-title {
            font-size: 22px;
            font-weight: 700;
            margin: 0;
            color: #0f1f38;
        }

        .db-sub {
            margin: 4px 0 0;
            color: #5f6e86;
            font-size: 13px;
        }

        .db-card-body {
            padding: 16px 18px;
        }

        .db-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }

        .db-form-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 12px;
        }

        .db-label {
            display: block;
            font-size: 12px;
            font-weight: 700;
            color: #30445f;
            margin-bottom: 6px;
        }

        .db-input,
        .db-select {
            width: 100%;
            border: 1px solid #d6e0ec;
            border-radius: 10px;
            padding: 9px 10px;
            font-size: 13px;
            color: #12233f;
            background: #fff;
        }

        .db-actions {
            margin-top: 12px;
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .db-btn {
            border: 0;
            border-radius: 10px;
            padding: 9px 12px;
            font-size: 12px;
            font-weight: 700;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .db-btn-primary { background: #1d4ed8; color: #fff; }
        .db-btn-warning { background: #b45309; color: #fff; }
        .db-btn-danger { background: #be123c; color: #fff; }
        .db-btn-muted { background: #e2e8f0; color: #0f172a; }
        .db-btn-dark { background: #0f172a; color: #fff; }

        .db-table-wrap {
            overflow: auto;
            border: 1px solid #e5ebf3;
            border-radius: 12px;
        }

        .db-table {
            width: 100%;
            border-collapse: collapse;
            min-width: 780px;
        }

        .db-table th,
        .db-table td {
            border-bottom: 1px solid #ecf1f7;
            padding: 10px 12px;
            text-align: left;
            font-size: 12px;
            vertical-align: top;
            color: #1f3048;
        }

        .db-table th {
            background: #f8fbff;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #5d6e88;
        }

        .db-kv {
            max-width: 220px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .db-tabs {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .db-tab {
            border: 1px solid #d6e0ec;
            background: #f8fbff;
            color: #1a2b45;
            border-radius: 999px;
            padding: 8px 12px;
            text-decoration: none;
            font-size: 12px;
            font-weight: 700;
        }

        .db-tab-active {
            background: #1d4ed8;
            color: #fff;
            border-color: #1d4ed8;
        }

        .db-alert {
            border-radius: 10px;
            padding: 10px 12px;
            margin-bottom: 12px;
            font-size: 13px;
        }

        .db-alert-success { background: #e7f8ef; color: #166534; }
        .db-alert-error { background: #fde7ee; color: #9f1239; }

        .db-pagination {
            margin-top: 12px;
            display: flex;
            justify-content: space-between;
            gap: 10px;
            flex-wrap: wrap;
            font-size: 12px;
            color: #50627c;
        }

        .db-toolbar {
            display: flex;
            gap: 10px;
            align-items: end;
            flex-wrap: wrap;
        }

        .db-toolbar form {
            display: flex;
            gap: 8px;
            align-items: end;
            flex-wrap: wrap;
        }

        .db-create-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 14px;
        }

        .db-column-box {
            border: 1px solid #e7edf5;
            border-radius: 12px;
            padding: 12px;
            background: #fbfdff;
        }

        @media (max-width: 1100px) {
            .db-grid { grid-template-columns: 1fr; }
            .db-create-grid { grid-template-columns: 1fr; }
        }

        @media (max-width: 800px) {
            .db-form-grid { grid-template-columns: 1fr; }
        }
    </style>

    <section class="db-admin-wrap">
        <div class="db-card">
            <div class="db-card-head">
                <div>
                    <h2 class="db-title">Database Admin</h2>
                    <p class="db-sub">Create, update, and delete records directly from the admin frontend.</p>
                </div>
                <div class="db-toolbar">
                    <form method="GET" action="{{ route('admin.database.index') }}">
                        <div>
                            <label class="db-label">Select table</label>
                            <select class="db-select" name="table">
                                @foreach ($tables as $table)
                                    <option value="{{ $table }}" {{ $currentTable === $table ? 'selected' : '' }}>
                                        {{ $table }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="db-btn db-btn-primary">Open table</button>
                    </form>
                    <a href="{{ route('admin.database.create-table.view') }}" class="db-btn db-btn-dark">Add New Table</a>
                    @if ($currentTable)
                        <form method="POST" action="{{ route('admin.database.drop-table', ['table' => $currentTable]) }}" onsubmit="return confirm('Drop table {{ $currentTable }}? This is destructive.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="db-btn db-btn-danger">Drop current table</button>
                        </form>
                    @endif
                </div>
            </div>
            <div class="db-card-body">
                @if (session('success'))
                    <div class="db-alert db-alert-success">{{ session('success') }}</div>
                @endif

                @if (session('error'))
                    <div class="db-alert db-alert-error">{{ session('error') }}</div>
                @endif

                @if ($errors->any())
                    <div class="db-alert db-alert-error">
                        {{ $errors->first() }}
                    </div>
                @endif

                @if (!$currentTable)
                    <p>No manageable tables found.</p>
                @else
                    <div class="db-grid">
                        <div class="db-card" style="margin: 0;">
                            <div class="db-card-head">
                                <strong>Create {{ $currentTable }} row</strong>
                            </div>
                            <div class="db-card-body">
                                <form method="POST" action="{{ route('admin.database.store', ['table' => $currentTable]) }}">
                                    @csrf
                                    <div class="db-form-grid">
                                        @foreach ($formColumns as $column)
                                            <div>
                                                <label class="db-label">{{ $column }}</label>
                                                @if ($column === 'user_type')
                                                    <select class="db-select" name="{{ $column }}">
                                                        <option value="user">user</option>
                                                        <option value="admin">admin</option>
                                                    </select>
                                                @elseif ($column === 'user_id')
                                                    <select class="db-select" name="{{ $column }}">
                                                        @foreach ($userOptions as $user)
                                                            <option value="{{ $user->id }}">{{ $user->id }} - {{ $user->name }}</option>
                                                        @endforeach
                                                    </select>
                                                @elseif ($column === 'shop_id')
                                                    <select class="db-select" name="{{ $column }}">
                                                        @foreach ($shopOptions as $shop)
                                                            <option value="{{ $shop->id }}">{{ $shop->shop_number }} - {{ $shop->name }}</option>
                                                        @endforeach
                                                    </select>
                                                @elseif ($column === 'password')
                                                    <input class="db-input" name="{{ $column }}" type="password" />
                                                @elseif (str_contains($column, 'date'))
                                                    <input class="db-input" name="{{ $column }}" type="date" />
                                                @elseif (str_contains($column, 'time'))
                                                    <input class="db-input" name="{{ $column }}" type="time" />
                                                @elseif (str_contains($column, 'collection') || str_ends_with($column, '_id'))
                                                    <input class="db-input" name="{{ $column }}" type="number" step="0.01" />
                                                @else
                                                    <input class="db-input" name="{{ $column }}" type="text" />
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                    <div class="db-actions">
                                        <button class="db-btn db-btn-primary" type="submit">
                                            <i class="fa-solid fa-plus"></i> Create Row
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="db-card" style="margin: 0;">
                            <div class="db-card-head">
                                <strong>{{ $editRow ? 'Edit row #'.$editRow->{$primaryKey} : 'Select a row to edit' }}</strong>
                            </div>
                            <div class="db-card-body">
                                @if ($editRow)
                                    <form method="POST" action="{{ route('admin.database.update', ['table' => $currentTable, 'id' => $editRow->{$primaryKey}]) }}">
                                        @csrf
                                        @method('PUT')
                                        <div class="db-form-grid">
                                            @foreach ($formColumns as $column)
                                                <div>
                                                    <label class="db-label">{{ $column }}</label>
                                                    @php $value = $editRow->{$column} ?? ''; @endphp
                                                    @if ($column === 'user_type')
                                                        <select class="db-select" name="{{ $column }}">
                                                            <option value="user" {{ $value === 'user' ? 'selected' : '' }}>user</option>
                                                            <option value="admin" {{ $value === 'admin' ? 'selected' : '' }}>admin</option>
                                                        </select>
                                                    @elseif ($column === 'user_id')
                                                        <select class="db-select" name="{{ $column }}">
                                                            @foreach ($userOptions as $user)
                                                                <option value="{{ $user->id }}" {{ (string) $value === (string) $user->id ? 'selected' : '' }}>
                                                                    {{ $user->id }} - {{ $user->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    @elseif ($column === 'shop_id')
                                                        <select class="db-select" name="{{ $column }}">
                                                            @foreach ($shopOptions as $shop)
                                                                <option value="{{ $shop->id }}" {{ (string) $value === (string) $shop->id ? 'selected' : '' }}>
                                                                    {{ $shop->shop_number }} - {{ $shop->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    @elseif ($column === 'password')
                                                        <input class="db-input" name="{{ $column }}" type="password" placeholder="Leave blank to keep current password" />
                                                    @elseif (str_contains($column, 'date'))
                                                        <input class="db-input" name="{{ $column }}" type="date" value="{{ $value ? \Illuminate\Support\Carbon::parse($value)->format('Y-m-d') : '' }}" />
                                                    @elseif (str_contains($column, 'time'))
                                                        <input class="db-input" name="{{ $column }}" type="time" value="{{ $value }}" />
                                                    @elseif (str_contains($column, 'collection') || str_ends_with($column, '_id'))
                                                        <input class="db-input" name="{{ $column }}" type="number" step="0.01" value="{{ $value }}" />
                                                    @else
                                                        <input class="db-input" name="{{ $column }}" type="text" value="{{ $value }}" />
                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>
                                        <div class="db-actions">
                                            <button class="db-btn db-btn-warning" type="submit">
                                                <i class="fa-solid fa-pen"></i> Update Row
                                            </button>
                                            <a class="db-btn db-btn-muted" href="{{ route('admin.database.index', ['table' => $currentTable]) }}">
                                                Clear
                                            </a>
                                        </div>
                                    </form>
                                @else
                                    <p class="db-sub">Use the table actions below to load a row into this editor.</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="db-card" style="margin-top: 16px;">
                        <div class="db-card-head">
                            <strong>{{ $currentTable }} rows</strong>
                        </div>
                        <div class="db-card-body">
                            <div class="db-table-wrap">
                                <table class="db-table">
                                    <thead>
                                        <tr>
                                            @foreach ($columns as $column)
                                                <th>{{ $column }}</th>
                                            @endforeach
                                            <th>actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($rows as $row)
                                            <tr>
                                                @foreach ($columns as $column)
                                                    <td>
                                                        <div class="db-kv">{{ $row->{$column} }}</div>
                                                    </td>
                                                @endforeach
                                                <td>
                                                    <div class="db-actions" style="margin-top: 0;">
                                                        <a class="db-btn db-btn-warning" href="{{ route('admin.database.index', ['table' => $currentTable, 'edit' => $row->{$primaryKey}]) }}">
                                                            Edit
                                                        </a>
                                                        <form method="POST" action="{{ route('admin.database.destroy', ['table' => $currentTable, 'id' => $row->{$primaryKey}]) }}" onsubmit="return confirm('Delete this row?');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button class="db-btn db-btn-danger" type="submit">Delete</button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="{{ count($columns) + 1 }}">No rows found.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            @if (method_exists($rows, 'currentPage'))
                                <div class="db-pagination">
                                    <div>
                                        Page {{ $rows->currentPage() }} of {{ $rows->lastPage() }} |
                                        Showing {{ $rows->firstItem() ?? 0 }}-{{ $rows->lastItem() ?? 0 }}
                                    </div>
                                    <div class="db-actions" style="margin-top: 0;">
                                        @if ($rows->onFirstPage())
                                            <span class="db-btn db-btn-muted">Previous</span>
                                        @else
                                            <a class="db-btn db-btn-muted" href="{{ $rows->previousPageUrl() }}">Previous</a>
                                        @endif
                                        @if ($rows->hasMorePages())
                                            <a class="db-btn db-btn-muted" href="{{ $rows->nextPageUrl() }}">Next</a>
                                        @else
                                            <span class="db-btn db-btn-muted">Next</span>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>
@endsection
