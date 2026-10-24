@extends("layout.admin.main")

@section("content")
    <section>
        <form method="POST" action="{{ route('stock-report.post') }}">
            @csrf
            <div class="page-header">
                <div>
                    <h1>Create a Stock Report</h1>
                    <p>Manage your stocks.</p>
                </div>
                
                <button class="btn-primary" type="submit">
                    {{-- <i class="fa-solid fa-plus"></i> --}}
                    Save
                </button>
            </div>
            <div>
                <div class="card">
                    {{-- <div class="toolbar">
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
                    </div> --}}
                    
                    <div class="table-wrap">
                        <table>
                            <thead>
                                <tr>
                                    <th style="width: 0;">KODE BARANG</th>
                                    <th style="width: 0;">NAMA BARANG</th>
                                    <th style="width: 0;">SATUAN</th>
                                    <th style="width: 0;">HARGA SATUAN</th>
                                    <th style="width: 0;">STOK AWAL</th>
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
                                    <th style="width: 0;">BARANG MASUK</th>
                                    <th style="width: 0;">
                                        <div class="th-tooltip">
                                            PERSEDIAAN AKHIR

                                            <span class="tooltip-icon">
                                                <i class="fa-solid fa-circle-question"></i>
                                            </span>

                                            <span class="tooltip-box">
                                                Nilai ini akan dihitung oleh sistem.
                                            </span>
                                        </div>
                                    </th>
                                    <th style="width: 0; color: var(--text-primary)">STOK FISIK</th>
                                    <th style="width: 0;">
                                        <div class="th-tooltip">
                                            SELISIH

                                            <span class="tooltip-icon">
                                                <i class="fa-solid fa-circle-question"></i>
                                            </span>

                                            <span class="tooltip-box">
                                                Nilai ini akan dihitung oleh sistem.
                                            </span>
                                        </div>
                                    </th>
                                    <th style="width: 0; color: var(--text-primary)">SELISIH HARGA</th>
                                    <th>KETERANGAN</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($ingredients as $ingredient)
                                    <input type="hidden" name="items[{{ $ingredient->id }}][ingredient_id]" value="{{ $ingredient->id }}" />

                                    <tr>
                                        <td>
                                            <div class="order-id"><span class="dot"></span>#{{ $ingredient->normalizedId() }}</div>
                                        </td>
                                        <td>{{ $ingredient->name }}</td>
                                        <td style="text-align: center">{{ $ingredient->unit_of_measurement->symbol }}</td>
                                        <td style="text-align: center">{{ App\Support\Currency::format("IDR", $ingredient->harga_satuan) }}</td>
                                        <td style="text-align: center">
                                            {{ $ingredient->opening_stock }}
                                            <input
                                                type="hidden"
                                                name="items[{{ $ingredient->id }}][opening_stock]"
                                                value="{{ $ingredient->opening_stock }}"
                                            >
                                        </td>
                                        <td class="editable-cell" style="text-align: center">
                                            <input
                                                id="stockId-{{ $ingredient->id }}-penjualan"
                                                type="number"
                                                class="table-input"
                                                name="items[{{ $ingredient->id }}][outgoing_stock]"
                                                placeholder="0"
                                                min="0"
                                                value="0"
                                                hidden
                                            >

                                            <span id="stockId-{{ $ingredient->id }}-penjualanDisplay">0</span>
                                        </td>
                                        <td class="editable-cell" style="text-align: center">
                                            <input
                                                id="stockId-{{ $ingredient->id }}-barangMasuk"
                                                type="number"
                                                class="table-input"
                                                name="items[{{ $ingredient->id }}][incoming_stock]"
                                                placeholder="0"
                                                min="0"
                                                value="{{ $ingredient->supplyIn }}"
                                                hidden
                                            >
                                            
                                            <span id="stockId-{{ $ingredient->id }}-barangMasukDisplay">{{ $ingredient->supplyIn }}</span>
                                        </td>
                                        <td class="editable-cell" style="text-align: center">
                                            <input
                                                id="stockId-{{ $ingredient->id }}-persediaanAkhir"
                                                type="number"
                                                class="table-input"
                                                name="items[{{ $ingredient->id }}][expected_stock]"
                                                placeholder="0"
                                                min="0"
                                                hidden
                                            >
                                            <span id="stockId-{{ $ingredient->id }}-persediaanAkhirDisplay">0</span>
                                        </td>
                                        <td class="editable-cell">
                                            <input
                                                id="stockId-{{ $ingredient->id }}-opname"
                                                type="number"
                                                class="table-input"
                                                name="items[{{ $ingredient->id }}][actual_stock]"
                                                placeholder="0"
                                                min="0"
                                                style="text-align: center"
                                            >
                                        </td>
                                        <td class="editable-cell" style="text-align: center">
                                            <input
                                                id="stockId-{{ $ingredient->id }}-selisih"
                                                type="number"
                                                class="table-input"
                                                name="items[{{ $ingredient->id }}][difference]"
                                                placeholder="0"
                                                hidden
                                            >

                                            <span id="stockId-{{ $ingredient->id }}-selisihDisplay">0</span>
                                        </td>
                                        <td class="editable-cell" style="text-align: center">
                                            <input
                                                id="stockId-{{ $ingredient->id }}-selisihHarga"
                                                type="number"
                                                class="table-input"
                                                name="items[{{ $ingredient->id }}][difference_value]"
                                                placeholder="0"
                                                hidden
                                            >

                                            <span id="stockId-{{ $ingredient->id }}-selisihHargaDisplay">0</span>
                                        </td>
                                        <td class="editable-cell">
                                            <textarea
                                                class="table-textarea"
                                                name="items[{{ $ingredient->id }}][notes]"
                                                rows="1"
                                                placeholder="Add a note..."
                                            ></textarea>
                                        </td>
                                    </tr>
                                @empty
                                    
                                @endforelse
                                
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="stock-summary">
                    <div class="stock-summary-header">
                        <div>
                            <h3>Report Summary</h3>
                            <p>Quick overview of this stock report.</p>
                        </div>
                        <span class="badge" id="summary-status">Unfilled</span>
                    </div>

                    <div class="summary-list" id="summary-list">
                        {{-- EMPTY --}}
                    </div>
                </div>
            </div>
        </form>
    </section>
@endsection

@section("script")
    <script>
        let data = @json($ingredients);
        let hasWarnings = {};

        let summaryStatusElement = document.getElementById("summary-status");
        let summaryListElement = document.getElementById("summary-list");

        function formatCurrency(value) {
            const formatted = new Intl.NumberFormat('id-ID').format(Math.abs(value));

            return value < 0
                ? '-Rp' + formatted
                : 'Rp' + formatted;
        }

        function renderSummary() {
            summaryListElement.innerHTML = "";

            const warnings = Object.values(hasWarnings).filter(item => item.hasWarning);

            if (warnings.length === 0) {
                summaryStatusElement.className = "badge success";
                summaryStatusElement.innerText = "Safe";

                summaryListElement.innerHTML = `
                    <div class="summary-item success">
                        <span class="summary-dot"></span>
                        <span>All stock matches the expected inventory.</span>
                    </div>
                `;
                return;
            }

            summaryStatusElement.className = "badge warning";
            summaryStatusElement.innerText = `${warnings.length} Issue${warnings.length > 1 ? "s" : ""}`;

            warnings.forEach(item => {
                const type = item.quantity > 0 ? "warning" : "danger";
                const label = item.quantity > 0 ? "more" : "less";
                const qty = item.quantity > 0 ? `+${item.quantity}` : item.quantity;

                summaryListElement.innerHTML += `
                    <div class="summary-item ${type}">
                        <span class="summary-dot"></span>
                        <span>
                            <strong>#${item.code}</strong> ${item.name} is
                            <strong class="summary-value ${type}">${qty}</strong>
                            ${label}.
                        </span>
                    </div>
                `;
            })
        }

        function recalculateRow(ingredientData, inputElements) {
            let hasilPersediaanAkhir = ingredientData.opening_stock - parseInt(inputElements.penjualanInputElement.value || 0) + parseInt(inputElements.barangMasukInputElement.value || 0);
            let hasilSelisih = (parseInt(inputElements.opnameInputElement.value || 0) - hasilPersediaanAkhir) || 0
            let hasilSelisihHarga = hasilSelisih * ingredientData.harga_satuan

            inputElements.persediaanAkhirElement.value = hasilPersediaanAkhir
            inputElements.selisihElement.value = hasilSelisih

            if(hasilSelisih > 0) {
                inputElements.selisihDisplayElement.style.color = "#fbbf24"; // yellow
                inputElements.selisihHargaDisplayElement.style.color = "#ffffff"; // white
                hasWarnings[ingredientData.id.toString()].hasWarning = true;
            } else if (hasilSelisih < 0) {
                inputElements.selisihDisplayElement.style.color = "#f87171";
                inputElements.selisihHargaDisplayElement.style.color = "#f87171";
                hasWarnings[ingredientData.id.toString()].hasWarning = true;
                
            } else {
                inputElements.selisihDisplayElement.style.color = "#4ade80";
                inputElements.selisihHargaDisplayElement.style.color = "#4ade80";
                inputElements.selisihHargaDisplayElement.style.color = "#ffffff"; // white
                hasWarnings[ingredientData.id.toString()].hasWarning = false;
            }
            
            inputElements.persediaanAkhirElementDisplay.innerText = hasilPersediaanAkhir
            inputElements.selisihDisplayElement.innerText = hasilSelisih > 0 ? `+${hasilSelisih}` : hasilSelisih
            inputElements.selisihHargaDisplayElement.innerText = formatCurrency(hasilSelisihHarga)

            hasWarnings[ingredientData.id.toString()].quantity = hasilSelisih;
            renderSummary();
        }

        data.forEach((ingredient) => {
            hasWarnings[ingredient.id.toString()] = {
                code: ingredient.normalized_id,
                name: ingredient.name,
                quantity: 0,
                hasWarning: false
            };

            let inputElements = {
                penjualanInputElement: document.getElementById(`stockId-${ingredient.id}-penjualan`),
                penjualanInputElementDisplay: document.getElementById(`stockId-${ingredient.id}-penjualanDisplay`),
                barangMasukInputElement: document.getElementById(`stockId-${ingredient.id}-barangMasuk`),
                barangMasukInputElementDisplay: document.getElementById(`stockId-${ingredient.id}-barangMasukDisplay`),
                opnameInputElement: document.getElementById(`stockId-${ingredient.id}-opname`),
                persediaanAkhirElement: document.getElementById(`stockId-${ingredient.id}-persediaanAkhir`),
                persediaanAkhirElementDisplay: document.getElementById(`stockId-${ingredient.id}-persediaanAkhirDisplay`),
                selisihElement: document.getElementById(`stockId-${ingredient.id}-selisih`),
                selisihDisplayElement: document.getElementById(`stockId-${ingredient.id}-selisihDisplay`),
                selisihHargaElement: document.getElementById(`stockId-${ingredient.id}-selisihHarga`),
                selisihHargaDisplayElement: document.getElementById(`stockId-${ingredient.id}-selisihHargaDisplay`)
            }

            recalculateRow(ingredient, inputElements);

            inputElements.opnameInputElement.onkeyup = () => recalculateRow(ingredient, inputElements)
        })

    </script>
@endsection