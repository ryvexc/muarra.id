@extends("layout.admin.main")

@section("content")
    <section>

        <div class="page-header">
            <div>
                <h1>Stock Report</h1>
                <p>#{{ $stockReportItems->first()->stockReport->normalizedId() }}</p>
            </div>
        </div>

        <div>
            <div class="card">

                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th style="width: 0;">KODE BARANG</th>
                                <th style="width: 0;">NAMA BARANG</th>
                                <th style="width: 0;">SATUAN</th>
                                <th style="width: 0;">HARGA SATUAN</th>

                                <th style="width: 0;">
                                    STOK AWAL
                                </th>

                                <th style="width: 0;">
                                    <div class="th-tooltip">
                                        PENJUALAN

                                        <span class="tooltip-icon">
                                            <i class="fa-solid fa-circle-question"></i>
                                        </span>

                                        <span class="tooltip-box">
                                            Nilai ini otomatis terkalkulasi berdasarkan penjualan/order.
                                        </span>
                                    </div>
                                </th>

                                <th style="width: 0;">
                                    BARANG MASUK
                                </th>

                                <th style="width: 0;">
                                    <div class="th-tooltip">
                                        PERSEDIAAN AKHIR

                                        <span class="tooltip-icon">
                                            <i class="fa-solid fa-circle-question"></i>
                                        </span>

                                        <span class="tooltip-box">
                                            Nilai ini dihitung berdasarkan stok awal, penjualan, dan barang masuk.
                                        </span>
                                    </div>
                                </th>

                                <th style="width: 0; color: var(--text-primary)">
                                    STOK FISIK
                                </th>

                                <th style="width: 0;">
                                    <div class="th-tooltip">
                                        SELISIH

                                        <span class="tooltip-icon">
                                            <i class="fa-solid fa-circle-question"></i>
                                        </span>

                                        <span class="tooltip-box">
                                            Selisih antara stok fisik dan persediaan akhir.
                                        </span>
                                    </div>
                                </th>

                                <th style="width: 0; color: var(--text-primary)">
                                    SELISIH HARGA
                                </th>

                                <th>
                                    KETERANGAN
                                </th>
                            </tr>
                        </thead>

                        <tbody>

                            @forelse ($stockReportItems as $stockReportItem)

                                <tr class="
                                    @if($stockReportItem->difference > 0)
                                        row-warning
                                    @elseif($stockReportItem->difference < 0)
                                        row-danger
                                    @endif
                                ">

                                    {{-- KODE BARANG --}}
                                    <td>
                                        <div class="order-id">

                                            <span class="dot
                                                @if($stockReportItem->difference > 0)
                                                    dot-warning
                                                @elseif($stockReportItem->difference < 0)
                                                    dot-danger
                                                @else
                                                    dot-success
                                                @endif
                                            "></span>

                                            #{{ $stockReportItem->ingredient->normalizedId() }}

                                        </div>
                                    </td>

                                    {{-- NAMA --}}
                                    <td>
                                        {{ $stockReportItem->ingredient->name }}
                                    </td>

                                    {{-- SATUAN --}}
                                    <td style="text-align: center">
                                        {{ $stockReportItem->ingredient->unit_of_measurement->symbol }}
                                    </td>

                                    {{-- HARGA SATUAN --}}
                                    <td style="text-align: center">
                                        {{ App\Support\Currency::format("IDR", $stockReportItem->unit_price) }}
                                    </td>

                                    {{-- STOK AWAL --}}
                                    <td style="text-align: center">
                                        {{ $stockReportItem->opening_stock }}
                                    </td>

                                    {{-- PENJUALAN --}}
                                    <td style="text-align: center">
                                        {{ $stockReportItem->outgoing_stock }}
                                    </td>

                                    {{-- BARANG MASUK --}}
                                    <td style="text-align: center">
                                        {{ $stockReportItem->incoming_stock }}
                                    </td>

                                    {{-- PERSEDIAAN AKHIR --}}
                                    <td style="text-align: center">
                                        {{ $stockReportItem->expected_stock }}
                                    </td>

                                    {{-- STOK FISIK --}}
                                    <td style="text-align: center">
                                        {{ $stockReportItem->actual_stock }}
                                    </td>

                                    {{-- SELISIH --}}
                                    @if($stockReportItem->difference > 0)

                                        <td style="text-align: center; color: #fbbf24">
                                            +{{ $stockReportItem->difference }}
                                        </td>

                                    @elseif($stockReportItem->difference < 0)

                                        <td style="text-align: center; color: #f87171">
                                            {{ $stockReportItem->difference }}
                                        </td>

                                    @else

                                        <td style="text-align: center; color: #4ade80">
                                            0
                                        </td>

                                    @endif

                                    {{-- SELISIH HARGA --}}
                                    @if($stockReportItem->difference_value > 0)

                                        <td style="text-align: center; color: #fbbf24">
                                            +{{ App\Support\Currency::format("IDR", $stockReportItem->difference_value) }}
                                        </td>

                                    @elseif($stockReportItem->difference_value < 0)

                                        <td style="text-align: center; color: #f87171">
                                            {{ App\Support\Currency::format("IDR", $stockReportItem->difference_value) }}
                                        </td>

                                    @else

                                        <td style="text-align: center; color: #4ade80">
                                            {{ App\Support\Currency::format("IDR", 0) }}
                                        </td>

                                    @endif

                                    {{-- KETERANGAN --}}
                                    @if($stockReportItem->notes)

                                        <td>
                                            {{ $stockReportItem->notes }}
                                        </td>

                                    @else

                                        <td style="color: var(--text-secondary); text-align: center">
                                            -
                                        </td>

                                    @endif

                                </tr>

                            @empty

                            @endforelse

                        </tbody>
                    </table>
                </div>

            </div>

            {{-- Report Summary --}}
            <div class="stock-summary">

                <div class="stock-summary-header">
                    <div>
                        <h3>Report Summary</h3>
                        <p>Quick overview of this stock report.</p>
                    </div>

                    @php
                        $hasShortage = $stockReportItems->contains(
                            fn ($item) => $item->difference < 0
                        );

                        $hasSurplus = $stockReportItems->contains(
                            fn ($item) => $item->difference > 0
                        );
                    @endphp

                    @if($hasShortage)
                        <span class="badge danger">
                            Shortage
                        </span>
                    @elseif($hasSurplus)
                        <span class="badge warning">
                            Surplus
                        </span>
                    @else
                        <span class="badge success">
                            Matched
                        </span>
                    @endif

                </div>

                <div class="summary-list">

                    @forelse ($stockReportItems as $stockReportItem)

                        @if ($stockReportItem->difference > 0)

                            <div class="summary-item warning">
                                <span class="summary-dot"></span>

                                <span>
                                    <strong>
                                        #{{ $stockReportItem->ingredient->normalizedId() }}
                                    </strong>

                                    {{ $stockReportItem->ingredient->name }} is

                                    <strong class="summary-value warning">
                                        +{{ $stockReportItem->difference }}
                                    </strong>

                                    more
                                </span>
                            </div>

                        @elseif ($stockReportItem->difference < 0)

                            <div class="summary-item danger">
                                <span class="summary-dot"></span>

                                <span>
                                    <strong>
                                        #{{ $stockReportItem->ingredient->normalizedId() }}
                                    </strong>

                                    {{ $stockReportItem->ingredient->name }} is

                                    <strong class="summary-value danger">
                                        {{ $stockReportItem->difference }}
                                    </strong>

                                    less
                                </span>
                            </div>

                        @endif

                    @empty

                        <div class="summary-item success">
                            <span class="summary-dot"></span>

                            <span>
                                All stock matches the expected inventory.
                            </span>
                        </div>

                    @endforelse

                </div>

            </div>

        </div>

    </section>
@endsection

@section("script")
@endsection