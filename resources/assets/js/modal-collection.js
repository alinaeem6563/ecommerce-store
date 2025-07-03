
document.addEventListener('DOMContentLoaded', function() {
    // Initialize DataTable
    const collectionsTable = $('#collections-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('collections.index') }}",
        columns: [
            { data: 'id' },
            { data: 'collection' },
            { 
                data: 'id',
                orderable: false,
                searchable: false,
                render: function(data) {
                    return `
                        <div class="d-inline-block">
                            <button class="btn btn-sm btn-icon edit-collection" data-id="${data}">
                                <i class="bx bx-edit-alt"></i>
                            </button>
                            <button class="btn btn-sm btn-icon delete-collection" data-id="${data}">
                                <i class="bx bx-trash"></i>
                            </button>
                        </div>
                    `;
                }
            }
        ],
        dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>><"table-responsive"t><"row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
        language: {
            paginate: {
                previous: '<i class="bx bx-chevron-left"></i>',
                next: '<i class="bx bx-chevron-right"></i>'
            }
        }
    });

    // Add Collection Form Submission (Desktop)
    $('#addCollectionForm').on('submit', function(e) {
        e.preventDefault();
        submitCollectionForm($(this), collectionsTable);
    });

    // Add Collection Form Submission (Mobile)
    $('#addCollectionFormMobile').on('submit', function(e) {
        e.preventDefault();
        submitCollectionForm($(this), collectionsTable);
    });

    // Edit Collection Button Click
    $(document).on('click', '.edit-collection', function() {
        const id = $(this).data('id');
        const collection = collectionsTable.row($(this).closest('tr')).data().collection;
        
        $('#edit-collection').val(collection);
        $('#editCollectionForm').attr('action', `{{ url('collections') }}/${id}`);
        $('#editCollectionModal').modal('show');
    });

    // Edit Collection Form Submission
    $('#editCollectionForm').on('submit', function(e) {
        e.preventDefault();
        const form = $(this);
        const url = form.attr('action');
        
        $.ajax({
            url: url,
            type: 'POST',
            data: form.serialize(),
            dataType: 'json',
            success: function(response) {
                $('#editCollectionModal').modal('hide');
                collectionsTable.ajax.reload();
                showToast('success', response.message || 'Collection updated successfully!');
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    const errors = xhr.responseJSON.errors;
                    if (errors.collection) {
                        $('#edit-collection').addClass('is-invalid');
                        $('#edit-collection-error').text(errors.collection[0]);
                    }
                } else {
                    showToast('error', 'An error occurred while updating the collection.');
                }
            }
        });
    });

    // Delete Collection Button Click
    $(document).on('click', '.delete-collection', function() {
        const id = $(this).data('id');
        
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!',
            customClass: {
                confirmButton: 'btn btn-danger me-3',
                cancelButton: 'btn btn-outline-secondary'
            },
            buttonsStyling: false
        }).then(function(result) {
            if (result.isConfirmed) {
                $.ajax({
                    url: `{{ url('collections') }}/${id}`,
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        collectionsTable.ajax.reload();
                        showToast('success', response.message || 'Collection deleted successfully!');
                    },
                    error: function() {
                        showToast('error', 'An error occurred while deleting the collection.');
                    }
                });
            }
        });
    });

    // Helper function to submit collection form
    function submitCollectionForm(form, table) {
        const formData = form.serialize();
        const url = form.attr('action');
        
        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                form[0].reset();
                table.ajax.reload();
                
                // Close offcanvas if it's the mobile form
                if (form.attr('id') === 'addCollectionFormMobile') {
                    $('#addCollectionOffcanvas').offcanvas('hide');
                }
                
                showToast('success', response.success || 'Collection created successfully!');
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    const errors = xhr.responseJSON.errors;
                    if (errors.collection) {
                        const inputId = form.attr('id') === 'addCollectionFormMobile' ? 'collection-mobile' : 'collection';
                        const errorId = form.attr('id') === 'addCollectionFormMobile' ? 'collection-mobile-error' : 'collection-error';
                        
                        $(`#${inputId}`).addClass('is-invalid');
                        $(`#${errorId}`).text(errors.collection[0]);
                    }
                } else {
                    showToast('error', 'An error occurred while creating the collection.');
                }
            }
        });
    }

    // Helper function to show toast notifications
    function showToast(type, message) {
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer);
                toast.addEventListener('mouseleave', Swal.resumeTimer);
            }
        });
        
        Toast.fire({
            icon: type,
            title: message
        });
    }
});
