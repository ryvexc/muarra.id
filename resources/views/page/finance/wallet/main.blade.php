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
                    {{ App\Support\Currency::format("IDR", $cashWallet->balance) }}
                </div>
    
                <div class="wallet-footer">
                    <span>GLOBAL ACCOUNT</span>
                    <span>**** **** {{ explode('-', $cashWallet->uuid)[2] }} {{ explode('-', $cashWallet->uuid)[3] }}</span>
                </div>
            </div>
        </div>

        {{-- Cashless --}}
        <div class="wallet-card cashless-card">
            <div class="wallet-card-top">
                <span class="wallet-type">Cashless Wallet</span>
                <i class="fa-solid fa-credit-card"></i>
            </div>

            <div class="wallet-balance">
                {{ App\Support\Currency::format("IDR", $cashlessWallet->balance) }}
            </div>

            <div class="wallet-footer">
                <span>GLOBAL ACCOUNT</span>
                <span>**** **** {{ explode('-', $cashlessWallet->uuid)[2] }} {{ explode('-', $cashlessWallet->uuid)[3] }}</span>
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
                            <th style="width: 0">ID</th>
                            <th style="width: 0">WALLET</th>
                            <th style="width: 0">AMOUNT</th>
                            <th style="width: 0">DESCRIPTION</th>
                            <th>NOTES</th>
                            <th style="width: 0">DIBUAT PUKUL</th>
                            <th style="width: 0">TANGGAL</th>
                            <th style="width: 0">ACTIONS</th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr class="date-group">
                            <td colspan="9">
                                <div class="date-label">
                                    <i class="fa-solid fa-calendar"></i>
                                    {{ Carbon\Carbon::today()->translatedFormat("d F Y") }}
                                    <span>({{ $transactions->count() }} Transactions)</span>
                                </div>
                            </td>
                        </tr>

                        @forelse ($transactions as $transaction)
                            <tr>
                                <td>
                                    <div class="order-id">
                                        <span class="dot"></span>
                                        #{{ $transaction->normalizedId() }}
                                    </div>
                                </td>

                                <td>{{ ucfirst($transaction->wallet->type) }}</td>

                                @if ($transaction->type == "credit")
                                    <td style="color:#4ade80">
                                        +{{ App\Support\Currency::format("IDR", $transaction->amount) }}
                                    </td>
                                @elseif ($transaction->type == "debit")
                                    <td style="color:#f87171">
                                        -{{ App\Support\Currency::format("IDR", $transaction->amount) }}
                                    </td>
                                @endif

                                <td>
                                    <strong>
                                        {{ $transaction->description }}
                                    </strong>
                                    <br>
                                    <span style="color:var(--text-secondary); font-size: 14px">
                                        {{ $transaction->subdescription }}
                                    </span>
                                </td>

                                <td>{{ $transaction->notes }}</td>

                                <td class="date-cell" style="text-align: right">
                                    {{ $transaction->created_at->format("H:i:s") }}
                                </td>

                                <td class="date-cell">
                                    {{ $transaction->created_at->format("Y-m-d") }}
                                </td>

                                <td class="actions">
                                    <button class="icon-btn primary" title="View">
                                        <i class="fa-solid fa-eye"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colSpan="9999" class="date-cell" style="text-align: center">No data.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>

@endsection