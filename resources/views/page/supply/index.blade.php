@extends("layout.admin.main")

@section("content")
    <section>
        <div class="page-header">
            <div>
                <h1>Incoming Supplies</h1>
                <p>Manage your ingredients/stocks.</p>
            </div>
            
            <button class="btn-primary open-modal" data-modal="addModal">
                <i class="fa-solid fa-plus"></i>
                New Supply
            </button>
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
                                <th style="width: 0;">ID BARANG</th>
                                <th style="width: 0;">NAMA BARANG</th>
                                <th style="width: 0;">JUMLAH</th>
                                <th style="">SATUAN</th>
                                <th style="width: 0; text-align: right">CREATED AT</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($supplies as $supply)
                                <tr>
                                    <td>
                                        <div class="order-id"><span class="dot"></span>#{{ $supply->normalizedId() }}</div>
                                    </td>
                                    <td>#{{ $supply->ingredient->normalizedId() }}</td>
                                    <td>{{ $supply->ingredient->name }}</td>
                                    <td style="text-align: right">{{ $supply->quantity }}</td>
                                    <td>{{ $supply->ingredient->unit_of_measurement->symbol }}</td>
                                    <td class="date-cell" style="text-align: right">{{ $supply->created_at }}</td>
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

        <div id="addModal" class="modal">
            <div class="modal-content">

                <div class="modal-header">
                    <div class="modal-title">
                        <div class="modal-icon">
                            <i class="fa-solid fa-box"></i>
                        </div>
                        <div>
                            <h2>New Supply</h2>
                            <p>Supply your stock.</p>
                        </div>
                    </div>

                    <button class="close-modal" type="button">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>

                <form method="POST" action="{{ route('supply.post') }}">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Barang</label>
                            <select name="ingredient_id">
                                @foreach ($ingredients as $ingredient)
                                    <option value="{{ $ingredient->id }}">#{{ $ingredient->normalizedId() }} - {{ $ingredient->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Quantity</label>
                            <input type="number" placeholder="0" name="quantity">
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
    </section>
@endsection

@section("script")
    
@endsection