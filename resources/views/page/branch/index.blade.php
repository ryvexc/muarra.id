@extends("layout.admin.main")

@section("content")
    <section>
        <div class="page-header">
            <div>
                <h1>Branches</h1>
                <p>Manage all branches in one place.</p>
            </div>
            
            <button class="btn-primary open-modal" data-modal="addCategoryModal">
                <i class="fa-solid fa-plus"></i>
                New Branch
            </button>
        </div>
        <div>
            <div style="display: flex; justify-content: space-between; width: 100%; margin-bottom: 20px;">
                <div style="display: inline-flex; gap:16px">
                    <span></span>
                </div>

                <div style="display: inline-flex">
                    <div class="form-group form-group-compact" style="width: 100%">
                        <label style="text-align: right">Export</label>
                        <button class="btn-success">
                            <i class="fa-solid fa-file-excel"></i>
                        </button>
                    </div>
                </div>
            </div>

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
                                <th style="width: 0;">STAFF ID</th>
                                <th style="width: 0;">NAME</th>
                                <th style="width: 0;">MANAGER</th>
                                <th>ADDRESS</th>
                                <th style="width: 0;">UPDATED AT</th>
                                <th style="width: 0">CREATED_AT</th>
                                <th style="text-align: right; width: 0">ACTIONS</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($branches as $branch)
                                <tr>
                                    <td>
                                        <div class="order-id"><span class="dot"></span>#{{ $branch->normalizedId() }}</div>
                                    </td>
                                    <td>{{ $branch->name }}</td>
                                    <td>
                                        @if($branch->manager)
                                            <div>
                                                <span>{{ $branch->manager->name }}</span>
                                                <p>{{ $branch->manager->email }}</p>
                                            </div>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>{{ $branch->address }}</td>
                                    <td class="date-cell" style="text-align: right">{{ $branch->updated_at }}</td>
                                    <td class="date-cell" style="text-align: right">{{ $branch->created_at }}</td>
                                    <td class="actions">
                                        <button class="icon-btn edit open-modal" title="Edit" data-modal="editModal-{{ $branch->id }}">
                                            <i class="fa-solid fa-pen"></i>
                                        </button>

                                        <button class="icon-btn delete open-modal" title="Delete" data-modal="deleteModal-{{ $branch->id }}">
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
                            <h2>New Branch</h2>
                            <p>Add a new Branch.</p>
                        </div>
                    </div>

                    <button class="close-modal" type="button">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>

                <form method="POST" action="{{ route('branch.post') }}">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" placeholder="Name" name="name" required>
                        </div>

                        <div class="form-group">
                            <label>Address</label>
                            <input type="text" placeholder="Address" name="address" required>
                        </div>

                        <div class="form-group">
                            <label>Manager</label>
                            <select name="manager_id">
                                <option value="" selected disabled>-- Select Manager --</option>
                                
                                @foreach ($managers as $manager)
                                    <option value="{{ $manager->id }}">{{ $manager->name }} - {{ $manager->email }}</option>
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

        @foreach ($branches as $branch)
            <div id="editModal-{{ $branch->id }}" class="modal">
                <div class="modal-content">

                    <div class="modal-header">
                        <div class="modal-title">
                            <div class="modal-icon">
                                <i class="fa-solid fa-box"></i>
                            </div>
                            <div>
                                <h2>Edit Branch</h2>
                                <p>Edit #{{ $branch->normalizedId() }}.</p>
                            </div>
                        </div>

                        <button class="close-modal" type="button">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>

                    <form method="POST" action="{{ route('branch.edit', ['id' => $branch->id]) }}">
                        @csrf
                        @method("PUT")
                        <div class="modal-body">
                            <div class="form-group">
                                <label>Branch Name</label>
                                <input type="text" placeholder="Example: Comp." name="name" required value="{{ $branch->name }}">
                            </div>

                            <div class="form-group">
                                <label>Branch Address</label>
                                <input type="text" placeholder="Example: Comp." name="address" required value="{{ $branch->address }}">
                            </div>

                            <div class="form-group">
                                <label>Manager</label>
                                <select name="manager_id" required>
                                    <option value="" selected disabled>-- Select User --</option>
                                    
                                    @foreach ($managers as $manager)
                                        <option value="{{ $manager->id }}" @selected($manager->id == ($branch->manager->id ?? 0))>{{ $manager->name }} - {{ $manager->email }}</option>
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

            <div id="deleteModal-{{ $branch->id }}" class="modal">
                <div class="modal-content">

                    <div class="modal-header">
                        <div class="modal-title">
                            <div class="modal-icon">
                                <i class="fa-solid fa-box"></i>
                            </div>
                            <div>
                                <h2>Delete Tenant</h2>
                                <p>Delete #{{ $branch->normalizedId() }}.</p>
                            </div>
                        </div>

                        <button class="close-modal" type="button">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>

                    <form method="POST" action="{{ route('tenant.delete', ['id' => $branch->id]) }}">
                        @csrf
                        @method("DELETE")

                        <div style="padding: 16px">
                            <p style="margin-bottom: 12px">Are you sure you wanna delete this Tenant?</p>
    
                            <ul class="info-list">
                                <li>
                                    <span class="label">Name</span>
                                    <span class="value">{{ $branch->name }}</span>
                                </li>
    
                                <li>
                                    <span class="label">Manager</span>
                                    <span class="value">{{ $branch->manager->name ?? "-" }}</span>
                                </li>
                                <li>
                                    <span class="label">Owner Email</span>
                                    <span class="value">{{ $branch->manager->email ?? "-" }}</span>
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