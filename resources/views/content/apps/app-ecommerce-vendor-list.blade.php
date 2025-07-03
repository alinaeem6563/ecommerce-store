@extends('layouts/layoutMaster')

@section('title', 'Vendors')

@section('vendor-style')
    @vite(['resources/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.scss', 'resources/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.scss', 'resources/assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.scss',  'resources/assets/vendor/libs/sweetalert2/sweetalert2.scss'])
@endsection

@section('page-style')
    @vite([
    'resources/assets/vendor/scss/pages/vendor-management.scss'])
@endsection

@section('vendor-script')
    @vite([ 'resources/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js', 'resources/assets/vendor/libs/sweetalert2/sweetalert2.js'
   ])
@endsection

@section('page-script')
    @vite('resources/assets/js/app-ecommerce-vendor-list.js')
@endsection

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
  <h4 class="fw-bold py-3 mb-4">
    <span class="text-muted fw-light">Vendor Management /</span> Vendors
  </h4>

  <!-- Alert for success message -->
  @if(session('success'))
  <div class="alert alert-success alert-dismissible fade show" role="alert">
    <strong>Success!</strong> {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
  @endif

  <div class="row">
    <!-- Vendor List Card -->
    <div class="col-12 col-lg-8 order-2 order-lg-1 mb-4">
      <div class="card">
        <div class="card-header border-bottom d-flex justify-content-between align-items-center flex-wrap">
          <h5 class="card-title mb-0">Vendors</h5>
          <div class="d-flex align-items-center gap-2">
            <div class="search-box">
              <i class="ti ti-search search-icon"></i>
              <input type="text" class="form-control" id="searchInput" placeholder="Search vendors...">
            </div>
            <button type="button" class="btn btn-primary d-lg-none" data-bs-toggle="offcanvas" data-bs-target="#addVendorOffcanvas">
              <i class="ti ti-plus me-1"></i> Add Vendor
            </button>
          </div>
        </div>
        <div class="card-datatable table-responsive">
          <table class="datatables-vendors table border-top">
            <thead>
              <tr>
                <th>Vendor</th>
                <th>Status</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              @foreach($vendors ?? [] as $vendor)
              <tr data-id="{{ $vendor->id }}">
                <td>
                  <div class="d-flex flex-column">
                    <h6 class="text-body mb-0">{{ $vendor->name }}</h6>
                    <small class="text-truncate text-muted">{{ \Illuminate\Support\Str::limit($vendor->description, 50) }}</small>
                  </div>
                </td>
                <td>
                  <span class="badge bg-label-{{ $vendor->status === 'Active' ? 'success' : 'warning' }} me-1">
                    {{ $vendor->status }}
                  </span>
                </td>
                <td>
                  <div class="d-flex align-items-center">
                    <button type="button" class="btn btn-sm btn-icon view-btn" data-id="{{ $vendor->id }}">
                      <i class="ti ti-eye text-primary"></i>
                    </button>
                    <button type="button" class="btn btn-sm btn-icon edit-btn" data-id="{{ $vendor->id }}">
                      <i class="ti ti-edit text-warning"></i>
                    </button>
                    <button type="button" class="btn btn-sm btn-icon delete-btn" data-id="{{ $vendor->id }}">
                      <i class="ti ti-trash text-danger"></i>
                    </button>
                  </div>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>

      <!-- Vendor Grid View (for mobile) -->
      <div class="vendor-grid-view d-md-none mt-4">
        <div class="row g-3">
          @foreach($vendors ?? [] as $vendor)
          <div class="col-12">
            <div class="card vendor-card h-100">
              <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                  <h5 class="mb-0">{{ $vendor->name }}</h5>
                  <span class="badge bg-label-{{ $vendor->status === 'Active' ? 'success' : 'warning' }}">
                    {{ $vendor->status }}
                  </span>
                </div>
                <p class="card-text text-muted small mb-3">{{ \Illuminate\Support\Str::limit($vendor->description, 100) }}</p>
                <div class="d-flex justify-content-between align-items-center">
                  <div class="btn-group" role="group">
                    <button type="button" class="btn btn-sm btn-outline-primary view-btn" data-id="{{ $vendor->id }}">
                      <i class="ti ti-eye me-1"></i> View
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-warning edit-btn" data-id="{{ $vendor->id }}">
                      <i class="ti ti-edit me-1"></i> Edit
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-danger delete-btn" data-id="{{ $vendor->id }}">
                      <i class="ti ti-trash me-1"></i> Delete
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>
          @endforeach
        </div>
      </div>
    </div>

    <!-- Add Vendor Card (Desktop) -->
    <div class="col-12 col-lg-4 order-1 order-lg-2 mb-4 d-none d-lg-block">
      <div class="card">
        <div class="card-header border-bottom">
          <h5 class="card-title mb-0">Add New Vendor</h5>
        </div>
        <div class="card-body">
          <form id="addVendorForm" method="POST" action="{{ route('vendors.store') }}">
            @csrf
            <div class="mb-3">
              <label for="name" class="form-label">Vendor Name <span class="text-danger">*</span></label>
              <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" placeholder="Enter vendor name" value="{{ old('name') }}" required>
              @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
            <div class="mb-3">
              <label for="description" class="form-label">Description</label>
              <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="4" placeholder="Enter vendor description">{{ old('description') }}</textarea>
              @error('description')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
            <div class="mb-4">
              <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
              <select class="form-select  @error('status') is-invalid @enderror" id="status" name="status" required>
                <option value="Active" {{ old('status') == 'Active' ? 'selected' : '' }}>Active</option>
                <option value="Suspended" {{ old('status') == 'Suspended' ? 'selected' : '' }}>Suspended</option>
              </select>
              @error('status')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
            <div class="d-grid">
              <button type="submit" class="btn btn-primary">Add Vendor</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- Add Vendor Offcanvas (Mobile) -->
  <div class="offcanvas offcanvas-end" tabindex="-1" id="addVendorOffcanvas" aria-labelledby="addVendorOffcanvasLabel">
    <div class="offcanvas-header border-bottom">
      <h5 class="offcanvas-title" id="addVendorOffcanvasLabel">Add New Vendor</h5>
      <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
      <form id="addVendorFormMobile" method="POST" action="{{ route('vendors.store') }}">
        @csrf
        <div class="mb-3">
          <label for="nameMobile" class="form-label">Vendor Name <span class="text-danger">*</span></label>
          <input type="text" class="form-control @error('name') is-invalid @enderror" id="nameMobile" name="name" placeholder="Enter vendor name" value="{{ old('name') }}" required>
          @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
        <div class="mb-3">
          <label for="descriptionMobile" class="form-label">Description</label>
          <textarea class="form-control @error('description') is-invalid @enderror" id="descriptionMobile" name="description" rows="4" placeholder="Enter vendor description">{{ old('description') }}</textarea>
          @error('description')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
        <div class="mb-4">
          <label for="statusMobile" class="form-label">Status <span class="text-danger">*</span></label>
          <select class="form-select @error('status') is-invalid @enderror" id="statusMobile" name="status" required>
            <option value="Active" {{ old('status') == 'Active' ? 'selected' : '' }}>Active</option>
            <option value="Suspended" {{ old('status') == 'Suspended' ? 'selected' : '' }}>Suspended</option>
          </select>
          @error('status')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
        <div class="d-grid">
          <button type="submit" class="btn btn-primary">Add Vendor</button>
        </div>
      </form>
    </div>
  </div>

  <!-- View Vendor Modal -->
  <div class="modal fade" id="viewVendorModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content p-3 p-md-5">
        <div class="modal-header">
          <h5 class="modal-title" id="viewVendorModalTitle">Vendor Details</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-12">
              <div class="mb-3">
                <div class="d-flex justify-content-between align-items-center mb-2">
                  <h4 id="viewVendorName" class="mb-0">Vendor Name</h4>
                  <span class="badge bg-label-success mb-0" id="viewVendorStatus">Active</span>
                </div>
              </div>
              <div class="mb-3">
                <h6 class="text-muted mb-2">Description</h6>
                <p id="viewVendorDescription">Vendor description will appear here.</p>
              </div>
              <div class="mb-3">
                <h6 class="text-muted mb-2">Created At</h6>
                <p id="viewVendorCreatedAt">-</p>
              </div>
              <div class="mb-3">
                <h6 class="text-muted mb-2">Updated At</h6>
                <p id="viewVendorUpdatedAt">-</p>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
          <button type="button" class="btn btn-warning edit-from-view" id="editFromViewBtn">Edit</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Edit Vendor Modal -->
  <div class="modal fade" id="editVendorModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content p-3 p-md-5">
        <div class="modal-header">
          <h5 class="modal-title" id="editVendorModalTitle">Edit Vendor</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="editVendorForm" method="POST">
          @csrf
          @method('PUT')
          <div class="modal-body">
            <div class="row">
              <div class="col-12">
                <div class="mb-3">
                  <label for="editName" class="form-label">Vendor Name <span class="text-danger">*</span></label>
                  <input type="text" class="form-control" id="editName" name="name" placeholder="Enter vendor name" required>
                  <div class="invalid-feedback">Please enter a vendor name.</div>
                </div>
                <div class="mb-3">
                  <label for="editDescription" class="form-label">Description</label>
                  <textarea class="form-control" id="editDescription" name="description" rows="4" placeholder="Enter vendor description"></textarea>
                </div>
                <div class="mb-3">
                  <label for="editStatus" class="form-label">Status <span class="text-danger">*</span></label>
                  <select class="form-select" id="editStatus" name="status" required>
                    <option value="Active">Active</option>
                    <option value="Suspended">Suspended</option>
                  </select>
                  <div class="invalid-feedback">Please select a status.</div>
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-primary">Update Vendor</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection

@section('page-script')

<script>
  // Fallback if DataTables is not available
  document.addEventListener('DOMContentLoaded', function() {
    if (typeof $.fn.DataTable === 'undefined') {
      console.warn('DataTables not loaded. Using fallback table functionality.');
      const searchInput = document.getElementById('searchInput');
      if (searchInput) {
        searchInput.addEventListener('keyup', function() {
          const searchTerm = this.value.toLowerCase();
          const rows = document.querySelectorAll('.datatables-vendors tbody tr');
          
          rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(searchTerm) ? '' : 'none';
          });
        });
      }
    }
  });
</script>
@endsection
