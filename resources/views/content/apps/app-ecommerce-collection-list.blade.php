@extends('layouts/layoutMaster')

@section('title', 'Product Collection ')

@section('vendor-style')
    @vite(['resources/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.scss', 'resources/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.scss', 'resources/assets/vendor/libs/sweetalert2/sweetalert2.scss',
    ])
@endsection
@section('page-style')
@vite('resources/assets/vendor/scss/pages/collection-management.scss')
@endsection
@section('vendor-script')
    @vite(['resources/assets/js/collection-management.js','resources/assets/vendor/libs/moment/moment.js', 'resources/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js', 'resources/assets/vendor/libs/sweetalert2/sweetalert2.js'])
@endsection






@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">Ecommerce /</span> Collection Management
    </h4>

    <!-- Alert for success message -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <!-- Collections List Card -->
        <div class="col-12 col-lg-8 order-2 order-lg-1 mb-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Collections</h5>
                   
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Collection Name</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($collections as $collection)
                            <tr>
                                <td>{{ $collection->id }}</td>
                                <td>{{ $collection->collection }}</td>
                                <td>
                                    <div class="d-flex">
                                        <button type="button" class="btn btn-sm btn-icon edit-collection" data-bs-toggle="modal" data-bs-target="#editCollectionModal" data-id="{{ $collection->id }}" data-collection="{{ $collection->collection }}">
                                            <i class="ti ti-edit"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-icon delete-collection" data-bs-toggle="modal" data-bs-target="#deleteCollectionModal" data-id="{{ $collection->id }}">
                                            <i class="ti ti-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="d-flex justify-content-center mt-3">
                       
                    </div>
                </div>
            </div>
        </div>
        <!-- /Collections List Card -->

        <!-- Add Collection Card -->
        <div class="col-12 col-lg-4 order-1 order-lg-2 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Add New Collection</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('collections.store') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="collection" class="form-label">Collection Name</label>
                            <input type="text" class="form-control @error('collection') is-invalid @enderror" 
                                id="collection" name="collection" placeholder="Enter collection name" 
                                value="{{ old('collection') }}" required>
                            @error('collection')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Add Collection</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- /Add Collection Card -->
    </div>
</div>

<!-- Edit Collection Modal -->
<div class="modal fade" id="editCollectionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Collection</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editCollectionForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit-collection" class="form-label">Collection Name</label>
                        <input type="text" class="form-control" id="edit-collection" name="collection" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Collection Modal -->
<div class="modal fade" id="deleteCollectionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Delete Collection</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this collection?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="deleteCollectionForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection



@section('page-script')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Edit Collection Button Click
    $(document).on('click', '.edit-collection', function() {
        const id = $(this).data('id');
        const collection = $(this).data('collection');
        
        $('#edit-collection').val(collection);
        $('#editCollectionForm').attr('action', `{{ url('collections') }}/${id}`);
    });

    // Delete Collection Button Click
    $(document).on('click', '.delete-collection', function() {
        const id = $(this).data('id');
        $('#deleteCollectionForm').attr('action', `{{ url('collections') }}/${id}`);
    });
});
</script>
@endsection
