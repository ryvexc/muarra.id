@extends("layout.admin.main")

@section("content")
<section>
    <div class="page-header">
        <div>
            <h1>Wallets</h1>
            <p>Manage global cash and cashless wallet transactions.</p>
        </div>
    </div>

    {{-- Wallet Cards --}}
    <div class="wallet-cards">
        {{-- Cash --}}
        <div class="wallet-card cash-card">
            <div class="wallet-card-top">
                <span class="wallet-type">Cash Wallet</span>
                <i class="fa-solid fa-wallet"></i>
            </div>

            <div>
                <div class="wallet-balance">
                    Rp 12.500.000
                </div>
    
                <div class="wallet-footer">
                    <span>GLOBAL ACCOUNT</span>
                    <span>•••• 4829</span>
                </div>
            </div>
        </div>

        {{-- Cashless --}}
        <div class="wallet-card cashless-card">
            <div class="wallet-card-top">
                <span class="wallet-type">Muarra Wallet</span>
                <i class="fa-solid fa-credit-card"></i>
            </div>

            <div class="wallet-balance">
                Rp 35.800.000
            </div>

            <div class="wallet-footer">
                <span>GLOBAL ACCOUNT</span>
                <span>•••• 9231</span>
            </div>
        </div>
    </div>

    {{-- Actions --}}
    <div class="wallet-actions">
        <button class="btn-primary">
            <i class="fa-solid fa-arrow-right-arrow-left"></i>
            Transfer
        </button>

        <button class="btn-secondary">
            <i class="fa-solid fa-plus"></i>
            Deposit
        </button>

        <button class="btn-secondary">
            <i class="fa-solid fa-money-bill-transfer"></i>
            Withdraw
        </button>

        <button class="btn-secondary">
            <i class="fa-solid fa-clock-rotate-left"></i>
            History
        </button>
    </div>

    {{-- Recent Transactions --}}
    <div class="transaction-section">
        <div class="section-title">
            <h2>Recent Transactions</h2>
            <p>Latest wallet activities.</p>
        </div>

        <div class="transaction-list">
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>TYPE</th>
                            <th>WALLET</th>
                            <th>DESCRIPTION</th>
                            <th style="text-align:right">AMOUNT</th>
                            <th style="text-align:right">BALANCE</th>
                            <th>DIBUAT PUKUL</th>
                            <th>TANGGAL</th>
                            <th>ACTIONS</th>
                        </tr>
                    </thead>

                    <tbody>
                        {{-- Date Group --}}
                        <tr class="date-group">
                            <td colspan="9">
                                <div class="date-label">
                                    <i class="fa-solid fa-calendar"></i>
                                    01 October 2026
                                    <span>(5 Transactions)</span>
                                </div>
                            </td>
                        </tr>

                        <tr>
                            <td>
                                <div class="order-id">
                                    <span class="dot"></span>
                                    #TRX-000010
                                </div>
                            </td>

                            <td>
                                <span class="badge success">
                                    Deposit
                                </span>
                            </td>

                            <td>
                                <div class="wallet-name">
                                    <i class="fa-solid fa-money-bill-wave"></i>
                                    Cash Wallet
                                </div>
                            </td>

                            <td>
                                <strong>
                                    Transfer from Global Cash Wallet
                                </strong>
                                <br>
                                <span style="color:var(--text-secondary)">
                                    Malang Branch Allocation
                                </span>
                            </td>

                            <td style="text-align:right;color:#4ade80">
                                + Rp5.000.000
                            </td>

                            <td style="text-align:right">
                                Rp17.500.000
                            </td>

                            <td class="date-cell">
                                08:15
                            </td>

                            <td class="date-cell">
                                01-10-2026
                            </td>

                            <td class="actions">
                                <a class="icon-btn primary" title="View">
                                    <i class="fa-solid fa-eye"></i>
                                </a>
                            </td>
                        </tr>

                        <tr>
                            <td>
                                <div class="order-id">
                                    <span class="dot"></span>
                                    #TRX-000011
                                </div>
                            </td>

                            <td>
                                <span class="badge warning">
                                    Transfer
                                </span>
                            </td>

                            <td>
                                <div class="wallet-name">
                                    <i class="fa-solid fa-money-bill-wave"></i>
                                    Cash Wallet
                                </div>
                            </td>

                            <td>
                                <strong>
                                    Transfer to Malang Branch
                                </strong>

                                <br>

                                <span style="color:var(--text-secondary)">
                                    Branch Fund Distribution
                                </span>
                            </td>

                            <td style="text-align:right;color:#f87171">
                                - Rp2.000.000
                            </td>

                            <td style="text-align:right">
                                Rp15.500.000
                            </td>

                            <td class="date-cell">
                                09:30
                            </td>

                            <td class="date-cell">
                                01-10-2026
                            </td>

                            <td class="actions">
                                <a class="icon-btn primary" title="View">
                                    <i class="fa-solid fa-eye"></i>
                                </a>
                            </td>
                        </tr>

                        {{-- Date Group --}}
                        <tr class="date-group">
                            <td colspan="9">

                                <div class="date-label">
                                    <i class="fa-solid fa-calendar"></i>
                                    02 October 2026
                                    <span>(3 Transactions)</span>
                                </div>

                            </td>
                        </tr>

                        <tr>
                            <td>
                                <div class="order-id">
                                    <span class="dot"></span>
                                    #TRX-000015
                                </div>
                            </td>

                            <td>
                                <span class="badge success">
                                    Deposit
                                </span>
                            </td>

                            <td>
                                <div class="wallet-name">
                                    <i class="fa-solid fa-credit-card"></i>
                                    Cashless Wallet
                                </div>
                            </td>

                            <td>
                                <strong>
                                    Marketplace Settlement
                                </strong>
                                <br>
                                <span style="color:var(--text-secondary)">
                                    Shopee / Tokopedia
                                </span>
                            </td>

                            <td style="text-align:right;color:#4ade80">
                                + Rp8.750.000
                            </td>

                            <td style="text-align:right">
                                Rp46.300.000
                            </td>

                            <td class="date-cell">
                                08:10
                            </td>

                            <td class="date-cell">
                                02-10-2026
                            </td>

                            <td class="actions">
                                <a class="icon-btn primary" title="View">
                                    <i class="fa-solid fa-eye"></i>
                                </a>
                            </td>
                        </tr>

                        <tr>
                            <td>
                                <div class="order-id">
                                    <span class="dot"></span>
                                    #TRX-000015
                                </div>
                            </td>

                            <td>
                                <span class="badge success">
                                    Deposit
                                </span>
                            </td>


                            <td>
                                <div class="wallet-name">
                                    <i class="fa-solid fa-credit-card"></i>
                                    Cashless Wallet
                                </div>
                            </td>

                            <td>
                                <strong>
                                    Marketplace Settlement
                                </strong>

                                <br>

                                <span style="color:var(--text-secondary)">
                                    Shopee / Tokopedia
                                </span>
                            </td>

                            <td style="text-align:right;color:#4ade80">
                                + Rp8.750.000
                            </td>

                            <td style="text-align:right">
                                Rp46.300.000
                            </td>

                            <td class="date-cell">
                                08:10
                            </td>

                            <td class="date-cell">
                                02-10-2026
                            </td>

                            <td class="actions">
                                <a class="icon-btn primary" title="View">
                                    <i class="fa-solid fa-eye"></i>
                                </a>
                            </td>
                        </tr>

                        <tr>
                            <td>
                                <div class="order-id">
                                    <span class="dot"></span>
                                    #TRX-000015
                                </div>
                            </td>

                            <td>
                                <span class="badge success">
                                    Deposit
                                </span>
                            </td>

                            <td>
                                <div class="wallet-name">
                                    <i class="fa-solid fa-credit-card"></i>
                                    Cashless Wallet
                                </div>
                            </td>

                            <td>
                                <strong>
                                    Marketplace Settlement
                                </strong>
                                <br>
                                <span style="color:var(--text-secondary)">
                                    Shopee / Tokopedia
                                </span>
                            </td>

                            <td style="text-align:right;color:#4ade80">
                                + Rp8.750.000
                            </td>

                            <td style="text-align:right">
                                Rp46.300.000
                            </td>

                            <td class="date-cell">
                                08:10
                            </td>

                            <td class="date-cell">
                                02-10-2026
                            </td>

                            <td class="actions">
                                <a class="icon-btn primary" title="View">
                                    <i class="fa-solid fa-eye"></i>
                                </a>
                            </td>
                        </tr>

                        <tr>
                            <td>
                                <div class="order-id">
                                    <span class="dot"></span>
                                    #TRX-000015
                                </div>
                            </td>

                            <td>
                                <span class="badge success">
                                    Deposit
                                </span>
                            </td>

                            <td>
                                <div class="wallet-name">
                                    <i class="fa-solid fa-credit-card"></i>
                                    Cashless Wallet
                                </div>
                            </td>

                            <td>
                                <strong>
                                    Marketplace Settlement
                                </strong>
                                <br>
                                <span style="color:var(--text-secondary)">
                                    Shopee / Tokopedia
                                </span>
                            </td>

                            <td style="text-align:right;color:#4ade80">
                                + Rp8.750.000
                            </td>

                            <td style="text-align:right">
                                Rp46.300.000
                            </td>

                            <td class="date-cell">
                                08:10
                            </td>

                            <td class="date-cell">
                                02-10-2026
                            </td>

                            <td class="actions">
                                <a class="icon-btn primary" title="View">
                                    <i class="fa-solid fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                        
                            <tr>

                                <td>
                                    <div class="order-id">
                                        <span class="dot"></span>
                                        #TRX-000015
                                    </div>
                                </td>


                                <td>
                                    <span class="badge success">
                                        Deposit
                                    </span>
                                </td>


                                <td>
                                    <div class="wallet-name">
                                        <i class="fa-solid fa-credit-card"></i>
                                        Cashless Wallet
                                    </div>
                                </td>


                                <td>
                                    <strong>
                                        Marketplace Settlement
                                    </strong>

                                    <br>

                                    <span style="color:var(--text-secondary)">
                                        Shopee / Tokopedia
                                    </span>
                                </td>


                                <td style="text-align:right;color:#4ade80">
                                    + Rp8.750.000
                                </td>


                                <td style="text-align:right">
                                    Rp46.300.000
                                </td>


                                <td class="date-cell">
                                    08:10
                                </td>


                                <td class="date-cell">
                                    02-10-2026
                                </td>


                                <td class="actions">

                                    <a class="icon-btn primary" title="View">
                                        <i class="fa-solid fa-eye"></i>
                                    </a>

                                </td>

                            </tr>
                            <tr>

                                <td>
                                    <div class="order-id">
                                        <span class="dot"></span>
                                        #TRX-000015
                                    </div>
                                </td>


                                <td>
                                    <span class="badge success">
                                        Deposit
                                    </span>
                                </td>


                                <td>
                                    <div class="wallet-name">
                                        <i class="fa-solid fa-credit-card"></i>
                                        Cashless Wallet
                                    </div>
                                </td>


                                <td>
                                    <strong>
                                        Marketplace Settlement
                                    </strong>

                                    <br>

                                    <span style="color:var(--text-secondary)">
                                        Shopee / Tokopedia
                                    </span>
                                </td>


                                <td style="text-align:right;color:#4ade80">
                                    + Rp8.750.000
                                </td>


                                <td style="text-align:right">
                                    Rp46.300.000
                                </td>


                                <td class="date-cell">
                                    08:10
                                </td>


                                <td class="date-cell">
                                    02-10-2026
                                </td>


                                <td class="actions">

                                    <a class="icon-btn primary" title="View">
                                        <i class="fa-solid fa-eye"></i>
                                    </a>

                                </td>

                            </tr>

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    </div>


</section>

@endsection