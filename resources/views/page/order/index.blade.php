@extends("layout.admin.main")

@section("content")
    <section>
        <div class="page-header">
            <div>
                <h1>Orders</h1>
                <p>Manage all orders in one place.</p>
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
                        <label>Cashier</label>
                        <select name="cashier_id" required
                            onchange="filter(this)"
                        >
                            <option value="" selected>All</option>

                            @foreach ($cashiers as $cashier)
                                <option value="{{ $cashier->id }}" @selected(request('cashier_id') == $cashier->id)>{{ $cashier->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group form-group-compact" style="width: 100%">
                        <label>Payment Type</label>
                        <select name="payment_type" required
                            onchange="filter(this)"
                        >
                            <option value="" selected>All</option>

                            @foreach (["Card", "Cash", "Qris"] as $payment_type)
                                <option value="{{ strtolower($payment_type) }}" @selected(request('payment_type') == strtolower($payment_type))>{{ $payment_type }}</option>
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
                        {{-- <form method="GET" action="{{ url()->current() }}" id="searchForm">
                            <div class="query-search">
                                <div class="query-search-input">
                                    <i class="fas fa-search"></i>

                                    <input
                                        type="text"
                                        name="q"
                                        id="querySearch"
                                        value="{{ request('q') }}"
                                        placeholder="Search orders... (e.g. payment_type:qris)"
                                        autocomplete="off"
                                    >

                                    <button type="button" id="clearSearch">×</button>
                                </div>

                                <div id="searchSuggestions" class="search-suggestions"></div>

                                <div id="searchPreview" class="search-preview"></div>
                            </div>
                        </form> --}}
                    </div>
                    
                    <div class="toolbar-right">
                        <button class="btn-success" onclick="window.location.href = '{{ route('order.export', request()->query()) }}'">
                            <i class="fa-solid fa-file-excel"></i>
                        </button>
                    </div>
                </div>
                
                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th style="width: 0">ORDER ID</th>
                                <th style="width: 0">BRANCH</th>
                                <th style="width: 0">CASHIER</th>
                                <th style="width: 0">CUST NAME</th>
                                <th>ITEMS</th>
                                <th style="width: 0">PAYMENT TYPE</th>
                                <th style="width: 0">SUBTOTAL</th>
                                <th style="width: 0">DISCOUNT</th>
                                <th style="width: 0">TOTAL</th>
                                <th style="text-align: right; width: 0">TANGGAL</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($orders as $order)
                                <tr>
                                    <td>
                                        <div class="order-id"><span class="dot"></span>#{{ $order->normalizedId() }}</div>
                                    </td>
                                    <td>{{ $order->branch->name }}</td>
                                    <td>
                                        <div class="cashier">
                                            <div class="avatar">{{ $order->cashier->name[0] }}</div>{{ $order->cashier->name }}
                                        </div>
                                    </td>
                                    <td>{{ $order->cust_name }}</td>
                                    
                                    <td>
                                        <ul style="display: flex; flex-direction: column; gap: 4px;">
                                            @foreach ($order->orderItems as $orderItem)
                                                <li style="list-style-type: none">
                                                    - {{ $orderItem->quantity }}x {{ $orderItem->product->name }} 
                                                    <span class="date-cell">({{ App\Support\Currency::format("IDR", $orderItem->total_price) }})</span>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </td>
                                    
                                    @if ($order->payment_type == "cash")
                                        <td><span class="badge cash"><span class="dot"></span>Cash</span></td>
                                    @elseif($order->payment_type == "card")
                                        <td><span class="badge card"><span class="dot"></span>Card</span></td>
                                    @elseif($order->payment_type == "qris")
                                        <td><span class="badge qr"><span class="dot"></span>QRIS</span></td>
                                    @endif

                                    <td>{{ App\Support\Currency::format("IDR", $order->subtotal)  }}</td>
                                    <td>{{ $order->discount->name ?? "" }}</td>

                                    <td class="amount">{{ App\Support\Currency::format("IDR", $order->total) }}</td>
                                    <td class="date-cell" style="text-align: right">{{ Carbon\Carbon::parse($order->created_at)->format('d M Y · H:i') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <div class="footer">
                    {{ $orders->links() }}
                </div>
            </div>
        </div>
    </section>
@endsection