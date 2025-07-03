// DOM elements
document.addEventListener('DOMContentLoaded', () => {
  const searchInput = document.getElementById('searchInput');
  const editVendorForm = document.getElementById('editVendorForm');

  // DataTable initialization
  let vendorTable;
  if (typeof $.fn !== 'undefined' && $.fn.DataTable) {
    vendorTable = $('.datatables-vendors').DataTable({
      responsive: true,
      lengthMenu: [7, 10, 25, 50, 75, 100],
      dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6 d-flex justify-content-center justify-content-md-end"f>>t<"row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
      language: {
        search: '',
        searchPlaceholder: 'Search vendors'
      },
      columnDefs: [{ targets: -1, orderable: false, searchable: false }]
    });
  }

  // Search functionality
  if (searchInput) {
    searchInput.addEventListener('keyup', function () {
      if (vendorTable) {
        vendorTable.search(this.value).draw();
      }
      filterGridView(this.value);
    });
  }

  // View button click handler
  document.addEventListener('click', e => {
    if (e.target.closest('.view-btn')) {
      const vendorId = e.target.closest('.view-btn').dataset.id;
      viewVendor(vendorId);
    }
  });

  // Edit button click handler
  document.addEventListener('click', e => {
    if (e.target.closest('.edit-btn')) {
      const vendorId = e.target.closest('.edit-btn').dataset.id;
      editVendor(vendorId);
    }
  });

  // Delete button click handler
  document.addEventListener('click', e => {
    if (e.target.closest('.delete-btn')) {
      const vendorId = e.target.closest('.delete-btn').dataset.id;
      deleteVendor(vendorId);
    }
  });

  // Edit from view button
  const editFromViewBtn = document.getElementById('editFromViewBtn');
  if (editFromViewBtn) {
    editFromViewBtn.addEventListener('click', function () {
      const vendorId = this.dataset.vendorId;
      $('#viewVendorModal').modal('hide');
      editVendor(vendorId);
    });
  }

  // Edit form submission
  if (editVendorForm) {
    editVendorForm.addEventListener('submit', handleEditVendor);
  }
});

// Filter grid view
function filterGridView(value) {
  const cards = document.querySelectorAll('.vendor-card');
  const searchTerm = value.toLowerCase();

  cards.forEach(card => {
    const title = card.querySelector('h5').textContent.toLowerCase();
    const description = card.querySelector('p').textContent.toLowerCase();

    if (title.includes(searchTerm) || description.includes(searchTerm)) {
      card.closest('.col-12').style.display = '';
    } else {
      card.closest('.col-12').style.display = 'none';
    }
  });
}

// View vendor details
function viewVendor(vendorId) {
  // Fetch vendor details
  fetch(`/vendors/${vendorId}`, {
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
      document.getElementById('viewVendorName').textContent = data.name || 'Unknown';
      document.getElementById('viewVendorDescription').textContent = data.description || 'No description available';

      // Set status badge
      const statusBadge = document.getElementById('viewVendorStatus');
      statusBadge.textContent = data.status || 'Unknown';
      statusBadge.className = `badge ${data.status === 'Active' ? 'bg-label-success' : 'bg-label-warning'} mb-0`;

      // Set dates
      document.getElementById('viewVendorCreatedAt').textContent = data.created_at
        ? new Date(data.created_at).toLocaleString()
        : 'Unknown';
      document.getElementById('viewVendorUpdatedAt').textContent = data.updated_at
        ? new Date(data.updated_at).toLocaleString()
        : 'Unknown';

      // Set edit button data
      document.getElementById('editFromViewBtn').dataset.vendorId = vendorId;

      // Show modal
      $('#viewVendorModal').modal('show');
    })
    .catch(error => {
      console.error('Error:', error);
      Swal.fire({
        title: 'Error!',
        text: 'Failed to load vendor details. Please check your network connection and try again.',
        icon: 'error',
        customClass: {
          confirmButton: 'btn btn-primary'
        },
        buttonsStyling: false
      });
    });
}

// Edit vendor
function editVendor(vendorId) {
  // Fetch vendor details for editing
  fetch(`/vendors/${vendorId}/edit`, {
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
      document.getElementById('editName').value = data.name || '';
      document.getElementById('editDescription').value = data.description || '';

      // Set status
      const statusSelect = document.getElementById('editStatus');
      statusSelect.value = data.status || 'Active';

      // Set form action
      document.getElementById('editVendorForm').action = `/vendors/${vendorId}`;

      // Show modal
      $('#editVendorModal').modal('show');
    })
    .catch(error => {
      console.error('Error:', error);
      Swal.fire({
        title: 'Error!',
        text: 'Failed to load vendor data. Please check your network connection and try again.',
        icon: 'error',
        customClass: {
          confirmButton: 'btn btn-primary'
        },
        buttonsStyling: false
      });
    });
}

// Handle edit vendor form submission
function handleEditVendor(e) {
  if (!this.checkValidity()) {
    e.preventDefault();
    e.stopPropagation();
    this.classList.add('was-validated');
    return;
  }
}

// Delete vendor
function deleteVendor(vendorId) {
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
      // Create form for deletion
      const form = document.createElement('form');
      form.method = 'POST';
      form.action = `/vendors/${vendorId}`;
      form.style.display = 'none';

      const csrfToken = document.createElement('input');
      csrfToken.type = 'hidden';
      csrfToken.name = '_token';
      csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

      const methodField = document.createElement('input');
      methodField.type = 'hidden';
      methodField.name = '_method';
      methodField.value = 'DELETE';

      form.appendChild(csrfToken);
      form.appendChild(methodField);
      document.body.appendChild(form);

      form.submit();
    }
  });
}
