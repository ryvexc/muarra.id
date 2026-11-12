@extends("layout.admin.main")

@section("content")
    <section>
        <div class="page-header">
            <div>
                <h1>Orders</h1>
                <p>Manage all orders in one place.</p>
            </div>
            
            {{-- <button class="btn-primary">
                <i class="fa-solid fa-plus"></i>
                New Order
            </button> --}}
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