// DOM Loaded
document.addEventListener('DOMContentLoaded', () => {
  // Variables
  const productForm = document.getElementById('product-form');
  const productNameInput = document.getElementById('product_name');
  const productSlugInput = document.getElementById('product_slug');
  const generateSlugBtn = document.getElementById('generate-slug');
  const generateSkuBtn = document.getElementById('generate-sku');
  const skuInput = document.getElementById('sku');
  const inventoryTrackingCheckbox = document.getElementById('inventory_tracking');
  const hasVariantsCheckbox = document.getElementById('has_variants');
  const variantsContainer = document.getElementById('variants-container');
  const variantAttributesSelect = document.getElementById('variant_attributes');
  const variantOptionsContainer = document.getElementById('variant-options-container');
  const generateVariantsBtn = document.getElementById('generate-variants');
  const variantsTableContainer = document.getElementById('variants-table-container');
  const addFeatureBtn = document.getElementById('add-feature');
  const addBenefitBtn = document.getElementById('add-benefit');
  const addVideoBtn = document.getElementById('add-video');
  const addAttributeBtn = document.getElementById('add-attribute');
  const addSpecificationBtn = document.getElementById('add-specification');
  const prevTabBtn = document.getElementById('prev-tab');
  const nextTabBtn = document.getElementById('next-tab');
  const saveDraftBtn = document.getElementById('save-draft');
  const statusSelect = document.getElementById('status');

  // Initialize Select2
  if (typeof $ !== 'undefined' && typeof $.fn.select2 !== 'undefined') {
    $('.select2').each(function () {
      const $this = $(this);
      $this.wrap('<div class="position-relative"></div>').select2({
        placeholder: $this.data('placeholder') || 'Select option',
        dropdownParent: $this.parent()
      });
    });
  }

  // Initialize TinyMCE
  if (typeof tinymce !== 'undefined') {
    tinymce.init({
      selector: '.rich-editor',
      height: 300,
      menubar: false,
      plugins: [
        'advlist',
        'autolink',
        'lists',
        'link',
        'image',
        'charmap',
        'preview',
        'anchor',
        'searchreplace',
        'visualblocks',
        'code',
        'fullscreen',
        'insertdatetime',
        'media',
        'table',
        'help',
        'wordcount'
      ],
      toolbar:
        'undo redo | formatselect | ' +
        'bold italic backcolor | alignleft aligncenter ' +
        'alignright alignjustify | bullist numlist outdent indent | ' +
        'removeformat | help',
      content_style:
        'body { font-family: -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif; font-size: 14px; }'
    });
  }

  // Initialize Flatpickr
  if (typeof flatpickr !== 'undefined') {
    flatpickr('.date-range-picker', {
      mode: 'range',
      dateFormat: 'Y-m-d',
      onChange: (selectedDates, dateStr, instance) => {
        if (selectedDates.length === 2) {
          document.getElementById('discount_start_date').value = formatDate(selectedDates[0]);
          document.getElementById('discount_end_date').value = formatDate(selectedDates[1]);
        }
      }
    });
  }

  // Initialize Tagify if available
  if (typeof Tagify !== 'undefined') {
    // Tags input
    const tagsInput = document.getElementById('tags');
    if (tagsInput) {
      new Tagify(tagsInput, {
        whitelist: [],
        enforceWhitelist: false,
        delimiters: ','
      });
    }

    // Meta keywords
    const metaKeywordsInput = document.getElementById('meta_keywords');
    if (metaKeywordsInput) {
      new Tagify(metaKeywordsInput, {
        whitelist: [],
        enforceWhitelist: false,
        delimiters: ','
      });
    }
  }
$(document).on('click', '#generate-barcode', function () {
  function generateBarcode(length = 12) {
    let barcode = '';
    for (let i = 0; i < length; i++) {
      barcode += Math.floor(Math.random() * 10); // Generate a random digit (0-9)
    }
    return barcode; // Return generated barcode
  }

  $('#barcode').val(generateBarcode()); // Generate barcode and set it in input field
});


  // Generate slug from product name

  $(document).on('click', '#generate-slug', function () {
    const productName = $('#product_name').val().trim();
    // Trim spaces

    if (productName) {
      const slug = productName

        .toLowerCase()

        .replace(/[^\w\s-]/g, '')

        // Remove special characters

        .replace(/[\s_-]+/g, '-')

        // Replace spaces and underscores with "-"

        .replace(/^-+|-+$/g, '');
      // Trim dashes from start and end

      $('#product_slug').val(slug);
    }
  });
  $(document).on('click', '#generate-sku', function () {
    const productName = $('#product_name').val().trim();
    // Trim spaces

    if (productName) {
      const randomNum = Math.floor(Math.random() * 10000);
      // Generate random number

      const prefix = productName.substring(0, 3).toUpperCase();
      // Extract first 3 letters

      const sku = `
${prefix}
-
${randomNum}
`;
      $('#sku').val(sku);
    }
  });
  // Handle inventory tracking toggle
  $(document).on('change', '#inventory_tracking', function () {
    $('.inventory-field').toggle(this.checked);
  });

  // Handle variants toggle
  $(document).on('change', '#has_variants', function () {
    if (this.checked) {
      $('#variants-container').show();
    } else {
      $('#variants-container').hide();
      $('#variant-options-container, #variants-table-container').empty();
    }
  });

  // Handle variant attributes change
  $(document).on('change', '#variant_attributes', function () {
    const selectedAttributes = $(this).val();

    if (selectedAttributes && selectedAttributes.length > 0) {
      let optionsHtml = '';

      selectedAttributes.forEach(attribute => {
        optionsHtml += `
                <div class="mb-3">
                    <label for="${attribute}_options" class="form-label">
                        ${capitalizeFirstLetter(attribute)} Options
                    </label>
                    <input type="text" class="form-control" id="${attribute}_options" 
                        name="attribute_options[${attribute}]" data-role="tagsinput" 
                        placeholder="Add ${attribute} options">
                    <small class="text-muted">Press Enter or comma to add multiple options</small>
                </div>
            `;
      });

      $('#variant-options-container').html(optionsHtml);

      // Initialize tagsinput for new fields
      if ($.fn.tagsinput) {
        $('#variant-options-container input[data-role="tagsinput"]').tagsinput({
          trimValue: true,
          confirmKeys: [13, 44, 32] // Enter, comma, space
        });
      }
    } else {
      $('#variant-options-container').empty();
      $('#variants-table-container').empty();
    }
  });

  // Generate variants
  $(document).on('click', '#generate-variants', function () {
    const selectedAttributes = $('#variant_attributes').val();

    if (!selectedAttributes || selectedAttributes.length === 0) {
      alert('Please select at least one variant attribute');
      return;
    }

    // Get attribute options
    const attributeOptions = {};
    let allOptionsPresent = true;

    selectedAttributes.forEach(attribute => {
      let options;

      if ($.fn.tagsinput) {
        options = $(`#${attribute}_options`).tagsinput('items');
      } else {
        // Fallback for custom implementation
        const inputVal = $(`#${attribute}_options`).val();
        options = inputVal
          ? inputVal
              .split(',')
              .map(o => o.trim())
              .filter(o => o)
          : [];
      }

      if (!options || options.length === 0) {
        alert(`Please add at least one option for ${capitalizeFirstLetter(attribute)}`);
        allOptionsPresent = false;
        return;
      }

      attributeOptions[attribute] = options;
    });

    if (!allOptionsPresent) return;

    // Generate all possible combinations
    const combinations = generateCombinations(attributeOptions);

    // Create variants table
    let tableHtml = `
        <table class="table table-bordered mt-4">
            <thead>
                <tr>
                    ${selectedAttributes.map(attr => `<th>${capitalizeFirstLetter(attr)}</th>`).join('')}
                    <th>SKU</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Image</th>
                </tr>
            </thead>
            <tbody>
    `;

    combinations.forEach((combination, index) => {
      tableHtml += `
            <tr>
                ${selectedAttributes
                  .map(
                    attr => `
                    <td>
                        <input type="hidden" name="variants[${index}][attributes][${attr}]" value="${combination[attr]}">
                        ${combination[attr]}
                    </td>
                `
                  )
                  .join('')}
                <td>
                    <input type="text" class="form-control form-control-sm" name="variants[${index}][sku]" placeholder="SKU">
                </td>
                <td>
                    <input type="number" class="form-control form-control-sm" name="variants[${index}][price]" step="0.01" min="0" placeholder="Price">
                </td>
                <td>
                    <input type="number" class="form-control form-control-sm" name="variants[${index}][stock]" min="0" placeholder="Stock">
                </td>
                <td>
                    <input type="file" class="form-control form-control-sm" name="variants[${index}][image]" accept="image/*">
                </td>
            </tr>
        `;
    });

    tableHtml += `
            </tbody>
        </table>
    `;

    $('#variants-table-container').html(tableHtml);
  });

  // Add/remove dynamic fields
  // Features
  if (addFeatureBtn) {
    addFeatureBtn.addEventListener('click', () => {
      const container = document.getElementById('features-container');
      const newFeature = document.createElement('div');
      newFeature.className = 'input-group mb-2';
      newFeature.innerHTML = `
        <input type="text" class="form-control" name="features[]" placeholder="Product feature">
        <button class="btn btn-outline-secondary remove-feature" type="button">
          <i class="ti ti-trash"></i>
        </button>
      `;

      container.appendChild(newFeature);

      // Add event listener to the new remove button
      newFeature.querySelector('.remove-feature').addEventListener('click', () => {
        container.removeChild(newFeature);
      });
    });

    // Handle existing remove buttons
    document.querySelectorAll('.remove-feature').forEach(button => {
      button.addEventListener('click', function () {
        this.closest('.input-group').remove();
      });
    });
  }

  // Benefits
  if (addBenefitBtn) {
    addBenefitBtn.addEventListener('click', () => {
      const container = document.getElementById('benefits-container');
      const newBenefit = document.createElement('div');
      newBenefit.className = 'input-group mb-2';
      newBenefit.innerHTML = `
        <input type="text" class="form-control" name="benefits[]" placeholder="Product benefit">
        <button class="btn btn-outline-secondary remove-benefit" type="button">
          <i class="ti ti-trash"></i>
        </button>
      `;

      container.appendChild(newBenefit);

      // Add event listener to the new remove button
      newBenefit.querySelector('.remove-benefit').addEventListener('click', () => {
        container.removeChild(newBenefit);
      });
    });

    // Handle existing remove buttons
    document.querySelectorAll('.remove-benefit').forEach(button => {
      button.addEventListener('click', function () {
        this.closest('.input-group').remove();
      });
    });
  }

  // Videos
  if (addVideoBtn) {
    addVideoBtn.addEventListener('click', () => {
      const container = document.getElementById('video-urls-container');
      const newVideo = document.createElement('div');
      newVideo.className = 'input-group mb-2';
      newVideo.innerHTML = `
        <input type="url" class="form-control" name="video_urls[]" placeholder="https://www.youtube.com/watch?v=...">
        <button class="btn btn-outline-secondary remove-video" type="button">
          <i class="ti ti-trash"></i>
        </button>
      `;

      container.appendChild(newVideo);

      // Add event listener to the new remove button
      newVideo.querySelector('.remove-video').addEventListener('click', () => {
        container.removeChild(newVideo);
      });
    });

    // Handle existing remove buttons
    document.querySelectorAll('.remove-video').forEach(button => {
      button.addEventListener('click', function () {
        this.closest('.input-group').remove();
      });
    });
  }

  // Attributes
  if (addAttributeBtn) {
    addAttributeBtn.addEventListener('click', () => {
      const container = document.getElementById('attributes-container');
      const index = container.querySelectorAll('.attribute-row').length;
      const newAttribute = document.createElement('div');
      newAttribute.className = 'row mb-2 attribute-row';
      newAttribute.innerHTML = `
        <div class="col-md-5 mt-1">
          <input type="text" class="form-control" name="attributes[${index}][name]" placeholder="Attribute name (e.g., Material)">
        </div>
        <div class="col-md-5 mt-1">
          <input type="text" class="form-control" name="attributes[${index}][value]" placeholder="Attribute value (e.g., Cotton)">
        </div>
        <div class="col-md-1 mt-1">
          <button type="button" class="btn btn-outline-danger remove-attribute">
            <i class="ti ti-trash"></i>
          </button>
        </div>
      `;

      container.appendChild(newAttribute);

      // Add event listener to the new remove button
      newAttribute.querySelector('.remove-attribute').addEventListener('click', () => {
        container.removeChild(newAttribute);
      });
    });

    // Handle existing remove buttons
    document.querySelectorAll('.remove-attribute').forEach(button => {
      button.addEventListener('click', function () {
        this.closest('.attribute-row').remove();
      });
    });
  }

  // Specifications
  if (addSpecificationBtn) {
    addSpecificationBtn.addEventListener('click', () => {
      const container = document.getElementById('specifications-container');
      const index = container.querySelectorAll('.specification-row').length;
      const newSpecification = document.createElement('div');
      newSpecification.className = 'row mb-2 specification-row';
      newSpecification.innerHTML = `
        <div class="col-md-4 mt-1">
          <input type="text" class="form-control" name="specifications[${index}][group]" placeholder="Group (e.g., Dimensions)">
        </div>
        <div class="col-md-4 mt-1">
          <input type="text" class="form-control" name="specifications[${index}][name]" placeholder="Name (e.g., Height)">
        </div>
        <div class="col-md-4 mt-1">
          <input type="text" class="form-control" name="specifications[${index}][value]" placeholder="Value (e.g., 10)">
        </div>
        <div class="col-md-4 mt-1">
          <input type="text" class="form-control" name="specifications[${index}][unit]" placeholder="Unit">
        </div>
        <div class="col-md-1">
          <button type="button" class="btn btn-outline-danger remove-specification">
            <i class="ti ti-trash"></i>
          </button>
        </div>
      `;

      container.appendChild(newSpecification);

      // Add event listener to the new remove button
      newSpecification.querySelector('.remove-specification').addEventListener('click', () => {
        container.removeChild(newSpecification);
      });
    });

    // Handle existing remove buttons
    document.querySelectorAll('.remove-specification').forEach(button => {
      button.addEventListener('click', function () {
        this.closest('.specification-row').remove();
      });
    });
  }

  // Image upload preview
  function handleImageUpload(inputId, previewId) {
    const input = document.getElementById(inputId);
    const preview = document.getElementById(previewId);

    if (input && preview) {
      input.addEventListener('change', function () {
        if (this.files && this.files[0]) {
          const reader = new FileReader();

          reader.onload = e => {
            preview.innerHTML = `<img src="${e.target.result}" class="img-fluid" style="max-height: 150px;">`;
          };

          reader.readAsDataURL(this.files[0]);
        }
      });

      preview.addEventListener('click', () => {
        input.click();
      });
    }
  }

  handleImageUpload('main_image', 'main-image-preview');
  handleImageUpload('thumbnail_image', 'thumbnail-image-preview');

  // Gallery images preview
  const galleryInput = document.getElementById('image_gallery');
  const galleryPreview = document.getElementById('gallery-preview');

  if (galleryInput && galleryPreview) {
    galleryInput.addEventListener('change', function () {
      if (this.files && this.files.length > 0) {
        galleryPreview.innerHTML = '';

        for (let i = 0; i < this.files.length; i++) {
          const reader = new FileReader();

          reader.onload = e => {
            const imgContainer = document.createElement('div');
            imgContainer.className = 'gallery-image';
            imgContainer.innerHTML = `
              <img src="${e.target.result}" class="img-fluid" style="width: 100px; height: 100px; object-fit: cover; border-radius: 4px;">
            `;
            galleryPreview.appendChild(imgContainer);
          };

          reader.readAsDataURL(this.files[i]);
        }
      }
    });

    galleryPreview.addEventListener('click', () => {
      galleryInput.click();
    });
  }

  // Document upload preview
  const documentInput = document.getElementById('document_urls');
  const documentPreview = document.getElementById('document-preview');

  if (documentInput && documentPreview) {
    documentInput.addEventListener('change', function () {
      if (this.files && this.files.length > 0) {
        documentPreview.innerHTML = '';

        for (let i = 0; i < this.files.length; i++) {
          const file = this.files[i];
          const fileExt = file.name.split('.').pop().toLowerCase();
          let iconClass = 'ti ti-file';

          if (fileExt === 'pdf') iconClass = 'ti ti-file-text';
          else if (['doc', 'docx'].includes(fileExt)) iconClass = 'ti ti-file-text';
          else if (['xls', 'xlsx'].includes(fileExt)) iconClass = 'ti ti-file-spreadsheet';

          const docElement = document.createElement('div');
          docElement.className = 'd-inline-block text-center m-2';
          docElement.innerHTML = `
            <i class="${iconClass}" style="font-size: 2rem;"></i>
            <p class="small text-truncate" style="max-width: 100px;">${file.name}</p>
          `;
          documentPreview.appendChild(docElement);
        }
      }
    });

    documentPreview.addEventListener('click', () => {
      documentInput.click();
    });
  }

  // Tab navigation
  if (prevTabBtn && nextTabBtn) {
    prevTabBtn.addEventListener('click', () => {
      const activeTab = document.querySelector('.nav-tabs .active');
      if (activeTab && activeTab.parentElement.previousElementSibling) {
        const prevTab = activeTab.parentElement.previousElementSibling.querySelector('button');
        if (prevTab) {
          const tab = new bootstrap.Tab(prevTab);
          tab.show();
        }
      }
    });

    nextTabBtn.addEventListener('click', () => {
      const activeTab = document.querySelector('.nav-tabs .active');
      if (activeTab && activeTab.parentElement.nextElementSibling) {
        const nextTab = activeTab.parentElement.nextElementSibling.querySelector('button');
        if (nextTab) {
          const tab = new bootstrap.Tab(nextTab);
          tab.show();
        }
      }
    });
  }

  // Save as draft button
  if (saveDraftBtn && statusSelect) {
    saveDraftBtn.addEventListener('click', () => {
      statusSelect.value = 'draft';
      productForm.submit();
    });
  }

  // Helper functions
  function formatDate(date) {
    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const day = String(date.getDate()).padStart(2, '0');
    return `${year}-${month}-${day}`;
  }

  function capitalizeFirstLetter(string) {
    return string.charAt(0).toUpperCase() + string.slice(1);
  }

  function generateCombinations(options) {
    const keys = Object.keys(options);
    const combinations = [];

    function generate(index, current) {
      if (index === keys.length) {
        combinations.push({ ...current });
        return;
      }

      const key = keys[index];
      const values = options[key];

      for (let i = 0; i < values.length; i++) {
        current[key] = values[i];
        generate(index + 1, current);
      }
    }

    generate(0, {});
    return combinations;
  }
});

// Add these event handlers after the existing document.ready function

// Show/hide digital and subscription tabs based on product type
$(document).change(() => {
  const productType = $('#product_type').val();

  if (productType === 'digital') {
    $('#is_digital').prop('checked', true);
    $('#digital-tab').parent().show();
    $('#is_subscription').prop('checked', false);
    $('#subscription-tab').parent().hide();
  } else if (productType === 'subscription') {
    $('#is_subscription').prop('checked', true);
    $('#subscription-tab').parent().show();
    $('#is_digital').prop('checked', false);
    $('#digital-tab').parent().hide();
  } else if (productType === 'bundle') {
    $('#digital-tab').parent().show();
    $('#subscription-tab').parent().show();
  } else {
    $('#is_digital').prop('checked', false);
    $('#is_subscription').prop('checked', false);
    $('#digital-tab').parent().hide();
    $('#subscription-tab').parent().hide();
  }
});

// Show/hide digital tab based on checkbox
$('#is_digital').change(function () {
  if (this.checked) {
    $('#digital-tab').parent().show();
  } else {
    $('#digital-tab').parent().hide();
    // If we're on the digital tab, switch to basic info
    if ($('#digital-tab').hasClass('active')) {
      $('#basic-tab').tab('show');
    }
  }
});

// Show/hide subscription tab based on checkbox
$('#is_subscription').change(function () {
  if (this.checked) {
    $('#subscription-tab').parent().show();
  } else {
    $('#subscription-tab').parent().hide();
    // If we're on the subscription tab, switch to basic info
    if ($('#subscription-tab').hasClass('active')) {
      $('#basic-tab').tab('show');
    }
  }
});

// Hide digital and subscription tabs initially
$('#digital-tab').parent().hide();
$('#subscription-tab').parent().hide();

// Handle file upload for digital products
$('#download_file').change(function () {
  if (this.files && this.files[0]) {
    const file = this.files[0];
    const fileSize = (file.size / 1024).toFixed(2) + ' KB';
    const fileType = file.type;

    $('#download_file_size').text('Size: ' + fileSize);
    $('#download_file_type').text('Type: ' + fileType);

    // Store file info in hidden fields
    $('#download_file_size_input').val(file.size);
    $('#download_file_type_input').val(fileType);
  }
});

// Custom fields
$('#add-custom-field').click(() => {
  const index = $('.custom-field-row').length;
  $('#custom-fields-container').append(`
    <div class="row mb-2 custom-field-row">
      <div class="col-md-5 mt-1">
        <input type="text" class="form-control" name="custom_fields[${index}][name]" placeholder="Field name">
      </div>
      <div class="col-md-5 mt-1">
        <input type="text" class="form-control" name="custom_fields[${index}][value]" placeholder="Field value">
      </div>
      <div class="col-md-1 mt-1">
        <button type="button" class="btn btn-outline-danger remove-custom-field">
          <i class="ti ti-trash"></i>
        </button>
      </div>
    </div>
  `);
});

$(document).on('click', '.remove-custom-field', function () {
  $(this).closest('.custom-field-row').remove();
});

// Initialize Tagify for badges and labels
if (typeof Tagify !== 'undefined') {
  new Tagify(document.getElementById('badges'));
  new Tagify(document.getElementById('labels'));
}

// Trigger product type change on load to set initial state
$(document).ready(() => {
  $('#product_type').trigger('change');
});
