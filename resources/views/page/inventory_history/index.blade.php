@extends("layout.admin.main")

@section("content")
    <section>
        <div class="page-header">
            <div>
                <h1>Inventory Histories</h1>
                <p>All inventory movements are logged here.</p>
            </div>
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

                    <div class="form-group form-group-compact" style="width: 100%">
                        <label>Item</label>
                        <select name="ingredient_id" required
                            onchange="filter(this)"
                        >
                            <option value="" selected>All</option>

                            @foreach ($ingredients as $item)
                                <option value="{{ $item->id }}" @selected(request('ingredient_id') == $item->id)>{{ $item->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group form-group-compact" style="width: 100%">
                        <label>Item</label>
                        <select name="type" required
                            onchange="filter(this)"
                        >
                            <option value="" selected>All</option>

                            @foreach (["out", "in", "adjustment"] as $type)
                                <option value="{{ $type }}" @selected(request('type') == $type)>{{ $type }}</option>
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
                        <button class="btn-success" onclick="window.location.href = '{{ route('inventory-history.export', request()->query()) }}'">
                            <i class="fa-solid fa-file-excel"></i>
                        </button>
                    </div>
                </div>
                
                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th style="width: 0;">ID</th>
                                <th style="width: 0;">BRANCH</th>
                                <th style="width: 0;">ID BARANG</th>
                                <th style="width: 0;">NAMA BARANG</th>
                                <th style="width: 0;">TYPE</th>
                                <th style="width: 0;">QTY.</th>
                                <th style="width: 0;">S. BEFORE</th>
                                <th style="width: 0;">S. AFTER</th>
                                <th style="width: 0;">REF. - ID</th>
                                <th style="width: 0;">CREATED BY</th>
                                <th>DESCRIPTION</th>
                                <th style="width: 0; text-align: right">CREATED AT</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($inventory_histories as $inventory_history)
                                <tr>
                                    <td>
                                        <div class="order-id"><span class="dot"></span>#{{ $inventory_history->normalizedId() }}</div>
                                    </td>
                                    <td>{{ $inventory_history->branch->name }}</td>
                                    <td>{{ $inventory_history->ingredient->normalizedId() }}</td>
                                    <td>
                                        {{ $inventory_history->ingredient->name }}
                                        @if ($inventory_history->ingredient->deleted_at)
                                            <span style="color:#f87171">(DELETED)</span>
                                        @endif
                                    </td>

                                    <td>
                                        @if ($inventory_history->type === 'out')
                                            <span class="badge danger">Out</span>
    
                                        @elseif ($inventory_history->type === 'in')
                                            <span class="badge success">In</span>

                                        @elseif ($inventory_history->type === 'adjustment')
                                            <span class="badge info">Adj.</span>
                                        @endif
                                    </td>

                                    <td style="text-align: center">{{ $inventory_history->quantity }}</td>
                                    <td style="text-align: center">{{ $inventory_history->stock_before }}</td>
                                    <td style="text-align: center">{{ $inventory_history->stock_after }}</td>
                                    <td>{{ $inventory_history->reference_type }} - {{ $inventory_history->reference_id }}</td>
                                    @if($inventory_history->createdByStaff)
                                        <td>{{ $inventory_history->createdByStaff->name }}</td>
                                    @else
                                        <td>{{ $inventory_history->createdByUser->name }}</td>
                                    @endif
                                    <td>{{ $inventory_history->notes }}</td>
                                    <td class="date-cell" style="text-align: right">{{ $inventory_history->created_at }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colSpan="9999" class="date-cell" style="text-align: center">No data.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <div class="footer">
                    {{ $inventory_histories->links() }}
                </div>
            </div>
        </div>
    </section>
@endsection

@section("script")
    
@endsection