document.addEventListener('DOMContentLoaded', () => {
  // Edit Collection Button Click
  $(document).on('click', '.edit-collection', function () {
    const id = $(this).data('id');
    const collection = $(this).data('collection');

    $('#edit-collection').val(collection);
    $('#editCollectionForm').attr('action', `/collections/${id}`);
  });

  // Delete Collection Button Click
  $(document).on('click', '.delete-collection', function () {
    const id = $(this).data('id');
    $('#deleteCollectionForm').attr('action', `/collections/${id}`);
  });
});
