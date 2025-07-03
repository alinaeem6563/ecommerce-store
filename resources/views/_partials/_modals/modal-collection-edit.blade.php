<!-- Edit Collection Modal -->
<div class="modal fade" id="editCollectionModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-simple modal-edit-collection">
    <div class="modal-content">
      <div class="modal-body">
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        <div class="text-center mb-4">
          <h4 class="mb-2">Edit Collection</h4>
          <p>Modify the collection details below.</p>
        </div>
        <form method="POST" id="editCollectionForm" class="row g-4">
          @csrf
          @method('PUT')
          <div class="col-12">
            <label class="form-label" for="collection">Collection Name</label>
            <input type="text" id="edit_collection_name" name="collection" class="form-control" placeholder="Enter collection name" />
          </div>
          <div class="col-12 text-center">
            <button type="submit" class="btn btn-primary me-2">Update Collection</button>
            <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<!--/ Edit Collection Modal -->
