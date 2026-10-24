@extends("layout.admin.main")

@section("content")
    <section>
        <div class="page-header">
            <div>
                <h1>Categories</h1>
                <p>Manage all categories in one place.</p>
            </div>
            
            <button class="btn-primary open-modal" data-modal="addCategoryModal">
                <i class="fa-solid fa-plus"></i>
                New Category
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
                                <th style="width: 0;">SUBCATEGORIES</th>
                                <th>JML. PRODUK</th>
                                <th style="width: 0;">UPDATED AT</th>
                                <th style="width: 0">CREATED_AT</th>
                                <th style="text-align: right; width: 0">ACTIONS</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($categories as $category)
                                <tr>
                                    <td>
                                        <div class="order-id"><span class="dot"></span>#{{ $category->normalizedId() }}</div>
                                    </td>
                                    <td>{{ $category->name }}</td>
                                    <td>
                                        @if($category->subCategories->where("deleted_at", NULL)->count() > 0)
                                            <ul style="display: flex; flex-direction: column; gap: 4px;">
                                                @foreach ($category->subCategories->where("deleted_at", NULL) as $subCategory)
                                                    <li style="list-style-type: none">- {{ $subCategory->name }}</li>
                                                @endforeach
                                            </ul>
                                        @else
                                            <p>-</p>
                                        @endif
                                    </td>
                                    <td>{{ $category->products->whereNull("deleted_at")->count() }}</td>
                                    <td class="date-cell" style="text-align: right">{{ $category->updated_at }}</td>
                                    <td class="date-cell" style="text-align: right">{{ $category->created_at }}</td>
                                    <td class="actions">
                                        <button class="icon-btn edit open-modal" title="Edit" data-modal="editCategoryModal-{{ $category->id }}">
                                            <i class="fa-solid fa-pen"></i>
                                        </button>

                                        <button class="icon-btn delete open-modal" title="Delete" data-modal="deleteCategoryModal-{{ $category->id }}">
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

        <div class="page-header" style="margin-top: 28px">
            <div>
                <h1>Subcategories</h1>
                <p>Manage all categories in one place.</p>
            </div>
            
            <button class="btn-primary open-modal" data-modal="addSubcategoryModal">
                <i class="fa-solid fa-plus"></i>
                New Subcategory
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
                                <th>JML. PRODUK</th>
                                <th style="width: 0;">UPDATED AT</th>
                                <th style="width: 0">CREATED_AT</th>
                                <th style="text-align: right; width: 0">ACTIONS</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($subCategories as $subCategory)
                                <tr>
                                    <td>
                                        <div class="order-id"><span class="dot"></span>#{{ $subCategory->normalizedId() }}</div>
                                    </td>
                                    <td>{{ $subCategory->name }}</td>
                                    <td>{{ $subCategory->products->whereNull("deleted_at")->count() }}</td>
                                    <td class="date-cell" style="text-align: right">{{ $subCategory->updated_at }}</td>
                                    <td class="date-cell" style="text-align: right">{{ $subCategory->created_at }}</td>
                                    <td class="actions">
                                        <button class="icon-btn edit open-modal" title="Edit" data-modal="editSubCategoryModal-{{ $subCategory->id }}">
                                            <i class="fa-solid fa-pen"></i>
                                        </button>

                                        <button class="icon-btn delete open-modal" title="Delete" data-modal="deleteSubCategoryModal-{{ $subCategory->id }}">
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
                            <h2>New Category</h2>
                            <p>Add a new category.</p>
                        </div>
                    </div>

                    <button class="close-modal" type="button">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>

                <form method="POST" action="{{ route('category.post') }}">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Category Name</label>
                            <input type="text" placeholder="Example: Sugar" name="name">
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

        @foreach ($categories as $category)
            <div id="editCategoryModal-{{ $category->id }}" class="modal">
                <div class="modal-content">
                    <div class="modal-header">
                        <div class="modal-title">
                            <div class="modal-icon">
                                <i class="fa-solid fa-box"></i>
                            </div>
                            <div>
                                <h2>Edit Category</h2>
                                <p>Edit #{{ $category->normalizedId() }}.</p>
                            </div>
                        </div>

                        <button class="close-modal" type="button">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>

                    <form method="POST" action="{{ route('category.edit', ['id' => $category->id]) }}">
                        @csrf
                        @method("PUT")
                        <div class="modal-body">
                            <div class="form-group">
                                <label>Category Name</label>
                                <input type="text" placeholder="Example: Sugar" name="name" value="{{ $category->name }}">
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

            <div id="deleteCategoryModal-{{ $category->id }}" class="modal">
                <div class="modal-content">

                    <div class="modal-header">
                        <div class="modal-title">
                            <div class="modal-icon">
                                <i class="fa-solid fa-box"></i>
                            </div>
                            <div>
                                <h2>Delete Category</h2>
                                <p>Delete #{{ $category->normalizedId() }}.</p>
                            </div>
                        </div>

                        <button class="close-modal" type="button">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>

                    <form method="POST" action="{{ route('category.delete', ['id' => $category->id]) }}">
                        @csrf
                        @method("DELETE")

                        <div style="padding: 16px">
                            <p style="margin-bottom: 12px">Are you sure you wanna delete this category?</p>
    
                            <ul class="info-list">
                                <li>
                                    <span class="label">Name</span>
                                    <span class="value">{{ $category->name }}</span>
                                </li>
    
                                <li>
                                    <span class="label">Jml. Produk</span>
                                    <span class="value">0</span>
                                </li>
    
                                <li>
                                    <span class="label">Subcategories</span>
                                    <span class="value">
                                        <ul>
                                            @foreach ($category->subCategories->where("deleted_at", NULL) as $subCategory)
                                                <li style="padding: 0">- {{ $subCategory->name }}</li>
                                            @endforeach
                                        </ul>
                                    </span>
                                </li>
    
                                <li>
                                    <span class="label">Last Updated</span>
                                    <span class="value">{{ $category->updated_at }}</span>
                                </li>

                                <li>
                                    <span class="label">Created At</span>
                                    <span class="value">{{ $category->created_at }}</span>
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

        <div id="addSubcategoryModal" class="modal">
            <div class="modal-content">

                <div class="modal-header">
                    <div class="modal-title">
                        <div class="modal-icon">
                            <i class="fa-solid fa-box"></i>
                        </div>
                        <div>
                            <h2>New Subcategory</h2>
                            <p>Add a new subcateogry.</p>
                        </div>
                    </div>

                    <button class="close-modal" type="button">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>

                <form method="POST" action="{{ route('category.sub.post') }}">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Category</label>
                            <select name="product_category_id" required>
                                <option value="" selected disabled>-- Select Category --</option>
                                
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label>Subcategory Name</label>
                            <input type="text" placeholder="Example: Sugar" name="name" required>
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

        @foreach ($subCategories as $subCategory)
            <div id="editSubCategoryModal-{{ $subCategory->id }}" class="modal">
                <div class="modal-content">
                    <div class="modal-header">
                        <div class="modal-title">
                            <div class="modal-icon">
                                <i class="fa-solid fa-box"></i>
                            </div>
                            <div>
                                <h2>Edit Subcategory</h2>
                                <p>Edit #{{ $subCategory->normalizedId() }}.</p>
                            </div>
                        </div>

                        <button class="close-modal" type="button">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>

                    <form method="POST" action="{{ route('category.sub.edit', ['id' => $subCategory->id]) }}">
                        @csrf
                        @method("PUT")
                        <div class="modal-body">
                            <div class="form-group">
                                <label>Category</label>
                                <select name="product_category_id" required>
                                    @if ($subCategory->category->deleted_at)
                                        <option value="{{ $subCategory->product_category_id }}" selected disabled>{{ $subCategory->category->name }} (DELETED)</option>
                                    @endif

                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}" @selected($subCategory->product_category_id == $category->id)>{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Subcategory Name</label>
                                <input type="text" placeholder="Example: Sugar" name="name" value="{{ $subCategory->name }}">
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

            <div id="deleteSubCategoryModal-{{ $subCategory->id }}" class="modal">
                <div class="modal-content">

                    <div class="modal-header">
                        <div class="modal-title">
                            <div class="modal-icon">
                                <i class="fa-solid fa-box"></i>
                            </div>
                            <div>
                                <h2>Delete SubCategory</h2>
                                <p>Delete #{{ $subCategory->normalizedId() }}.</p>
                            </div>
                        </div>

                        <button class="close-modal" type="button">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>

                    <form method="POST" action="{{ route('category.sub.delete', ['id' => $subCategory->id]) }}">
                        @csrf
                        @method("DELETE")

                        <div style="padding: 16px">
                            <p style="margin-bottom: 12px">Are you sure you wanna delete this category?</p>
    
                            <ul class="info-list">
                                <li>
                                    <span class="label">Name</span>
                                    <span class="value">{{ $subCategory->name }}</span>
                                </li>
    
                                <li>
                                    <span class="label">Category</span>
                                    <span class="value">{{ $subCategory->category->name }}</span>
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