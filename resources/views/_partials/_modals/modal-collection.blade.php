<!-- Create New Collection Modal -->
<div class="modal fade" id="newCollection" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-simple modal-new-collection">
    <div class="modal-content">
      <div class="modal-body">
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        <div class="text-center mb-4">
          <h4 class="mb-2">New Collection</h4>
          <p>Create New collection below.</p>
        </div>
        <form action={{route('collections.store')}} method="POST" id="newCollectionForm" class="row g-4" onsubmit="return true">
          @csrf
            <div class="col-12">
            <label class="form-label" for="collection">Collection Name</label>
            <input type="text" id="collection" name="collection" class="form-control" placeholder="Enter collection name" />
          </div>
          <div class="col-12 text-center">
            <button type="submit" class="btn btn-primary me-2">Create Collection</button>
            <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<!--/Create New Collection Modal -->
