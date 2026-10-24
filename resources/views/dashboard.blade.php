@extends("layout.admin.main")

@section("content")
    <section>
        <div class="page-header">
            <div>
                <h1>Transactions</h1>
                <p>Manage all transactions in one place.</p>
            </div>
            
            <button class="btn-primary">
                <i class="fa-solid fa-plus"></i>
                New Order
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
                                <th>ORDER ID</th>
                                <th>CASHIER</th>
                                <th>PAYMENT TYPE</th>
                                <th class="num">AMOUNT</th>
                                <th style="text-align: right">TANGGAL</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <div class="order-id"><span class="dot"></span>#ORD-1024</div>
                                </td>
                                <td>
                                    <div class="cashier">
                                        <div class="avatar">A</div>Arif
                                    </div>
                                </td>
                                <td><span class="badge cash"><span class="dot"></span>Cash</span></td>
                                <td class="amount">Rp45.000</td>
                                <td class="date-cell" style="text-align: right">23 Sep 2026 · 09:12</td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="order-id"><span class="dot"></span>#ORD-1025</div>
                                </td>
                                <td>
                                    <div class="cashier">
                                        <div class="avatar">R</div>Rina
                                    </div>
                                </td>
                                <td><span class="badge qr"><span class="dot"></span>Cashless</span></td>
                                <td class="amount">Rp78.000</td>
                                <td class="date-cell" style="text-align: right">23 Sep 2026 · 09:18</td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="order-id"><span class="dot"></span>#ORD-1026</div>
                                </td>
                                <td>
                                    <div class="cashier">
                                        <div class="avatar">D</div>Dimas
                                    </div>
                                </td>
                                <td><span class="badge card"><span class="dot"></span>Card</span></td>
                                <td class="amount">Rp32.000</td>
                                <td class="date-cell" style="text-align: right">23 Sep 2026 · 09:24</td>
                            </tr>
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