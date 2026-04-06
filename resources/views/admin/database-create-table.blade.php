@extends('admin.maindesign')

@section('content')
    <style>
        .db-create-wrap { padding: 18px; }
        .db-card {
            border: 1px solid #e7edf5;
            border-radius: 16px;
            background: #fff;
            box-shadow: 0 12px 30px rgba(16, 24, 40, 0.08);
        }
        .db-card-head {
            padding: 16px 18px;
            border-bottom: 1px solid #edf2f8;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }
        .db-title { margin: 0; font-size: 22px; font-weight: 700; color: #0f1f38; }
        .db-sub { margin: 4px 0 0; color: #5f6e86; font-size: 13px; }
        .db-body { padding: 16px 18px; }
        .db-form-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 12px;
        }
        .db-create-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 14px;
            margin-top: 12px;
        }
        .db-column-box {
            border: 1px solid #e7edf5;
            border-radius: 12px;
            padding: 12px;
            background: #fbfdff;
        }
        .db-column-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 8px;
            margin-bottom: 8px;
        }
        .db-column-title {
            font-size: 13px;
            font-weight: 700;
            color: #1f3552;
        }
        .db-label { display: block; font-size: 12px; font-weight: 700; color: #30445f; margin-bottom: 6px; }
        .db-input, .db-select {
            width: 100%;
            border: 1px solid #d6e0ec;
            border-radius: 10px;
            padding: 9px 10px;
            font-size: 13px;
            color: #12233f;
            background: #fff;
        }
        .db-actions { margin-top: 12px; display: flex; gap: 8px; flex-wrap: wrap; }
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
        .db-btn-muted { background: #e2e8f0; color: #0f172a; }
        .db-btn-danger { background: #be123c; color: #fff; }
        .db-alert {
            border-radius: 10px;
            padding: 10px 12px;
            margin-bottom: 12px;
            font-size: 13px;
            background: #fde7ee;
            color: #9f1239;
        }
        @media (max-width: 1100px) { .db-create-grid { grid-template-columns: 1fr; } }
        @media (max-width: 800px) { .db-form-grid { grid-template-columns: 1fr; } }
    </style>

    <section class="db-create-wrap">
        <div class="db-card">
            <div class="db-card-head">
                <div>
                    <h2 class="db-title">Create New Table</h2>
                    <p class="db-sub">Define table schema with optional foreign keys.</p>
                </div>
                <a class="db-btn db-btn-muted" href="{{ route('admin.database.index') }}">Back to tables</a>
            </div>
            <div class="db-body">
                @if ($errors->any())
                    <div class="db-alert">{{ $errors->first() }}</div>
                @endif

                <form method="POST" action="{{ route('admin.database.create-table') }}">
                    @csrf
                    <div class="db-form-grid">
                        <div>
                            <label class="db-label">Table name</label>
                            <input class="db-input" type="text" name="table_name" placeholder="example: products" required />
                        </div>
                    </div>

                    <p class="db-sub" style="margin: 10px 0 12px;">
                        System fields added automatically: <code>id</code>, <code>created_at</code>, <code>updated_at</code>
                    </p>

                    <div class="db-actions" style="margin-top: 0;">
                        <button class="db-btn db-btn-primary" type="button" id="add-column-btn">
                            <i class="fa-solid fa-plus"></i> Add Column
                        </button>
                    </div>

                    <div class="db-create-grid" id="column-container"></div>

                    <div class="db-actions">
                        <button class="db-btn db-btn-primary" type="submit">
                            <i class="fa-solid fa-table"></i> Create Table
                        </button>
                        <a class="db-btn db-btn-muted" href="{{ route('admin.database.index') }}">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <template id="column-template">
        <div class="db-column-box" data-column-card>
            <div class="db-column-head">
                <span class="db-column-title">Column <span data-column-number></span></span>
                <button class="db-btn db-btn-danger" type="button" data-remove-column>
                    <i class="fa-solid fa-trash"></i> Delete
                </button>
            </div>

            <div class="db-form-grid">
                <div>
                    <label class="db-label">Column name</label>
                    <input class="db-input" type="text" data-field="name" placeholder="column_name" />
                </div>
                <div>
                    <label class="db-label">Type</label>
                    <select class="db-select" data-field="type">
                        @foreach ($tableTemplates as $type => $label)
                            <option value="{{ $type }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="db-label">Default value</label>
                    <input class="db-input" type="text" data-field="default" />
                </div>
                <div>
                    <label class="db-label">Foreign table</label>
                    <select class="db-select" data-field="foreign_table">
                        <option value="">None</option>
                        @foreach ($tables as $table)
                            <option value="{{ $table }}">{{ $table }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="db-label">Foreign column</label>
                    <input class="db-input" type="text" data-field="foreign_column" placeholder="id" />
                </div>
                <div>
                    <label class="db-label">On delete</label>
                    <select class="db-select" data-field="on_delete">
                        <option value="cascade">cascade</option>
                        <option value="restrict">restrict</option>
                        <option value="set null">set null</option>
                        <option value="no action">no action</option>
                    </select>
                </div>
            </div>

            <div class="db-actions" style="margin-top: 8px;">
                <label><input type="checkbox" data-field="nullable" value="1" /> nullable</label>
                <label><input type="checkbox" data-field="index" value="1" /> index</label>
                <label><input type="checkbox" data-field="unique" value="1" /> unique</label>
                <label><input type="checkbox" data-field="is_foreign" value="1" /> foreign key</label>
            </div>

            <div class="db-form-grid" style="margin-top: 8px;">
                <div>
                    <label class="db-label">Decimal precision</label>
                    <input class="db-input" type="number" data-field="precision" min="1" max="65" placeholder="10" />
                </div>
                <div>
                    <label class="db-label">Decimal scale</label>
                    <input class="db-input" type="number" data-field="scale" min="0" max="30" placeholder="2" />
                </div>
            </div>
        </div>
    </template>

    <script>
        (function () {
            const container = document.getElementById('column-container');
            const template = document.getElementById('column-template');
            const addButton = document.getElementById('add-column-btn');
            const DEFAULT_COLUMNS = 3;

            function setInputNames() {
                const cards = container.querySelectorAll('[data-column-card]');
                cards.forEach(function (card, index) {
                    const number = card.querySelector('[data-column-number]');
                    if (number) {
                        number.textContent = String(index + 1);
                    }

                    card.querySelectorAll('[data-field]').forEach(function (field) {
                        const key = field.getAttribute('data-field');
                        field.setAttribute('name', 'columns[' + index + '][' + key + ']');
                    });
                });
            }

            function addColumnCard() {
                const fragment = template.content.cloneNode(true);
                const card = fragment.querySelector('[data-column-card]');
                const removeButton = fragment.querySelector('[data-remove-column]');

                removeButton.addEventListener('click', function () {
                    card.remove();
                    setInputNames();
                });

                container.appendChild(fragment);
                setInputNames();
            }

            addButton.addEventListener('click', function () {
                addColumnCard();
            });

            for (let i = 0; i < DEFAULT_COLUMNS; i += 1) {
                addColumnCard();
            }
        })();
    </script>
@endsection
