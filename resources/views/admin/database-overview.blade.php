@extends('admin.maindesign')

@section('content')
    <style>
        .db-overview-wrap { padding: 18px; }
        .db-head {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
            margin-bottom: 16px;
        }
        .db-title { margin: 0; font-size: 24px; font-weight: 700; color: #0f1f38; }
        .db-sub { margin: 4px 0 0; color: #5f6e86; font-size: 13px; }
        .db-btn {
            border: 0;
            border-radius: 10px;
            padding: 10px 14px;
            font-size: 13px;
            font-weight: 700;
            color: #fff;
            background: #1d4ed8;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        .db-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 14px;
        }
        .db-card {
            border: 1px solid #e7edf5;
            border-radius: 14px;
            background: #fff;
            box-shadow: 0 10px 24px rgba(16, 24, 40, 0.08);
            padding: 14px;
        }
        .db-card h3 { margin: 0 0 8px; font-size: 18px; color: #132640; }
        .db-meta { font-size: 12px; color: #4f617d; margin-bottom: 10px; }
        .db-open {
            border-radius: 10px;
            border: 1px solid #d6e0ec;
            background: #f8fbff;
            color: #1a2b45;
            padding: 8px 12px;
            text-decoration: none;
            font-size: 12px;
            font-weight: 700;
            display: inline-block;
        }
        .db-empty {
            border: 1px dashed #c9d4e6;
            border-radius: 12px;
            padding: 16px;
            background: #f8fbff;
            color: #4f617d;
            font-size: 13px;
        }
        @media (max-width: 1100px) { .db-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); } }
        @media (max-width: 700px) { .db-grid { grid-template-columns: 1fr; } }
    </style>

    <section class="db-overview-wrap">
        <div class="db-head">
            <div>
                <h2 class="db-title">Database Admin</h2>
                <p class="db-sub">Select a table to manage rows, or create a new table schema.</p>
            </div>
            <a class="db-btn" href="{{ route('admin.database.create-table.view') }}">
                <i class="fa-solid fa-table"></i> Add New Table
            </a>
        </div>

        @if (empty($tables))
            <div class="db-empty">No application tables found in the current database.</div>
        @else
            <div class="db-grid">
                @foreach ($tables as $table)
                    @php $meta = $tableInfo[$table] ?? null; @endphp
                    <div class="db-card">
                        <h3>{{ $table }}</h3>
                        <div class="db-meta">Columns: {{ $meta['columns_count'] ?? 0 }}</div>
                        <div class="db-meta">Rows: {{ $meta['rows_count'] ?? 0 }}</div>
                        <div class="db-meta">Timestamps: {{ ($meta['has_timestamps'] ?? false) ? 'Yes' : 'No' }}</div>
                        <div class="db-meta">Last update: {{ $meta['last_updated'] ?? 'N/A' }}</div>
                        <a class="db-open" href="{{ route('admin.database.index', ['table' => $table]) }}">Manage Table</a>
                    </div>
                @endforeach
            </div>
        @endif
    </section>
@endsection
