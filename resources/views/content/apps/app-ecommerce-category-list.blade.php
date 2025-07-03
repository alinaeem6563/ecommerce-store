@extends('layouts.layoutMaster')

@section('title', 'Product Categories')

@section('vendor-style')
@vite(['resources/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.scss', 'resources/assets/vendor/libs/sweetalert2/sweetalert2.scss',
'resources/assets/vendor/libs/dropzone/dropzone.scss',])
@endsection

@section('page-style')
@vite('resources/assets/vendor/scss/pages/category-management.scss')

@endsection
@section('vendor-script')
    @vite(['resources/assets/js/category-management.js','resources/assets/vendor/libs/moment/moment.js', 'resources/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js', 'resources/assets/vendor/libs/sweetalert2/sweetalert2.js','resources/assets/vendor/libs/dropzone/dropzone.js'])
@endsection


@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">eCommerce /</span> Category Management
    </h4>

    <!-- Alert for success/error messages -->
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <!-- Category List Card -->
    <div class="card">
        <div class="card-header border-bottom">
            <div class="d-flex justify-content-between align-items-center row py-3 gap-3 gap-md-0">
                <div class="col-md-4 category_status">
                    <form action="{{ route('category.index') }}" method="GET" id="filterForm">
                        <select id="FilterTransaction" name="status" class="form-select text-capitalize" onchange="document.getElementById('filterForm').submit()">
                            <option value="">All Categories</option>
                            <option value="Publish" {{ request('status') == 'Publish' ? 'selected' : '' }}>Published</option>
                            <option value="Inactive" {{ request('status') == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </form>
                </div>
                <div class="col-md-8 text-md-end">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                        <i class="ti ti-plus me-1"></i> Add New Category
                    </button>
                    <button class="btn btn-secondary d-md-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#addCategoryOffcanvas">
                        <i class="ti ti-plus me-1"></i> Add New Category
                    </button>
                </div>
            </div>
        </div>
        <div class="card-datatable table-responsive">
            <table class="datatables-categories table border-top" id="categories-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Slug</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($categories as $category)
                    <tr>
                        <td>{{ $category->id }}</td>
                        <td>
                            <img src="{{ $category->image_path ? asset('storage/'.$category->image_path) : asset('assets/img/placeholder.png') }}" 
                                alt="Category" class="rounded" height="40" width="40">
                        </td>
                        <td>{{ $category->name }}</td>
                        <td>{{ $category->slug }}</td>
                        <td>
                            <span class="badge {{ $category->status == 'Publish' ? 'bg-success' : 'bg-secondary' }}">
                                {{ $category->status }}
                            </span>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <button type="button" class="btn btn-sm btn-icon view-btn" 
                                    data-bs-toggle="modal" data-bs-target="#viewCategoryModal{{ $category->id }}">
                                    <i class="ti ti-eye text-primary"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-icon edit-btn" 
                                    data-bs-toggle="modal" data-bs-target="#editCategoryModal{{ $category->id }}">
                                    <i class="ti ti-edit text-warning"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-icon delete-btn" 
                                    data-bs-toggle="modal" data-bs-target="#deleteCategoryModal{{ $category->id }}">
                                    <i class="ti ti-trash text-danger"></i>
                                </button>
                            </div>
                        </td>
                    </tr>

                    <!-- View Category Modal for each category -->
                    <div class="modal fade" id="viewCategoryModal{{ $category->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-lg modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Category Details</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-12 col-md-4 text-center mb-3 mb-md-0">
                                            <img src="{{ $category->image_path ? asset('storage/'.$category->image_path) : asset('assets/img/placeholder.png') }}" 
                                                alt="Category Image" class="img-fluid rounded" style="max-height: 200px;">
                                        </div>
                                        <div class="col-12 col-md-8">
                                            <div class="mb-3">
                                                <h6 class="fw-semibold">Name</h6>
                                                <p class="mb-1">{{ $category->name }}</p>
                                            </div>
                                            <div class="mb-3">
                                                <h6 class="fw-semibold">Slug</h6>
                                                <p class="mb-1">{{ $category->slug }}</p>
                                            </div>
                                            <div class="mb-3">
                                                <h6 class="fw-semibold">Status</h6>
                                                <span class="badge rounded-pill {{ $category->status == 'Publish' ? 'bg-success' : 'bg-secondary' }}">
                                                    {{ $category->status }}
                                                </span>
                                            </div>
                                            <div>
                                                <h6 class="fw-semibold">Description</h6>
                                                <p class="mb-0">{{ $category->description ?? 'No description available' }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--/ View Category Modal -->

                    <!-- Delete Category Confirmation Modal for each category -->
                    <div class="modal fade" id="deleteCategoryModal{{ $category->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Delete Category</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <p>Are you sure you want to delete <strong>{{ $category->name }}</strong>? This action cannot be undone.</p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Cancel</button>
                                    <form action="{{ route('category.destroy', $category->id) }}" method="POST" style="display: inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">Delete</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--/ Delete Category Confirmation Modal -->

                    <!-- Edit Category Modal for each category -->
                    <div class="modal fade" id="editCategoryModal{{ $category->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-lg modal-dialog-centered modal-edit-category">
                            <div class="modal-content p-3 p-md-5">
                                <div class="modal-header">
                                    <h5 class="modal-title">Edit Category</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form action="{{ route('category.update', $category->id) }}" method="POST" enctype="multipart/form-data" class="row g-3">
                                        @csrf
                                        @method('PUT')
                                        <div class="col-12 col-md-6">
                                            <label class="form-label" for="edit-categoryTitle-{{ $category->id }}">Category Name</label>
                                            <input type="text" id="edit-categoryTitle-{{ $category->id }}" name="categoryTitle" class="form-control @error('categoryTitle') is-invalid @enderror" placeholder="Enter category name" value="{{ old('categoryTitle', $category->name) }}" />
                                            @error('categoryTitle')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <label class="form-label" for="edit-slug-{{ $category->id }}">Slug</label>
                                            <input type="text" id="edit-slug-{{ $category->id }}" name="slug" class="form-control @error('slug') is-invalid @enderror" placeholder="category-slug" value="{{ old('slug', $category->slug) }}" />
                                            @error('slug')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label" for="edit-categoryDescription-{{ $category->id }}">Description</label>
                                            <textarea class="form-control @error('categoryDescription') is-invalid @enderror" id="edit-categoryDescription-{{ $category->id }}" name="categoryDescription" rows="3" placeholder="Category description">{{ old('categoryDescription', $category->description) }}</textarea>
                                            @error('categoryDescription')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label">Current Image</label>
                                            <div class="d-flex mb-3">
                                                <img src="{{ $category->image_path ? asset('storage/'.$category->image_path) : asset('assets/img/placeholder.png') }}" 
                                                    alt="Current Category Image" class="rounded me-2" height="64" width="64">
                                                <div class="d-flex align-items-start flex-column">
                                                    <small class="text-muted mb-1">Leave empty to keep current image</small>
                                                </div>
                                            </div>
                                            <label class="form-label" for="edit-categoryImage-{{ $category->id }}">New Image (Optional)</label>
                                            <input type="file" class="form-control @error('categoryImage') is-invalid @enderror" id="edit-categoryImage-{{ $category->id }}" name="categoryImage" accept="image/*" />
                                            <small class="text-muted">Allowed file types: jpeg, png, jpg, gif (Max: 2MB)</small>
                                            @error('categoryImage')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label" for="edit-categoryStatus-{{ $category->id }}">Status</label>
                                            <select id="edit-categoryStatus-{{ $category->id }}" name="categoryStatus" class="form-select @error('categoryStatus') is-invalid @enderror">
                                                <option value="Publish" {{ old('categoryStatus', $category->status) == 'Publish' ? 'selected' : '' }}>Publish</option>
                                                <option value="Inactive" {{ old('categoryStatus', $category->status) == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                                            </select>
                                            @error('categoryStatus')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-12 text-center">
                                            <button type="submit" class="btn btn-primary me-sm-3 me-1">Update</button>
                                            <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--/ Edit Category Modal -->
                    @endforeach
                </tbody>
            </table>
        </div>
        <!-- Pagination -->
        <div class="card-footer">
            <div class="d-flex justify-content-center">
                {{ $categories->links() }}
            </div>
        </div>
    </div>
    <!--/ Category List Card -->

    <!-- Add Category Modal -->
    <div class="modal fade" id="addCategoryModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-add-category">
            <div class="modal-content p-3 p-md-5">
                <div class="modal-header">
                    <h5 class="modal-title" id="addCategoryTitle">Add Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addCategoryForm" class="row g-3" action="{{ route('category.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="col-12 col-md-6">
                            <label class="form-label" for="categoryTitle">Category Name</label>
                            <input type="text" id="categoryTitle" name="categoryTitle" class="form-control @error('categoryTitle') is-invalid @enderror" placeholder="Enter category name" value="{{ old('categoryTitle') }}" />
                            @error('categoryTitle')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label" for="slug">Slug</label>
                            <input type="text" id="slug" name="slug" class="form-control @error('slug') is-invalid @enderror" placeholder="category-slug" value="{{ old('slug') }}" />
                            @error('slug')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-12">
                            <label class="form-label" for="categoryDescription">Description</label>
                            <textarea class="form-control @error('categoryDescription') is-invalid @enderror" id="categoryDescription" name="categoryDescription" rows="3" placeholder="Category description">{{ old('categoryDescription') }}</textarea>
                            @error('categoryDescription')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-12">
                            <label class="form-label" for="categoryImage">Category Image</label>
                            <input type="file" class="form-control @error('categoryImage') is-invalid @enderror" id="categoryImage" name="categoryImage" accept="image/*" />
                            <small class="text-muted">Allowed file types: jpeg, png, jpg, gif (Max: 2MB)</small>
                            @error('categoryImage')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-12">
                            <label class="form-label" for="categoryStatus">Status</label>
                            <select id="categoryStatus" name="categoryStatus" class="form-select @error('categoryStatus') is-invalid @enderror">
                                <option value="Publish" {{ old('categoryStatus') == 'Publish' ? 'selected' : '' }}>Publish</option>
                                <option value="Inactive" {{ old('categoryStatus') == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                            @error('categoryStatus')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-12 text-center">
                            <button type="submit" class="btn btn-primary me-sm-3 me-1">Submit</button>
                            <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!--/ Add Category Modal -->

    <!-- Add Category Offcanvas (Mobile) -->
    <div class="offcanvas offcanvas-end" tabindex="-1" id="addCategoryOffcanvas" aria-labelledby="addCategoryOffcanvasLabel">
        <div class="offcanvas-header">
            <h5 id="addCategoryOffcanvasLabel" class="offcanvas-title">Add Category</h5>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body mx-0 flex-grow-0">
            <form id="addCategoryFormMobile" class="row g-3" action="{{ route('category.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="col-12">
                    <label class="form-label" for="categoryTitleMobile">Category Name</label>
                    <input type="text" id="categoryTitleMobile" name="categoryTitle" class="form-control @error('categoryTitle') is-invalid @enderror" placeholder="Enter category name" value="{{ old('categoryTitle') }}" />
                    @error('categoryTitle')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-12">
                    <label class="form-label" for="slugMobile">Slug</label>
                    <input type="text" id="slugMobile" name="slug" class="form-control @error('slug') is-invalid @enderror" placeholder="category-slug" value="{{ old('slug') }}" />
                    @error('slug')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-12">
                    <label class="form-label" for="categoryDescriptionMobile">Description</label>
                    <textarea class="form-control @error('categoryDescription') is-invalid @enderror" id="categoryDescriptionMobile" name="categoryDescription" rows="3" placeholder="Category description">{{ old('categoryDescription') }}</textarea>
                    @error('categoryDescription')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-12">
                    <label class="form-label" for="categoryImageMobile">Category Image</label>
                    <input type="file" class="form-control @error('categoryImage') is-invalid @enderror" id="categoryImageMobile" name="categoryImage" accept="image/*" />
                    <small class="text-muted">Allowed file types: jpeg, png, jpg, gif (Max: 2MB)</small>
                    @error('categoryImage')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-12">
                    <label class="form-label" for="categoryStatusMobile">Status</label>
                    <select id="categoryStatusMobile" name="categoryStatus" class="form-select @error('categoryStatus') is-invalid @enderror">
                        <option value="Publish" {{ old('categoryStatus') == 'Publish' ? 'selected' : '' }}>Publish</option>
                        <option value="Inactive" {{ old('categoryStatus') == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                    @error('categoryStatus')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-12 text-center">
                    <button type="submit" class="btn btn-primary me-sm-3 me-1">Submit</button>
                    <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="offcanvas" aria-label="Close">Cancel</button>
                </div>
            </form>
        </div>
    </div>
    <!--/ Add Category Offcanvas -->
</div>
@endsection
