@extends("layout.admin.main")

@section("content")
    <section>
        <div class="page-header">
            <div>
                <h1>Incoming Supplies</h1>
                <p>Manage your ingredients/stocks.</p>
            </div>
        </div>
        <div>
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
                        <button class="btn">Export</button>
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
                                <th>NOTES</th>
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
                                    <td>{{ $inventory_history->ingredient->name }}</td>

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
                                    <td>{{ $inventory_history->createdByStaff->name }}</td>
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
                    <span>Showing 3 dari 128</span>
                    <div class="pagination">
                        <button class="active">1</button>
                        <button>2</button>
                        <button>3</button>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section("script")
    
@endsection