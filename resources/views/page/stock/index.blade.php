@extends("layout.admin.main")

@php
    $hasSupply = $ingredients->contains(function ($ingredient) {
        return $ingredient->supplies->whereNull('stock_report_id')->isNotEmpty();
    });

    $hasOutgoingStock = $ingredients->contains(function ($ingredient) {
        return $ingredient->inventoryHistory->where("type", "out")
            ->where("reference_type", "order")
            ->whereNull("stock_report_id")->isNotEmpty();
    });
@endphp

@section("content")
    <section>
        <div class="page-header">
            <div>
                <h1>Ingredients/Stocks</h1>
                <p>Manage your ingredients/stocks.</p>
            </div>
            
            <button class="btn-primary open-modal" data-modal="addModal">
                <i class="fa-solid fa-plus"></i>
                New Ingredient
            </button>
        </div>
        <div>
            <div style="display: flex; justify-content: space-between; width: 100%">
                <div style="display: inline-flex; gap:16px; margin-bottom: 20px;">
                    <div class="form-group form-group-compact" style="width: 100%">
                        <label>Per Page</label>
                        <select name="per_page" required
                            onchange="filter(this)"
                        >
                            @foreach ([10, 25, 50, 100, "All"] as $per_page_value)
                                <option value="{{ $per_page_value }}" @selected(request('per_page') == $per_page_value)>{{ $per_page_value }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group form-group-compact" style="width: 100%">
                        <label>Branch</label>
                        <select name="branch_id" required
                            onchange="filter(this)"
                        >
                            <option value="" selected>All</option>

                            @foreach ($branches as $branch)
                                <option value="{{ $branch->id }}" @selected(request('branch_id') == $branch->id)>{{ $branch->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div style="display: inline-flex; gap: 20px;">
                    <div class="form-group form-group-compact" style="width: 100%">
                        <label>From</label>
                        <input
                            type="date"
                            name="start_date"
                            value="{{ request('start_date') }}"
                            onchange="filter(this)"
                        >
                    </div>

                    <div class="form-group form-group-compact" style="width: 100%">
                        <label>To</label>
                        <input
                            type="date"
                            name="end_date"
                            value="{{ request('end_date') }}"
                            onchange="filter(this)"
                        >
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="toolbar">
                    <div class="search">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke-width="2">
                            <circle cx="11" cy="11" r="7" />
                            <path d="m21 21-4.3-4.3" />
                        </svg>
                        <input placeholder="Im looking for...">
                    </div>
                    
                    <div class="toolbar-right">
                        <button class="btn-success" onclick="window.location.href = '{{ route('stock.export', request()->query()) }}'">
                            <i class="fa-solid fa-file-excel"></i>
                        </button>
                    </div>
                </div>
                
                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th style="width: 0;">ID</th>
                                <th style="width: 0;">NAMA BARANG</th>
                                <th style="width: 0;">STOK</th>
                                <th style="width: 0;">SATUAN</th>
                                <th style="">HARGA SATUAN</th>
                                <th style="width: 0;">UPDATED AT</th>
                                <th style="width: 0;">CREATED AT</th>
                                <th style="width: 0; text-align: right">ACTIONS</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (request("branch_id")) @forelse($inventories as $inventory)
                                <tr>
                                    <td>
                                        <div class="order-id"><span class="dot"></span>#{{ $inventory->ingredient->normalizedId() }}</div>
                                    </td>
                                    <td>{{ $inventory->ingredient->name }}</td>
                                    <td>
                                        {{ $inventory->stock }}

                                        @if ($inventory->supply_in > 0)
                                            <span
                                                class="th-tooltip supply-tooltip"
                                                style="color: #4ade80; cursor: help;"
                                            >
                                                (+{{ $inventory->supply_in }})

                                                <span class="tooltip-box">
                                                    Added from supplies
                                                </span>
                                            </span>
                                        @endif

                                        @if ($inventory->outgoingStock > 0)
                                            <span
                                                class="th-tooltip supply-tooltip"
                                                style="color: #de4a4a; cursor: help;"
                                            >
                                                (-{{ $inventory->outgoingStock }})

                                                <span class="tooltip-box">
                                                    Reduced by outgoing
                                                </span>
                                            </span>
                                        @endif
                                    </td>

                                    <td>{{ $inventory->ingredient->unit_of_measurement->symbol }}</td>
                                    <td>{{ App\Support\Currency::format("IDR", $inventory->ingredient->harga_satuan) }}/{{ $inventory->ingredient->unit_of_measurement->symbol }}</td>
                                    <td class="date-cell" style="text-align: right">{{ $inventory->ingredient->updated_at }}</td>
                                    <td class="date-cell" style="text-align: right">{{ $inventory->ingredient->created_at }}</td>
                                    <td class="actions">
                                        <button class="icon-btn edit open-modal" title="Edit" data-modal="editModal-{{ $inventory->ingredient->id }}">
                                            <i class="fa-solid fa-pen"></i>
                                        </button>

                                        <button class="icon-btn delete open-modal" title="Delete" data-modal="deleteModal-{{ $inventory->ingredient->id }}">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colSpan="9999" class="date-cell" style="text-align: center">No data.</td>
                                </tr>
                            @endforelse @else @forelse($ingredients as $ingredient)
                                <tr>
                                    <td>
                                        <div class="order-id"><span class="dot"></span>#{{ $ingredient->normalizedId() }}</div>
                                    </td>
                                    <td>{{ $ingredient->name }}</td>
                                    <td style="text-align: {{ $hasSupply || $hasOutgoingStock ? 'left' : 'center' }}">
                                        {{ $ingredient->inventory->sum("stock") }}

                                        @if ($ingredient->supplies->whereNull("stock_report_id")->count() > 0)
                                            <span
                                                class="th-tooltip supply-tooltip"
                                                style="color: #4ade80; cursor: help;"
                                            >
                                                (+{{ $ingredient->supplies->whereNull("stock_report_id")->sum("quantity") }})

                                                <span class="tooltip-box">
                                                    Added from supplies
                                                </span>
                                            </span>
                                        @endif

                                        @if ($ingredient->outgoingStock > 0)
                                            <span
                                                class="th-tooltip supply-tooltip"
                                                style="color: #de4a4a; cursor: help;"
                                            >
                                                (-{{ $ingredient->outgoingStock }})

                                                <span class="tooltip-box">
                                                    Reduced by outgoing
                                                </span>
                                            </span>
                                        @endif
                                    </td>
                                    <td>{{ $ingredient->unit_of_measurement->symbol }}</td>
                                    <td>{{ App\Support\Currency::format("IDR", $ingredient->harga_satuan) }}/{{ $ingredient->unit_of_measurement->symbol }}</td>
                                    <td class="date-cell" style="text-align: right">{{ $ingredient->updated_at }}</td>
                                    <td class="date-cell" style="text-align: right">{{ $ingredient->created_at }}</td>
                                    <td class="actions">
                                        <button class="icon-btn edit open-modal" title="Edit" data-modal="editModal-{{ $ingredient->id }}">
                                            <i class="fa-solid fa-pen"></i>
                                        </button>

                                        <button class="icon-btn delete open-modal" title="Delete" data-modal="deleteModal-{{ $ingredient->id }}">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colSpan="9999" class="date-cell" style="text-align: center">No data.</td>
                                </tr>
                            @endforelse @endif
                        </tbody>
                    </table>
                </div>
                
                <div class="footer">
                    {{ $inventories->links() }}
                </div>
            </div>
        </div>

        <div id="addModal" class="modal">
            <div class="modal-content">

                <div class="modal-header">
                    <div class="modal-title">
                        <div class="modal-icon">
                            <i class="fa-solid fa-box"></i>
                        </div>
                        <div>
                            <h2>New Ingredient</h2>
                            <p>Add a new stock item.</p>
                        </div>
                    </div>

                    <button class="close-modal" type="button">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>

                <form method="POST" action="{{ route('stock.post') }}">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Ingredient Name</label>
                            <input type="text" placeholder="Example: Sugar" name="name" required>
                        </div>

                        <div style="display: flex; gap: 20px">
                            <div class="form-group" style="width: 100%">
                                <label>Harga Satuan</label>
                                <input type="text" inputmode="numeric" class="currency-input" placeholder="0" name="harga_satuan" required>
                            </div>

                            <div class="form-group" style="width: 100%">
                                <label>Unit of Measurement</label>
                                <select name="unit_of_measurement_id" required>
                                    <option value="" selected disabled>-- Select Unit --</option>
    
                                    @foreach ($units as $unit)
                                        <option value="{{ $unit->id }}">{{ $unit->symbol }} ({{ $unit->name }})</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button class="btn-secondary modal-close" type="button">Cancel</button>
                        <button class="btn-primary" type="submit">
                            <i class="fa-solid fa-floppy-disk"></i>
                            Save
                        </button>
                    </div>
                </form>
            </div>
        </div>

        @foreach ($ingredients as $ingredient)
            <div id="editModal-{{ $ingredient->id }}" class="modal">
                <div class="modal-content">

                    <div class="modal-header">
                        <div class="modal-title">
                            <div class="modal-icon">
                                <i class="fa-solid fa-box"></i>
                            </div>
                            <div>
                                <h2>Edit Ingredient/Stock</h2>
                                <p>Edit #{{ $ingredient->normalizedId() }}.</p>
                            </div>
                        </div>

                        <button class="close-modal" type="button">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>

                    <form method="POST" action="{{ route('stock.edit', ['id' => $ingredient->id]) }}">
                        @csrf
                        @method("PUT")
                        <div class="modal-body">
                            <div class="form-group">
                                <label>Ingredient Name</label>
                                <input type="text" placeholder="Example: Sugar" name="name" value="{{ $ingredient->name }}">
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button class="btn-secondary modal-close" type="button">Cancel</button>
                            <button class="btn-primary" type="submit">
                                <i class="fa-solid fa-floppy-disk"></i>
                                Save
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div id="deleteModal-{{ $ingredient->id }}" class="modal">
                <div class="modal-content">

                    <div class="modal-header">
                        <div class="modal-title">
                            <div class="modal-icon">
                                <i class="fa-solid fa-box"></i>
                            </div>
                            <div>
                                <h2>Delete Ingredient/Stock</h2>
                                <p>Delete #{{ $ingredient->normalizedId() }}.</p>
                            </div>
                        </div>

                        <button class="close-modal" type="button">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>

                    <form method="POST" action="{{ route('stock.delete', ['id' => $ingredient->id]) }}">
                        @csrf
                        @method("DELETE")

                        <div style="padding: 16px">
                            <p style="margin-bottom: 12px">Are you sure you wanna delete this Stock/Ingredients?</p>
    
                            <ul class="info-list">
                                <li>
                                    <span class="label">Name</span>
                                    <span class="value">Sugar</span>
                                </li>
    
                                <li>
                                    <span class="label">Current Stock</span>
                                    <span class="value">32 Gr</span>
                                </li>
    
                                <li>
                                    <span class="label">Unit</span>
                                    <span class="value">Gram</span>
                                </li>
    
                                <li>
                                    <span class="label">Last Updated</span>
                                    <span class="value">26 Sep 2026 · 09:35</span>
                                </li>
                            </ul>
                        </div>

                        <div class="modal-footer">
                            <button class="btn-secondary modal-close" type="button">Cancel</button>
                            <button class="btn-primary" type="submit">
                                <i class="fa-solid fa-floppy-disk"></i>
                                Yes, Delete it
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @endforeach
    </section>
@endsection

@section("script")
    <script>
        document.querySelectorAll('.currency-input').forEach(input => {
        input.addEventListener('input', function () {
            let raw = this.value.replace(/\D/g, '');

            this.value = raw
                ? new Intl.NumberFormat('id-ID').format(Number(raw))
                : '';

            // Cari hidden input yang namanya *_raw
            const rawInput = document.getElementById(`${this.id}_raw`);

            if (rawInput) {
                rawInput.value = raw;
            }
        });
    });
    </script>
@endsection