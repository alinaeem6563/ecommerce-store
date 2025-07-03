<!-- Create New variant option Modal -->
<div class="modal fade" id="editVariantOption" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-simple modal-new-variant-option">
    <div class="modal-content">
      <div class="modal-body">
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        <div class="text-center mb-4">
          <h4 class="mb-2">New Variant Option</h4>
          <p>Create New Variant Option.</p>
        </div>
        <form action="{{route('variant.store')}}" method="POST" id="editVariantOptionForm" class="row g-4" onsubmit="return true">
          @csrf
            <div class="col-12">
            <label class="form-label" for="editVariantOption">New Variant</label>
            <input type="text" id="editVariantOption" name="name" class="form-control" placeholder="Enter Variant Option" />
          </div>
          <div class="col-12 text-center">
            <button type="submit" class="btn btn-primary me-2">Create Variant</button>
            <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<!--/Create New variant option Modal -->
<!-- Create New variant option Modal -->
<div class="modal fade" id="editVariantOption" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-simple modal-edit-variant-option">
    <div class="modal-content">
      <div class="modal-body">
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        <div class="text-center mb-4">
          <h4 class="mb-2">Update Variant Option</h4>
          <p>Update Variant Option.</p>
        </div>
        <form action="{{route('variant.update')}}" method="POST" id="editVariantOptionForm" class="row g-4" onsubmit="return true">
          @csrf
          @method('PUT')
            <div class="col-12">
            <label class="form-label" for="editVariantOption">Update Variant</label>
            <input type="text" id="editVariantOption" name="name" class="form-control" placeholder="Enter Variant Option" />
          </div>
          <div class="col-12 text-center">
            <button type="submit" class="btn btn-primary me-2">Update Variant</button>
            <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<!--/Create New variant option Modal -->
