@extends("layout.admin.main")

@section("content")
    <section>
        <div class="page-header">
            <div>
                <h1>Tenants</h1>
                <p>Manage all tenants in one place.</p>
            </div>
            
            <button class="btn-primary open-modal" data-modal="addCategoryModal">
                <i class="fa-solid fa-plus"></i>
                New Tenant
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
                                <th style="width: 0;">CATEGORY ID</th>
                                <th style="width: 0;">NAME</th>
                                <th style="width: 0;">OWNER</th>
                                <th>BRANCHES COUNT</th>
                                <th style="width: 0;">UPDATED AT</th>
                                <th style="width: 0">CREATED_AT</th>
                                <th style="text-align: right; width: 0">ACTIONS</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($tenants as $tenant)
                                <tr>
                                    <td>
                                        <div class="order-id"><span class="dot"></span>#{{ $tenant->normalizedId() }}</div>
                                    </td>
                                    <td>{{ $tenant->name }}</td>
                                    <td>
                                        <div>
                                            <span>{{ $tenant->user->name }}</span>
                                            <p>{{ $tenant->user->email }}</p>
                                        </div>
                                    </td>
                                    <td>{{ $tenant->branch->count() }}</td>
                                    <td class="date-cell" style="text-align: right">{{ $tenant->updated_at }}</td>
                                    <td class="date-cell" style="text-align: right">{{ $tenant->created_at }}</td>
                                    <td class="actions">
                                        <button class="icon-btn edit open-modal" title="Edit" data-modal="editModal-{{ $tenant->id }}">
                                            <i class="fa-solid fa-pen"></i>
                                        </button>

                                        <button class="icon-btn delete open-modal" title="Delete" data-modal="deleteModal-{{ $tenant->id }}">
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

        <div id="addCategoryModal" class="modal">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="modal-title">
                        <div class="modal-icon">
                            <i class="fa-solid fa-box"></i>
                        </div>
                        <div>
                            <h2>New Tenant</h2>
                            <p>Add a new tenant.</p>
                        </div>
                    </div>

                    <button class="close-modal" type="button">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>

                <form method="POST" action="{{ route('tenant.post') }}">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Tenant Name</label>
                            <input type="text" placeholder="Example: Comp." name="name" required>
                        </div>

                        <div class="form-group">
                            <label>Owner</label>
                            <select name="user_id" required>
                                <option value="" selected disabled>-- Select User --</option>
                                
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }} - {{ $user->email }}</option>
                                @endforeach
                            </select>
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

        @foreach ($tenants as $tenant)
            <div id="editModal-{{ $tenant->id }}" class="modal">
                <div class="modal-content">

                    <div class="modal-header">
                        <div class="modal-title">
                            <div class="modal-icon">
                                <i class="fa-solid fa-box"></i>
                            </div>
                            <div>
                                <h2>Edit Discount</h2>
                                <p>Edit #{{ $tenant->normalizedId() }}.</p>
                            </div>
                        </div>

                        <button class="close-modal" type="button">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>

                    <form method="POST" action="{{ route('tenant.edit', ['id' => $tenant->id]) }}">
                        @csrf
                        @method("PUT")
                        <div class="modal-body">
                            <div class="form-group">
                                <label>Tenant Name</label>
                                <input type="text" placeholder="Example: Comp." name="name" required value="{{ $tenant->name }}">
                            </div>

                            <div class="form-group">
                                <label>Owner</label>
                                <select name="user_id" required>
                                    <option value="" selected disabled>-- Select User --</option>
                                    
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}" @selected($user->id == $tenant->user_id)>{{ $user->name }} - {{ $user->email }}</option>
                                    @endforeach
                                </select>
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

            <div id="deleteModal-{{ $tenant->id }}" class="modal">
                <div class="modal-content">

                    <div class="modal-header">
                        <div class="modal-title">
                            <div class="modal-icon">
                                <i class="fa-solid fa-box"></i>
                            </div>
                            <div>
                                <h2>Delete Tenant</h2>
                                <p>Delete #{{ $tenant->normalizedId() }}.</p>
                            </div>
                        </div>

                        <button class="close-modal" type="button">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>

                    <form method="POST" action="{{ route('tenant.delete', ['id' => $tenant->id]) }}">
                        @csrf
                        @method("DELETE")

                        <div style="padding: 16px">
                            <p style="margin-bottom: 12px">Are you sure you wanna delete this Tenant?</p>
    
                            <ul class="info-list">
                                <li>
                                    <span class="label">Name</span>
                                    <span class="value">{{ $tenant->name }}</span>
                                </li>
    
                                <li>
                                    <span class="label">Owner</span>
                                    <span class="value">{{ $tenant->user->name }}</span>
                                </li>
                                <li>
                                    <span class="label">Owner Email</span>
                                    <span class="value">{{ $tenant->user->email }}</span>
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