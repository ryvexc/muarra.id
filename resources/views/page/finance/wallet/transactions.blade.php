@extends("layout.admin.main")

@section("content")
    <section>
        <div class="page-header">
            <div>
                <h1>Transactions</h1>
                <p>See all of your transactions.</p>
            </div>
            <div>
                <button class="btn-success" onclick="window.location.href = '{{ route('wallet.history.get-statement') }}'">
                    <i class="fa-solid fa-file-excel"></i>
                    Get Statement
                </button>
            </div>
        </div>
        <div>
            <div class="card">
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
                                        <span style="color:var(--text-secondary)">
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