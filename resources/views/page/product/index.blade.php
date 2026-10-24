@extends("layout.admin.main")

@section("content")
    <section>
        <div class="page-header">
            <div>
                <h1>Products</h1>
                <p>Manage all products in one place.</p>
            </div>
            
            <a class="btn-primary" href="{{ route('product.create') }}">
                <i class="fa-solid fa-plus"></i>
                New Product
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
                                <th style="width: 0;">PRODUCT ID</th>
                                <th style="width: 0;">NAME</th>
                                <th style="width: 0;">CATEGORY</th>
                                <th style="width: 0;">SUBCATEGORY</th>
                                <th style="width: 0;">HARGA JUAL</th>
                                <th>HARGA MODAL</th>
                                <th style="width: 0;">UPDATED AT</th>
                                <th style="width: 0;">DELETED AT</th>
                                <th style="width: 0; text-align: right">ACTION</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($products as $product)
                                <tr>
                                    <td>
                                        <div class="order-id"><span class="dot"></span>#{{ $product->normalizedId() }}</div>
                                    </td>
                                    <td>{{ $product->name }}</td>
                                    <td>{{ $product->category->name ?? "-" }}</td>
                                    <td>{{ $product->subcategory->name ?? "-" }}</td>
                                    <td>{{ App\Support\Currency::format("IDR", $product->harga_jual) }}</td>
                                    <td>{{ App\Support\Currency::format("IDR", $product->harga_modal) }}</td>
                                    <td class="date-cell">{{ $product->updated_at }}</td>
                                    <td class="date-cell">{{ $product->created_at }}</td>
                                    <td class="actions">
                                        <a class="icon-btn edit" href="{{ route('product.edit.form', ['id' => $product->id]) }}" title="Edit">
                                            <i class="fa-solid fa-pen"></i>
                                        </a>

                                        <button class="icon-btn delete open-modal" data-modal="deleteModal-{{ $product->id }}" title="Delete">
                                            <i class="fa-solid fa-trash"></i>
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

        @foreach ($products as $product)
            <div id="deleteModal-{{ $product->id }}" class="modal">
                <div class="modal-content">

                    <div class="modal-header">
                        <div class="modal-title">
                            <div class="modal-icon">
                                <i class="fa-solid fa-box"></i>
                            </div>
                            <div>
                                <h2>Delete Product</h2>
                                <p>Delete #{{ $product->normalizedId() }}.</p>
                            </div>
                        </div>

                        <button class="close-modal" type="button">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>

                    <form method="POST" action="{{ route('product.delete', ['id' => $product->id]) }}">
                        @csrf
                        @method("DELETE")

                        <div style="padding: 16px">
                            <p style="margin-bottom: 12px">Are you sure you wanna delete this Product?</p>
    
                            <ul class="info-list">
                                <li>
                                    <span class="label">Name</span>
                                    <span class="value">{{ $product->name }}</span>
                                </li>
    
                                <li>
                                    <span class="label">Category</span>
                                    <span class="value">{{ $product->category->name }}</span>
                                </li>
    
                                <li>
                                    <span class="label">Subcategory</span>
                                    <span class="value">{{ $product->subcategory->name ?? "-" }}</span>
                                </li>
    
                                <li>
                                    <span class="label">Last Updated</span>
                                    <span class="value">{{ $product->updated_at }}</span>
                                </li>

                                <li>
                                    <span class="label">Created At</span>
                                    <span class="value">{{ $product->created_at }}</span>
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