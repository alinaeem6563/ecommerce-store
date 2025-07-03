// Product List Page JavaScript

import $ from 'jquery';
import Swal from 'sweetalert2';

document.addEventListener('DOMContentLoaded', () => {
  // Initialize DataTable
  const productsTable = $('#products-table').DataTable({
    responsive: true,
    lengthMenu: [10, 25, 50, 100],
    pageLength: 25,
    dom:
      '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>' +
      '<"row"<"col-sm-12"tr>>' +
      '<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
    language: {
      search: '',
      searchPlaceholder: 'Search products...',
      lengthMenu: '_MENU_ products per page',
      info: 'Showing _START_ to _END_ of _TOTAL_ products',
      infoEmpty: 'No products found',
      infoFiltered: '(filtered from _MAX_ total products)',
      zeroRecords: 'No matching products found',
      paginate: {
        first: '<i class="ti ti-chevrons-left"></i>',
        previous: '<i class="ti ti-chevron-left"></i>',
        next: '<i class="ti ti-chevron-right"></i>',
        last: '<i class="ti ti-chevrons-right"></i>'
      }
    }
  });

  // Initialize search functionality
  $('#product-search').on('keyup', function () {
    productsTable.search(this.value).draw();
  });

  // Handle column visibility
  $('.column-visibility input[type="checkbox"]').on('change', function () {
    const column = productsTable.column($(this).data('column'));
    column.visible($(this).is(':checked'));
  });

  // Handle select all checkbox
  $('#select-all').on('change', function () {
    const isChecked = $(this).prop('checked');
    $('.product-checkbox').prop('checked', isChecked);
  });

  // Handle individual checkboxes
  $('.product-checkbox').on('change', () => {
    const allChecked = $('.product-checkbox:checked').length === $('.product-checkbox').length;
    $('#select-all').prop('checked', allChecked);
  });

  // Handle filters
  $('#filter-category, #filter-status, #filter-stock, #filter-type').on('change', () => {
    applyFilters();
  });

  function applyFilters() {
    const categoryFilter = $('#filter-category').val();
    const statusFilter = $('#filter-status').val();
    const stockFilter = $('#filter-stock').val();
    const typeFilter = $('#filter-type').val();

    // Clear existing filters
    productsTable.columns().search('').draw();

    // Apply category filter
    if (categoryFilter) {
      productsTable.column(4).search(categoryFilter).draw();
    }

    // Apply status filter
    if (statusFilter) {
      productsTable.column(7).search(statusFilter).draw();
    }

    // Apply stock filter
    if (stockFilter) {
      productsTable.column(6).search(stockFilter).draw();
    }

    // Apply type filter
    if (typeFilter) {
      productsTable.column(2).search(typeFilter).draw();
    }
  }

  // Handle product deletion
  $('.product-delete').on('click', function (e) {
    e.preventDefault();
    const productId = $(this).data('id');
    const deleteUrl = `/products/${productId}`;

    $('#deleteForm').attr('action', deleteUrl);
    $('#deleteModal').modal('show');
  });

  // Handle product activation
  $('.product-activate').on('click', function (e) {
    e.preventDefault();
    const productId = $(this).data('id');

    Swal.fire({
      title: 'Activate Product',
      text: 'Are you sure you want to activate this product?',
      icon: 'question',
      showCancelButton: true,
      confirmButtonText: 'Yes, activate it!',
      cancelButtonText: 'Cancel',
      customClass: {
        confirmButton: 'btn btn-success',
        cancelButton: 'btn btn-outline-secondary ms-1'
      },
      buttonsStyling: false
    }).then(result => {
      if (result.isConfirmed) {
        // Send AJAX request to activate product
        $.ajax({
          url: "/product/activate/" + productId,
          type: 'POST',
          data: {
            _token: $('meta[name="csrf-token"]').attr('content')
          },
          success: response => {
            Swal.fire({
              icon: 'success',
              title: 'Activated!',
              text: 'Product has been activated successfully.',
              customClass: {
                confirmButton: 'btn btn-success'
              },
              buttonsStyling: false
            }).then(() => {
              window.location.reload();
            });
          },
          error: error => {
            Swal.fire({
              icon: 'error',
              title: 'Error!',
              text: 'Failed to activate product. Please try again.',
              customClass: {
                confirmButton: 'btn btn-primary'
              },
              buttonsStyling: false
            });
          }
        });
      }
    });
  });

  // Handle product deactivation
  $('.product-deactivate').on('click', function (e) {
    e.preventDefault();
    const productId = $(this).data('id');

    Swal.fire({
      title: 'Deactivate Product',
      text: 'Are you sure you want to deactivate this product?',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Yes, deactivate it!',
      cancelButtonText: 'Cancel',
      customClass: {
        confirmButton: 'btn btn-warning',
        cancelButton: 'btn btn-outline-secondary ms-1'
      },
      buttonsStyling: false
    }).then(result => {
      if (result.isConfirmed) {
        // Send AJAX request to deactivate product
        $.ajax({
          url: '/product/deactivate/' + productId,
          type: 'POST',
          data: {
            _token: $('meta[name="csrf-token"]').attr('content')
          },
          success: response => {
            Swal.fire({
              icon: 'success',
              title: 'Deactivated!',
              text: 'Product has been deactivated successfully.',
              customClass: {
                confirmButton: 'btn btn-success'
              },
              buttonsStyling: false
            }).then(() => {
              window.location.reload();
            });
          },
          error: error => {
            Swal.fire({
              icon: 'error',
              title: 'Error!',
              text: 'Failed to deactivate product. Please try again.',
              customClass: {
                confirmButton: 'btn btn-primary'
              },
              buttonsStyling: false
            });
          }
        });
      }
    });
  });

  // Handle bulk actions
$('#bulkDelete').on('click', e => {
  e.preventDefault();
  const selectedProducts = getSelectedProducts();

  if (selectedProducts.length === 0) {
    Swal.fire({
      icon: 'warning',
      title: 'No Products Selected',
      text: 'Please select at least one product to delete.',
      customClass: { confirmButton: 'btn btn-primary' },
      buttonsStyling: false
    });
    return;
  }

  Swal.fire({
    title: 'Delete Selected Products',
    text: `Are you sure you want to delete ${selectedProducts.length} selected products? This action cannot be undone.`,
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: 'Yes, delete them!',
    cancelButtonText: 'Cancel',
    customClass: { confirmButton: 'btn btn-danger', cancelButton: 'btn btn-outline-secondary ms-1' },
    buttonsStyling: false
  }).then(result => {
    if (result.isConfirmed) {
      // Send AJAX request to delete products
      $.ajax({
        url: "{{route('product.bulkDelete')}}",
        type: 'POST',
        data: {
          _token: $('meta[name="csrf-token"]').attr('content'),
          product_ids: selectedProducts
        },
        success: response => {
          Swal.fire({
            icon: 'success',
            title: 'Deleted!',
            text: 'Selected products have been deleted successfully.',
            customClass: { confirmButton: 'btn btn-success' },
            buttonsStyling: false
          }).then(() => {
            window.location.reload();
          });
        },
        error: error => {
          Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: 'Failed to delete products. Please try again.',
            customClass: { confirmButton: 'btn btn-primary' },
            buttonsStyling: false
          });
        }
      });
    }
  });
});


  $('#bulkActivate').on('click', e => {
    e.preventDefault();
    const selectedProducts = getSelectedProducts();

    if (selectedProducts.length === 0) {
      Swal.fire({
        icon: 'warning',
        title: 'No Products Selected',
        text: 'Please select at least one product to activate.',
        customClass: {
          confirmButton: 'btn btn-primary'
        },
        buttonsStyling: false
      });
      return;
    }

    Swal.fire({
      title: 'Activate Selected Products',
      text: `Are you sure you want to activate ${selectedProducts.length} selected products?`,
      icon: 'question',
      showCancelButton: true,
      confirmButtonText: 'Yes, activate them!',
      cancelButtonText: 'Cancel',
      customClass: {
        confirmButton: 'btn btn-success',
        cancelButton: 'btn btn-outline-secondary ms-1'
      },
      buttonsStyling: false
    }).then(result => {
      if (result.isConfirmed) {
        // Send AJAX request to activate products
        $.ajax({
          url: "{{ route('product.bulkActivate') }}",
          type: 'POST',
          data: {
            _token: $('meta[name="csrf-token"]').attr('content'),
            product_ids: selectedProducts
          },
          success: response => {
            Swal.fire({
              icon: 'success',
              title: 'Activated!',
              text: 'Selected products have been activated successfully.',
              customClass: {
                confirmButton: 'btn btn-success'
              },
              buttonsStyling: false
            }).then(() => {
              window.location.reload();
            });
          },
          error: error => {
            Swal.fire({
              icon: 'error',
              title: 'Error!',
              text: 'Failed to activate products. Please try again.',
              customClass: {
                confirmButton: 'btn btn-primary'
              },
              buttonsStyling: false
            });
          }
        });
      }
    });
  });

  $('#bulkDeactivate').on('click', e => {
    e.preventDefault();
    const selectedProducts = getSelectedProducts();

    if (selectedProducts.length === 0) {
      Swal.fire({
        icon: 'warning',
        title: 'No Products Selected',
        text: 'Please select at least one product to deactivate.',
        customClass: {
          confirmButton: 'btn btn-primary'
        },
        buttonsStyling: false
      });
      return;
    }

    Swal.fire({
      title: 'Deactivate Selected Products',
      text: `Are you sure you want to deactivate ${selectedProducts.length} selected products?`,
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Yes, deactivate them!',
      cancelButtonText: 'Cancel',
      customClass: {
        confirmButton: 'btn btn-warning',
        cancelButton: 'btn btn-outline-secondary ms-1'
      },
      buttonsStyling: false
    }).then(result => {
      if (result.isConfirmed) {
        // Send AJAX request to deactivate products
        $.ajax({
          url: " {{route('product.bulkDeactivate')}}",
          type: 'POST',
          data: {
            _token: $('meta[name="csrf-token"]').attr('content'),
            product_ids: selectedProducts
          },
          success: response => {
            Swal.fire({
              icon: 'success',
              title: 'Deactivated!',
              text: 'Selected products have been deactivated successfully.',
              customClass: {
                confirmButton: 'btn btn-success'
              },
              buttonsStyling: false
            }).then(() => {
              window.location.reload();
            });
          },
          error: error => {
            Swal.fire({
              icon: 'error',
              title: 'Error!',
              text: 'Failed to deactivate products. Please try again.',
              customClass: {
                confirmButton: 'btn btn-primary'
              },
              buttonsStyling: false
            });
          }
        });
      }
    });
  });

  // Helper function to get selected products
  function getSelectedProducts() {
    const selectedProducts = [];
    $('.product-checkbox:checked').each(function () {
      selectedProducts.push($(this).val());
    });
    return selectedProducts;
  }
});
