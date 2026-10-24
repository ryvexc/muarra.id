@extends("layout.admin.main")

@section("content")
    <section>
        <div class="page-header">
            <div>
                <h1>Stock Reports</h1>
                <p>Manage your stocks.</p>
            </div>
            
            <a class="btn-primary" href="{{ route('stock-report.new') }}">
                <i class="fa-solid fa-plus"></i>
                New Report
            </a>
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
                                <th rowspan="2">ID</th>
                                <th rowspan="2">NAMA PENCATAT</th>
                                <th rowspan="2">CABANG</th>

                                <th colspan="2">STOCK SURPLUS</th>
                                <th colspan="2">STOCK SHORTAGE</th>

                                <th rowspan="2">STOCK MATCHED</th>
                                <th rowspan="2">STATUS</th>
                                <th rowspan="2">DIBUAT PUKUL</th>
                                <th rowspan="2">TANGGAL</th>
                                <th rowspan="2">ACTIONS</th>
                            </tr>

                            <tr>
                                <th style="text-align: center">COUNT</th>
                                <th style="text-align: right">VALUE</th>

                                <th style="text-align: center">COUNT</th>
                                <th style="text-align: right">VALUE</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($stockReports as $date => $reports)
                                <tr class="date-group">
                                    <td colspan="12">
                                        <div class="date-label">
                                            <i class="fa-solid fa-calendar"></i>
                                            {{ \Carbon\Carbon::parse($date)->translatedFormat('d F Y') }}
                                            <span>({{ $reports->count() }} Reports)</span>
                                        </div>
                                    </td>
                                </tr>

                                @foreach ($reports as $stockReport)
                                    <tr>
                                        <td>
                                            <div class="order-id"><span class="dot"></span>#{{ $stockReport->normalizedId() }}</div>
                                        </td>
                                        <td>
                                            <div class="cashier">
                                                <div class="avatar">A</div>Arif
                                            </div>
                                        </td>
                                        <td>Kopi 1</td>

                                        <td style="text-align: center; color: {{ $stockReport->stock_surplus_count > 0 ? '#fbbf24' : 'inherit' }}">
                                            {{ $stockReport->stock_surplus_count }}
                                        </td>
                                        <td style="text-align: right; color: {{ $stockReport->stock_surplus_value > 0 ? '#fbbf24' : 'inherit' }}">
                                            {{ App\Support\Currency::format("IDR", $stockReport->stock_surplus_value) }}
                                        </td>

                                        <td style="text-align: center; color: {{ $stockReport->stock_shortage_count > 0 ? '#f87171' : 'inherit' }}">
                                            {{ $stockReport->stock_shortage_count }}
                                        </td>
                                        <td style="text-align: right; color: {{ $stockReport->stock_shortage_value > 0 ? '#f87171' : 'inherit' }}">
                                            {{ App\Support\Currency::format("IDR", $stockReport->stock_shortage_value) }}
                                        </td>

                                        <td style="text-align: center">{{ $stockReport->stock_matched_count }}</td>
                                        <td>
                                            @if ($stockReport->status === 'shortage')
                                                <span class="badge danger">Shortage</span>

                                            @elseif ($stockReport->status === 'surplus')
                                                <span class="badge warning">Surplus</span>

                                            @else
                                                <span class="badge success">Matched</span>
                                            @endif
                                        </td>
                                        <td class="date-cell">{{ explode(" ", $stockReport->created_at)[1] }}</td>
                                        <td class="date-cell">{{ explode(" ", $stockReport->created_at)[0] }}</td>
                                        <td class="actions">
                                            <a class="icon-btn primary" title="View" href="{{ route('stock-report.view', ['id' => $stockReport->id ]) }}">
                                                <i class="fa-solid fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
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