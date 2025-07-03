// DOM elements
document.addEventListener('DOMContentLoaded', () => {
  const addCategoryForm = document.getElementById('addCategoryForm');
  const editCategoryForm = document.getElementById('editCategoryForm');
  const searchInput = document.getElementById('searchInput');
  const categoryImageDropzone = document.getElementById('categoryImageDropzone');
  const editCategoryImageDropzone = document.getElementById('editCategoryImageDropzone');
  const imagePreview = document.getElementById('imagePreview');
  const editImagePreview = document.getElementById('editImagePreview');
  const imagePreviewContainer = document.querySelector('.image-preview-container');
  const editImagePreviewContainer = document.querySelector('.edit-image-preview-container');

  // DataTable initialization
  let categoryTable;
  if ($.fn.DataTable) {
    categoryTable = $('.datatables-categories').DataTable({
      responsive: true,
      lengthMenu: [7, 10, 25, 50, 75, 100],
      dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6 d-flex justify-content-center justify-content-md-end"f>>t<"row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
      language: {
        search: '',
        searchPlaceholder: 'Search categories'
      },
      columnDefs: [
        { targets: 0, orderable: false, searchable: false },
        { targets: -1, orderable: false, searchable: false }
      ]
    });
  }

  // Search functionality
  if (searchInput) {
    searchInput.addEventListener('keyup', function () {
      if (categoryTable) {
        categoryTable.search(this.value).draw();
      }
      filterGridView(this.value);
    });
  }

  // Initialize Dropzone for add form
  if (categoryImageDropzone) {
    initDropzone(categoryImageDropzone, 'categoryImage', imagePreview, imagePreviewContainer);
  }

  // Initialize Dropzone for edit form
  if (editCategoryImageDropzone) {
    initDropzone(editCategoryImageDropzone, 'editCategoryImage', editImagePreview, editImagePreviewContainer);
  }

  // Remove image button handlers
  const removeImageBtn = document.querySelector('.remove-image');
  if (removeImageBtn) {
    removeImageBtn.addEventListener('click', () => {
      document.getElementById('categoryImage').value = '';
      imagePreviewContainer.classList.add('d-none');
    });
  }

  const removeEditImageBtn = document.querySelector('.remove-edit-image');
  if (removeEditImageBtn) {
    removeEditImageBtn.addEventListener('click', () => {
      document.getElementById('editCategoryImage').value = '';
      editImagePreviewContainer.classList.add('d-none');
    });
  }

  // Add form submission
  if (addCategoryForm) {
    addCategoryForm.addEventListener('submit', handleAddCategory);
  }

  // Edit form submission
  if (editCategoryForm) {
    editCategoryForm.addEventListener('submit', handleEditCategory);
  }

  // View button click handler
  document.addEventListener('click', e => {
    if (e.target.closest('.view-btn')) {
      const categoryId = e.target.closest('.view-btn').dataset.id;
      viewCategory(categoryId);
    }
  });

  // Edit button click handler
  document.addEventListener('click', e => {
    if (e.target.closest('.edit-btn')) {
      const categoryId = e.target.closest('.edit-btn').dataset.id;
      editCategory(categoryId);
    }
  });

  // Delete button click handler
  document.addEventListener('click', e => {
    if (e.target.closest('.delete-btn')) {
      const categoryId = e.target.closest('.delete-btn').dataset.id;
      deleteCategory(categoryId);
    }
  });

  // Edit from view button
  const editFromViewBtn = document.getElementById('editFromViewBtn');
  if (editFromViewBtn) {
    editFromViewBtn.addEventListener('click', function () {
      const categoryId = this.dataset.categoryId;
      $('#viewCategoryModal').modal('hide');
      editCategory(categoryId);
    });
  }

  // Auto-generate slug from title
  const categoryTitle = document.getElementById('categoryTitle');
  if (categoryTitle) {
    categoryTitle.addEventListener('input', function () {
      const slugField = document.getElementById('slug');
      if (!slugField.value) {
        slugField.value = this.value
          .toLowerCase()
          .replace(/[^\w\s-]/g, '')
          .replace(/[\s_-]+/g, '-')
          .replace(/^-+|-+$/g, '');
      }
    });
  }

  const editCategoryTitle = document.getElementById('editCategoryTitle');
  if (editCategoryTitle) {
    editCategoryTitle.addEventListener('input', function () {
      const slugField = document.getElementById('editSlug');
      if (!slugField.dataset.originalValue || slugField.value === slugField.dataset.originalValue) {
        slugField.value = this.value
          .toLowerCase()
          .replace(/[^\w\s-]/g, '')
          .replace(/[\s_-]+/g, '-')
          .replace(/^-+|-+$/g, '');
      }
    });
  }
});

// Initialize Dropzone
function initDropzone(dropzoneElement, inputId, previewElement, previewContainer) {
  dropzoneElement.addEventListener('click', () => {
    document.getElementById(inputId).click();
  });

  dropzoneElement.addEventListener('dragover', function (e) {
    e.preventDefault();
    this.classList.add('border-primary');
  });

  dropzoneElement.addEventListener('dragleave', function () {
    this.classList.remove('border-primary');
  });

  dropzoneElement.addEventListener('drop', function (e) {
    e.preventDefault();
    this.classList.remove('border-primary');

    if (e.dataTransfer.files.length) {
      document.getElementById(inputId).files = e.dataTransfer.files;
      handleImagePreview(document.getElementById(inputId), previewElement, previewContainer);
    }
  });

  document.getElementById(inputId).addEventListener('change', function () {
    handleImagePreview(this, previewElement, previewContainer);
  });
}

// Handle image preview
function handleImagePreview(input, previewElement, previewContainer) {
  if (input.files && input.files[0]) {
    const reader = new FileReader();

    reader.onload = e => {
      previewElement.src = e.target.result;
      previewContainer.classList.remove('d-none');
    };

    reader.onerror = () => {
      // Use a data URI fallback
      previewElement.src =
        'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAwIiBoZWlnaHQ9IjIwMCIgdmlld0JveD0iMCAwIDIwMCAyMDAiIGZpbGw9Im5vbmUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PHJlY3Qgd2lkdGg9IjIwMCIgaGVpZ2h0PSIyMDAiIGZpbGw9IiNFNUU3RUIiLz48cGF0aCBkPSJNMTAwIDEyOEMxMTcuNjczIDEyOCAxMzIgMTEzLjY3MyAxMzIgOTZDMTMyIDc4LjMyNyAxMTcuNjczIDY0IDEwMCA2NEM4Mi4zMjcgNjQgNjggNzguMzI3IDY4IDk2QzY4IDExMy42NzMgODIuMzI3IDEyOCAxMDAgMTI4WiIgZmlsbD0iI0EzQTNBMyIvPjxwYXRoIGQ9Ik0xNTIgMTc2SDQ4QzQ4IDE0Ni4zNzcgNzEuNDkgMTIyIDEwMCAxMjJDMTI4LjUxIDEyMiAxNTIgMTQ2LjM3NyAxNTIgMTc2WiIgZmlsbD0iI0EzQTNBMyIvPjwvc3ZnPg==';
      previewContainer.classList.remove('d-none');
    };

    reader.readAsDataURL(input.files[0]);
  }
}

// Filter grid view
function filterGridView(value) {
  const cards = document.querySelectorAll('.category-card');
  const searchTerm = value.toLowerCase();

  cards.forEach(card => {
    const title = card.querySelector('h6').textContent.toLowerCase();
    const description = card.querySelector('p').textContent.toLowerCase();

    if (title.includes(searchTerm) || description.includes(searchTerm)) {
      card.closest('.col-12').style.display = '';
    } else {
      card.closest('.col-12').style.display = 'none';
    }
  });
}

// Handle add category form submission
function handleAddCategory(e) {
  e.preventDefault();

  if (!this.checkValidity()) {
    e.stopPropagation();
    this.classList.add('was-validated');
    return;
  }

  const formData = new FormData(this);

  // Show loading state
  const submitBtn = this.querySelector('button[type="submit"]');
  const originalBtnText = submitBtn.innerHTML;
  submitBtn.innerHTML =
    '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Adding...';
  submitBtn.disabled = true;

  // Use the store method from the controller
  fetch('/apps/EcommerceProductCategory', {
    method: 'POST',
    body: formData,
    headers: {
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
      'X-Requested-With': 'XMLHttpRequest'
    }
  })
    .then(response => {
      if (!response.ok) {
        throw new Error(`HTTP error! Status: ${response.status}`);
      }
      return response.json();
    })
    .then(data => {
      if (data.success) {
        // Show success message
        Swal.fire({
          title: 'Success!',
          text: data.success,
          icon: 'success',
          customClass: {
            confirmButton: 'btn btn-primary'
          },
          buttonsStyling: false
        }).then(() => {
          // Reload the page to show the new category
          window.location.reload();
        });

        // Reset form
        this.reset();
        imagePreviewContainer.classList.add('d-none');
        $('#addCategoryModal').modal('hide');
      } else {
        // Show error message
        Swal.fire({
          title: 'Error!',
          text: 'Something went wrong. Please try again.',
          icon: 'error',
          customClass: {
            confirmButton: 'btn btn-primary'
          },
          buttonsStyling: false
        });
      }
    })
    .catch(error => {
      console.error('Error:', error);
      Swal.fire({
        title: 'Error!',
        text: 'An unexpected error occurred. Please try again.',
        icon: 'error',
        customClass: {
          confirmButton: 'btn btn-primary'
        },
        buttonsStyling: false
      });
    })
    .finally(() => {
      // Reset button state
      submitBtn.innerHTML = originalBtnText;
      submitBtn.disabled = false;
    });
}

// Handle edit category form submission
function handleEditCategory(e) {
  e.preventDefault();

  if (!this.checkValidity()) {
    e.stopPropagation();
    this.classList.add('was-validated');
    return;
  }

  const categoryId = document.getElementById('editCategoryId').value;
  const formData = new FormData(this);

  // Show loading state
  const submitBtn = this.querySelector('button[type="submit"]');
  const originalBtnText = submitBtn.innerHTML;
  submitBtn.innerHTML =
    '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Updating...';
  submitBtn.disabled = true;

  // Use the update method from the controller
  fetch(`/apps/EcommerceProductCategory/${categoryId}`, {
    method: 'POST', // Laravel accepts POST with _method=PUT
    body: formData,
    headers: {
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
      'X-Requested-With': 'XMLHttpRequest'
    }
  })
    .then(response => {
      if (!response.ok) {
        throw new Error(`HTTP error! Status: ${response.status}`);
      }
      return response.json();
    })
    .then(data => {
      if (data.success) {
        // Show success message
        Swal.fire({
          title: 'Success!',
          text: data.success,
          icon: 'success',
          customClass: {
            confirmButton: 'btn btn-primary'
          },
          buttonsStyling: false
        }).then(() => {
          // Reload the page to show the updated category
          window.location.reload();
        });

        // Reset form
        $('#editCategoryModal').modal('hide');
      } else {
        // Show error message
        Swal.fire({
          title: 'Error!',
          text: 'Something went wrong. Please try again.',
          icon: 'error',
          customClass: {
            confirmButton: 'btn btn-primary'
          },
          buttonsStyling: false
        });
      }
    })
    .catch(error => {
      console.error('Error:', error);
      Swal.fire({
        title: 'Error!',
        text: 'An unexpected error occurred. Please try again.',
        icon: 'error',
        customClass: {
          confirmButton: 'btn btn-primary'
        },
        buttonsStyling: false
      });
    })
    .finally(() => {
      // Reset button state
      submitBtn.innerHTML = originalBtnText;
      submitBtn.disabled = false;
    });
}

// View category details
function viewCategory(categoryId) {
  // Use the show method from the controller
  fetch(`/apps/EcommerceProductCategory/${categoryId}`, {
    method: 'GET',
    headers: {
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
      'X-Requested-With': 'XMLHttpRequest',
      Accept: 'application/json'
    }
  })
    .then(response => {
      if (!response.ok) {
        throw new Error(`HTTP error! Status: ${response.status}`);
      }
      return response.json();
    })
    .then(data => {
      // Populate view modal
      document.getElementById('viewCategoryName').textContent = data.name || 'Unknown';
      document.getElementById('viewCategorySlug').textContent = data.slug || 'unknown-slug';
      document.getElementById('viewCategoryDescription').textContent = data.description || 'No description available';

      // Set status badge
      const statusBadge = document.querySelector('.view-category-status');
      statusBadge.textContent = data.status || 'Unknown';
      statusBadge.className = `badge ${data.status === 'Publish' ? 'bg-label-success' : 'bg-label-warning'} mb-2 view-category-status`;

      // Set created at date
      document.getElementById('viewCategoryCreatedAt').textContent = data.created_at
        ? new Date(data.created_at).toLocaleString()
        : 'Unknown';

      // Set image
      const categoryImage = document.getElementById('viewCategoryImage');
      const categoryImagePlaceholder = document.querySelector('.category-image-placeholder');

      if (data.image_path) {
        categoryImage.src = data.image_path;
        categoryImage.style.display = 'block';
        categoryImagePlaceholder.classList.add('d-none');

        // Add error handler for image
        categoryImage.onerror = function () {
          this.style.display = 'none';
          categoryImagePlaceholder.classList.remove('d-none');
          document.getElementById('viewCategoryInitial').textContent = (data.name || '?').charAt(0).toUpperCase();
        };
      } else {
        categoryImage.style.display = 'none';
        categoryImagePlaceholder.classList.remove('d-none');
        document.getElementById('viewCategoryInitial').textContent = (data.name || '?').charAt(0).toUpperCase();
      }

      // Set edit button data
      document.getElementById('editFromViewBtn').dataset.categoryId = categoryId;

      // Show modal
      $('#viewCategoryModal').modal('show');
    })
    .catch(error => {
      console.error('Error:', error);
      Swal.fire({
        title: 'Error!',
        text: 'Failed to load category details. Please check your network connection and try again.',
        icon: 'error',
        customClass: {
          confirmButton: 'btn btn-primary'
        },
        buttonsStyling: false
      });
    });
}

// Edit category
function editCategory(categoryId) {
  // Use the edit method from the controller
  fetch(`/apps/EcommerceProductCategory/${categoryId}/edit`, {
    method: 'GET',
    headers: {
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
      'X-Requested-With': 'XMLHttpRequest',
      Accept: 'application/json'
    }
  })
    .then(response => {
      if (!response.ok) {
        throw new Error(`HTTP error! Status: ${response.status}`);
      }
      return response.json();
    })
    .then(data => {
      // Populate edit form
      document.getElementById('editCategoryId').value = categoryId;
      document.getElementById('editCategoryTitle').value = data.name || '';
      document.getElementById('editSlug').value = data.slug || '';
      document.getElementById('editSlug').dataset.originalValue = data.slug || '';
      document.getElementById('editCategoryDescription').value = data.description || '';

      // Set status
      const statusSelect = document.getElementById('editCategoryStatus');
      statusSelect.selectedIndex = 0; // Default to first option
      if (data.status) {
        for (let i = 0; i < statusSelect.options.length; i++) {
          if (statusSelect.options[i].value === data.status) {
            statusSelect.selectedIndex = i;
            break;
          }
        }
      }

      // Set image preview if exists
      const editImagePreview = document.getElementById('editImagePreview');
      const editImagePreviewContainer = document.querySelector('.edit-image-preview-container');

      if (data.image_path) {
        editImagePreview.src = data.image_path;
        editImagePreviewContainer.classList.remove('d-none');

        // Add error handler for image
        editImagePreview.onerror = () => {
          editImagePreviewContainer.classList.add('d-none');
        };
      } else {
        editImagePreviewContainer.classList.add('d-none');
      }

      // Show modal
      $('#editCategoryModal').modal('show');
    })
    .catch(error => {
      console.error('Error:', error);
      Swal.fire({
        title: 'Error!',
        text: 'Failed to load category data. Please check your network connection and try again.',
        icon: 'error',
        customClass: {
          confirmButton: 'btn btn-primary'
        },
        buttonsStyling: false
      });
    });
}

// Delete category
function deleteCategory(categoryId) {
  Swal.fire({
    title: 'Are you sure?',
    text: "You won't be able to revert this!",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: 'Yes, delete it!',
    customClass: {
      confirmButton: 'btn btn-danger me-3',
      cancelButton: 'btn btn-secondary'
    },
    buttonsStyling: false
  }).then(result => {
    if (result.isConfirmed) {
      // Use the destroy method from the controller
      fetch(`/apps/EcommerceProductCategory/${categoryId}`, {
        method: 'DELETE',
        headers: {
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
          'X-Requested-With': 'XMLHttpRequest',
          Accept: 'application/json'
        }
      })
        .then(response => {
          if (!response.ok) {
            throw new Error(`HTTP error! Status: ${response.status}`);
          }
          return response.json();
        })
        .then(data => {
          if (data.success) {
            Swal.fire({
              title: 'Deleted!',
              text: data.success,
              icon: 'success',
              customClass: {
                confirmButton: 'btn btn-success'
              },
              buttonsStyling: false
            }).then(() => {
              // Reload the page to update the category list
              window.location.reload();
            });
          } else {
            Swal.fire({
              title: 'Error!',
              text: 'Failed to delete category. Please try again.',
              icon: 'error',
              customClass: {
                confirmButton: 'btn btn-primary'
              },
              buttonsStyling: false
            });
          }
        })
        .catch(error => {
          console.error('Error:', error);
          Swal.fire({
            title: 'Error!',
            text: 'An unexpected error occurred. Please try again.',
            icon: 'error',
            customClass: {
              confirmButton: 'btn btn-primary'
            },
            buttonsStyling: false
          });
        });
    }
  });
}

var $ = jQuery;
