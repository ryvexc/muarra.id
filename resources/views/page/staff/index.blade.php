@extends("layout.admin.main")

@section("content")
    <section>
        <div class="page-header">
            <div>
                <h1>Staff</h1>
                <p>Manage all staff in one place.</p>
            </div>
            
            @if (currentUser()->role->id == 2)
                <button class="btn-primary open-modal" data-modal="addCategoryModal">
                    <i class="fa-solid fa-plus"></i>
                    New Staff
                </button>
            @endif
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
                                <th style="width: 0;">STAFF ID</th>
                                <th style="width: 0;">NAME</th>
                                <th style="width: 0;">EMAIL</th>
                                <th style="width: 0;">PHONE NUMBER</th>
                                <th>Role</th>
                                <th style="width: 0;">UPDATED AT</th>
                                <th style="width: 0">CREATED_AT</th>
                                <th style="text-align: right; width: 0">ACTIONS</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($staffs as $staff)
                                <tr>
                                    <td>
                                        <div class="order-id"><span class="dot"></span>#{{ $staff->normalizedId() }}</div>
                                    </td>
                                    <td>{{ $staff->name }}</td>
                                    <td>{{ $staff->email }}</td>
                                    <td>{{ $staff->phone_number }}</td>
                                    <td>{{ $staff->role->name }}</td>
                                    <td class="date-cell" style="text-align: right">{{ $staff->updated_at }}</td>
                                    <td class="date-cell" style="text-align: right">{{ $staff->created_at }}</td>
                                    <td class="actions">
                                        <button class="icon-btn edit open-modal" title="Edit" data-modal="editModal-{{ $staff->id }}">
                                            <i class="fa-solid fa-pen"></i>
                                        </button>

                                        <button class="icon-btn delete open-modal" title="Delete" data-modal="deleteModal-{{ $staff->id }}">
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
                            <h2>New Staff</h2>
                            <p>Add a new staff.</p>
                        </div>
                    </div>

                    <button class="close-modal" type="button">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>

                <form method="POST" action="{{ route('staff.post') }}">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Staff Role</label>
                            <select name="role_id" required>
                                <option value="" selected disabled>-- Select Role --</option>
                                
                                @foreach ($roles as $role)
                                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" placeholder="Name" name="name" required>
                        </div>

                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" placeholder="Name" name="email" required>
                        </div>

                        <div class="form-group">
                            <label>No. HP</label>
                            <input type="text" placeholder="Name" name="phone_number">
                        </div>

                        <div class="form-group">
                            <label>Password</label>
                            <input type="password" placeholder="Name" name="password" required>
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

        @foreach ($staffs as $staff)
            <div id="editModal-{{ $staff->id }}" class="modal">
                <div class="modal-content">

                    <div class="modal-header">
                        <div class="modal-title">
                            <div class="modal-icon">
                                <i class="fa-solid fa-box"></i>
                            </div>
                            <div>
                                <h2>Edit Staff</h2>
                                <p>Edit #{{ $staff->normalizedId() }}.</p>
                            </div>
                        </div>

                        <button class="close-modal" type="button">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>

                    <form method="POST" action="{{ route('staff.edit', ['id' => $staff->id]) }}">
                        @csrf
                        @method("PUT")
                        <div class="modal-body">
                            <div class="form-group">
                                <label>Staff Role</label>
                                <select name="role_id" required>
                                    <option value="" selected disabled>-- Select Role --</option>
                                    
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->id }}" @selected($role->id == $staff->role->id)>{{ $role->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Name</label>
                                <input type="text" placeholder="Name" name="name" required value="{{ $staff->name }}">
                            </div>

                            <div class="form-group">
                                <label>Email</label>
                                <input type="email" placeholder="Name" name="email" required value="{{ $staff->email }}">
                            </div>

                            <div class="form-group">
                                <label>No. HP</label>
                                <input type="text" placeholder="000000000" name="phone_number" value="{{ $staff->phone_number }}">
                            </div>

                            <div class="form-group">
                                <label>Password</label>
                                <input type="password" placeholder="Password" name="password">
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

            <div id="deleteModal-{{ $staff->id }}" class="modal">
                <div class="modal-content">

                    <div class="modal-header">
                        <div class="modal-title">
                            <div class="modal-icon">
                                <i class="fa-solid fa-box"></i>
                            </div>
                            <div>
                                <h2>Delete Tenant</h2>
                                <p>Delete #{{ $staff->normalizedId() }}.</p>
                            </div>
                        </div>

                        <button class="close-modal" type="button">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>

                    <form method="POST" action="{{ route('staff.delete', ['id' => $staff->id]) }}">
                        @csrf
                        @method("DELETE")

                        <div style="padding: 16px">
                            <p style="margin-bottom: 12px">Are you sure you wanna delete this Tenant?</p>
    
                            <ul class="info-list">
                                {{-- <li>
                                    <span class="label">Name</span>
                                    <span class="value">{{ $staff->name }}</span>
                                </li>
    
                                <li>
                                    <span class="label">Owner</span>
                                    <span class="value">{{ $staff->user->name }}</span>
                                </li>
                                <li>
                                    <span class="label">Owner Email</span>
                                    <span class="value">{{ $staff->user->email }}</span>
                                </li> --}}
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