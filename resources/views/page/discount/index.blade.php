@extends("layout.admin.main")

@php
    use Carbon\Carbon;
@endphp

@section("content")
    <section>
        <div class="page-header">
            <div>
                <h1>Discounts</h1>
                <p>Manage your active discounts.</p>
            </div>
            
            <button class="btn-primary open-modal" data-modal="addModal">
                <i class="fa-solid fa-plus"></i>
                New Discount
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
                                <th style="width: 0;">NAMA DISKON</th>
                                <th style="width: 0;">VALUE</th>
                                <th style="width: 0;">EXPIRY DATE</th>
                                <th style="">DESCRIPTION</th>
                                <th style="width: 0;">UPDATED AT</th>
                                <th style="width: 0;">CREATED AT</th>
                                <th style="width: 0; text-align: right">ACTIONS</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($discounts->filter(fn($discount) => is_null($discount->expiry_date) || $discount->expiry_date > Carbon::now()) as $discount)
                                <tr>
                                    <td>
                                        <div class="order-id"><span class="dot"></span>#{{ $discount->normalizedId() }}</div>
                                    </td>
                                    <td>{{ $discount->name }}</td>
                                    @if ($discount->type=="amount")
                                        <td>{{ App\Support\Currency::format("IDR", $discount->value) }}</td>
                                    @else
                                        <td>{{ $discount->value }}%</td>
                                    @endif
                                    <td>{{ $discount->expiry_date ?? "-" }}</td>
                                    <td>{{ $discount->description ?? "-" }}</td>
                                    <td class="date-cell" style="text-align: right">{{ $discount->updated_at }}</td>
                                    <td class="date-cell" style="text-align: right">{{ $discount->created_at }}</td>
                                    <td class="actions">
                                        <button class="icon-btn edit open-modal" title="Edit" data-modal="editModal-{{ $discount->id }}">
                                            <i class="fa-solid fa-pen"></i>
                                        </button>

                                        <button class="icon-btn delete open-modal" title="Delete" data-modal="deleteModal-{{ $discount->id }}">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colSpan="9999" class="date-cell" style="text-align: center">No data.</td>
                                </tr>
                            @endforelse

                            @if ($discounts->where("expiry_date", "<", Carbon::now())->count() > 0)
                                <tr class="date-group">
                                    <td colspan="12">
                                        <div class="date-label">
                                            <i class="fa-solid fa-calendar"></i>
                                            Expired
                                            <span>({{ $discounts->where("expiry_date", "<", Carbon::now())->count() }} Discounts)</span>
                                        </div>
                                    </td>
                                </tr>

                                @foreach($discounts->where("expiry_date", "<", Carbon::now()) as $discount)
                                    <tr>
                                        <td> 
                                            <div class="order-id date-cell"><span class="dot"></span>#{{ $discount->normalizedId() }}</div>
                                        </td>
                                        <td class="date-cell">{{ $discount->name }}</td>
                                        @if ($discount->type=="amount")
                                            <td class="date-cell">{{ App\Support\Currency::format("IDR", $discount->value) }}</td>
                                        @else
                                            <td class="date-cell">{{ $discount->value }}%</td>
                                        @endif
                                        <td class="date-cell">{{ $discount->expiry_date ?? "-" }}</td>
                                        <td class="date-cell">{{ $discount->description ?? "-" }}</td>
                                        <td class="date-cell" style="text-align: right">{{ $discount->updated_at }}</td>
                                        <td class="date-cell" style="text-align: right">{{ $discount->created_at }}</td>
                                        <td class="actions">
                                            <button class="icon-btn edit open-modal" title="Edit" data-modal="editModal-{{ $discount->id }}">
                                                <i class="fa-solid fa-pen"></i>
                                            </button>

                                            <button class="icon-btn delete open-modal" title="Delete" data-modal="deleteModal-{{ $discount->id }}">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
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
                            <h2>New Discount</h2>
                            <p>Add a new discount.</p>
                        </div>
                    </div>

                    <button class="close-modal" type="button">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>

                <form method="POST" action="{{ route('discount.post') }}">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Discount Name/Code</label>
                            <input type="text" placeholder="Example: DISCOUNT123" name="name" required>
                        </div>

                        <div style="display: flex; gap: 20px">
                            <div class="form-group" style="width: 100%">
                                <label>Value</label>
                                <input type="text" inputmode="numeric" class="currency-input" placeholder="0" name="value" required>
                            </div>

                            <div class="form-group" style="width: 100%">
                                <label>Type</label>
                                <select name="type" required>
                                    <option value="" selected disabled>-- Select Type --</option>
                                    <option value="percentage">Percentage</option>
                                    <option value="amount">Amount</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label>Expiry Date</label>
                            <input type="datetime-local" placeholder="0" name="expiry_date">
                        </div>

                        <div class="form-group">
                            <label>Description</label>
                            <textarea type="text" name="description"></textarea>
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

        @foreach ($discounts as $discount)
            <div id="editModal-{{ $discount->id }}" class="modal">
                <div class="modal-content">

                    <div class="modal-header">
                        <div class="modal-title">
                            <div class="modal-icon">
                                <i class="fa-solid fa-box"></i>
                            </div>
                            <div>
                                <h2>Edit Discount</h2>
                                <p>Edit #{{ $discount->normalizedId() }}.</p>
                            </div>
                        </div>

                        <button class="close-modal" type="button">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>

                    <form method="POST" action="{{ route('discount.edit', ['id' => $discount->id]) }}">
                        @csrf
                        @method("PUT")
                        <div class="modal-body">
                            <div class="form-group">
                                <label>Discount Name/Code</label>
                                <input type="text" placeholder="Example: DISCOUNT123" name="name" value="{{ $discount->name }}" required>
                            </div>

                            <div style="display: flex; gap: 20px">
                                <div class="form-group" style="width: 100%">
                                    <label>Value</label>
                                    <input type="text" inputmode="numeric" class="currency-input" placeholder="0" name="value" value="{{ $discount->value }}" required>
                                </div>

                                <div class="form-group" style="width: 100%">
                                    <label>Type</label>
                                    <select name="type" required>
                                        <option value="" selected disabled>-- Select Type --</option>
                                        <option value="percentage" @selected($discount->type == "percentage")>Percentage</option>
                                        <option value="amount" @selected($discount->type == "amount")>Amount</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label>Expiry Date</label>
                                <input type="datetime-local" placeholder="0" name="expiry_date" value="{{ $discount->expiry_date }}">
                            </div>

                            <div class="form-group">
                                <label>Description</label>
                                <textarea type="text" name="description">{{ $discount->description }}</textarea>
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

            <div id="deleteModal-{{ $discount->id }}" class="modal">
                <div class="modal-content">

                    <div class="modal-header">
                        <div class="modal-title">
                            <div class="modal-icon">
                                <i class="fa-solid fa-box"></i>
                            </div>
                            <div>
                                <h2>Delete Ingredient/Stock</h2>
                                <p>Delete #{{ $discount->normalizedId() }}.</p>
                            </div>
                        </div>

                        <button class="close-modal" type="button">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>

                    <form method="POST" action="{{ route('stock.delete', ['id' => $discount->id]) }}">
                        @csrf
                        @method("DELETE")

                        <div style="padding: 16px">
                            <p style="margin-bottom: 12px">Are you sure you wanna delete this Stock/Ingredients?</p>
    
                            <ul class="info-list">
                                <li>
                                    <span class="label">Name</span>
                                    <span class="value">Sugar</span>
                                </li>
    
                                <li>
                                    <span class="label">Current Stock</span>
                                    <span class="value">32 Gr</span>
                                </li>
    
                                <li>
                                    <span class="label">Unit</span>
                                    <span class="value">Gram</span>
                                </li>
    
                                <li>
                                    <span class="label">Last Updated</span>
                                    <span class="value">26 Sep 2026 · 09:35</span>
                                </li>
                            </ul>
                        </div>

                        <div class="modal-footer">
                            <button class="btn-secondary modal-close" type="button">Cancel</button>
                            <button class="btn-primary" type="submit">
                                <i class="fa-solid fa-floppy-disk"></i>
                                Yes, Delete it
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @endforeach
    </section>
@endsection

@section("script")
    <script>
        document.querySelectorAll('.currency-input').forEach(input => {
        input.addEventListener('input', function () {
            let raw = this.value.replace(/\D/g, '');

            this.value = raw
                ? new Intl.NumberFormat('id-ID').format(Number(raw))
                : '';

            // Cari hidden input yang namanya *_raw
            const rawInput = document.getElementById(`${this.id}_raw`);

            if (rawInput) {
                rawInput.value = raw;
            }
        });
    });
    </script>
@endsection