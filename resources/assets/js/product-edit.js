// Edit Product Page JavaScript

document.addEventListener('DOMContentLoaded', () => {
  // Initialize Select2
  if (typeof $.fn.select2 !== 'undefined') {
    $('.select2').select2({
      placeholder: 'Select an option',
      allowClear: true
    });
  }

  // Initialize Rich Text Editor
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
        'body { font-family: -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif; font-size: 14px }'
    });
  }

  // Initialize Flatpickr for date pickers
  if (typeof flatpickr !== 'undefined') {
    flatpickr('.date-range-picker', {
      mode: 'range',
      dateFormat: 'Y-m-d',
      onChange: (selectedDates, dateStr, instance) => {
        if (selectedDates.length === 2) {
          const startDate = selectedDates[0];
          const endDate = selectedDates[1];

          document.getElementById('discount_start_date').value = formatDate(startDate);
          document.getElementById('discount_end_date').value = formatDate(endDate);
        }
      }
    });
  }

  // Initialize Tagify for tags input
  if (typeof Tagify !== 'undefined') {
    new Tagify(document.getElementById('tags'));
    new Tagify(document.getElementById('meta_keywords'));
    new Tagify(document.getElementById('badges'));
    new Tagify(document.getElementById('labels'));
  }

  // Tab Navigation
  const productTabs = document.getElementById('productTabs');
  const tabButtons = productTabs.querySelectorAll('.nav-link');
  const prevTabBtn = document.getElementById('prev-tab');
  const nextTabBtn = document.getElementById('next-tab');

  prevTabBtn.addEventListener('click', () => {
    const activeTab = document.querySelector('.nav-link.active');
    const activeTabIndex = Array.from(tabButtons).indexOf(activeTab);

    if (activeTabIndex > 0) {
      tabButtons[activeTabIndex - 1].click();
    }
  });

  nextTabBtn.addEventListener('click', () => {
    const activeTab = document.querySelector('.nav-link.active');
    const activeTabIndex = Array.from(tabButtons).indexOf(activeTab);

    if (activeTabIndex < tabButtons.length - 1) {
      tabButtons[activeTabIndex + 1].click();
    }
  });

  // Generate Slug
  const generateSlugBtn = document.getElementById('generate-slug');
  if (generateSlugBtn) {
    generateSlugBtn.addEventListener('click', () => {
      const productName = document.getElementById('product_name').value;
      const slug = productName
        .toLowerCase()
        .replace(/[^\w\s-]/g, '')
        .replace(/[\s_-]+/g, '-')
        .replace(/^-+|-+$/g, '');

      document.getElementById('product_slug').value = slug;
    });
  }

  // Generate SKU
  const generateSkuBtn = document.getElementById('generate-sku');
  if (generateSkuBtn) {
    generateSkuBtn.addEventListener('click', () => {
      const productName = document.getElementById('product_name').value;
      const brand = document.getElementById('brand').value;

      // Create SKU from first 3 letters of brand and product name + random number
      const brandPrefix = brand.substring(0, 3).toUpperCase();
      const productPrefix = productName.substring(0, 3).toUpperCase();
      const randomNum = Math.floor(1000 + Math.random() * 9000);

      const sku = `${brandPrefix}-${productPrefix}-${randomNum}`;
      document.getElementById('sku').value = sku;
    });
  }

  // Generate Barcode
  const generateBarcodeBtn = document.getElementById('generate-barcode');
  if (generateBarcodeBtn) {
    generateBarcodeBtn.addEventListener('click', () => {
      // Generate EAN-13 format barcode (13 digits)
      let barcode = '2'; // Start with 2 for store products

      // Add 11 random digits
      for (let i = 0; i < 11; i++) {
        barcode += Math.floor(Math.random() * 10);
      }

      // Calculate check digit
      let sum = 0;
      for (let i = 0; i < 12; i++) {
        sum += Number.parseInt(barcode[i]) * (i % 2 === 0 ? 1 : 3);
      }
      const checkDigit = (10 - (sum % 10)) % 10;

      barcode += checkDigit;
      document.getElementById('barcode').value = barcode;
    });
  }

  // Features Management
  const addFeatureBtn = document.getElementById('add-feature');
  const featuresContainer = document.getElementById('features-container');

  if (addFeatureBtn && featuresContainer) {
    addFeatureBtn.addEventListener('click', () => {
      const featureRow = document.createElement('div');
      featureRow.className = 'input-group mb-2';
      featureRow.innerHTML = `
        <input type="text" class="form-control" name="features[]" placeholder="Product feature">
        <button class="btn btn-outline-secondary remove-feature" type="button">
          <i class="ti ti-trash"></i>
        </button>
      `;
      featuresContainer.appendChild(featureRow);

      // Add event listener to the new remove button
      const removeBtn = featureRow.querySelector('.remove-feature');
      removeBtn.addEventListener('click', () => {
        featuresContainer.removeChild(featureRow);
      });
    });

    // Add event listeners to existing remove buttons
    document.querySelectorAll('.remove-feature').forEach(button => {
      button.addEventListener('click', function () {
        const featureRow = this.closest('.input-group');
        featuresContainer.removeChild(featureRow);
      });
    });
  }

  // Benefits Management
  const addBenefitBtn = document.getElementById('add-benefit');
  const benefitsContainer = document.getElementById('benefits-container');

  if (addBenefitBtn && benefitsContainer) {
    addBenefitBtn.addEventListener('click', () => {
      const benefitRow = document.createElement('div');
      benefitRow.className = 'input-group mb-2';
      benefitRow.innerHTML = `
        <input type="text" class="form-control" name="benefits[]" placeholder="Product benefit">
        <button class="btn btn-outline-secondary remove-benefit" type="button">
          <i class="ti ti-trash"></i>
        </button>
      `;
      benefitsContainer.appendChild(benefitRow);

      // Add event listener to the new remove button
      const removeBtn = benefitRow.querySelector('.remove-benefit');
      removeBtn.addEventListener('click', () => {
        benefitsContainer.removeChild(benefitRow);
      });
    });

    // Add event listeners to existing remove buttons
    document.querySelectorAll('.remove-benefit').forEach(button => {
      button.addEventListener('click', function () {
        const benefitRow = this.closest('.input-group');
        benefitsContainer.removeChild(benefitRow);
      });
    });
  }

  // Video URLs Management
  const addVideoBtn = document.getElementById('add-video');
  const videoUrlsContainer = document.getElementById('video-urls-container');

  if (addVideoBtn && videoUrlsContainer) {
    addVideoBtn.addEventListener('click', () => {
      const videoRow = document.createElement('div');
      videoRow.className = 'input-group mb-2';
      videoRow.innerHTML = `
        <input type="url" class="form-control" name="video_urls[]" placeholder="https://www.youtube.com/watch?v=...">
        <button class="btn btn-outline-secondary remove-video" type="button">
          <i class="ti ti-trash"></i>
        </button>
      `;
      videoUrlsContainer.appendChild(videoRow);

      // Add event listener to the new remove button
      const removeBtn = videoRow.querySelector('.remove-video');
      removeBtn.addEventListener('click', () => {
        videoUrlsContainer.removeChild(videoRow);
      });
    });

    // Add event listeners to existing remove buttons
    document.querySelectorAll('.remove-video').forEach(button => {
      button.addEventListener('click', function () {
        const videoRow = this.closest('.input-group');
        videoUrlsContainer.removeChild(videoRow);
      });
    });
  }

  // Attributes Management
  const addAttributeBtn = document.getElementById('add-attribute');
  const attributesContainer = document.getElementById('attributes-container');

  if (addAttributeBtn && attributesContainer) {
    addAttributeBtn.addEventListener('click', () => {
      const attributeIndex = attributesContainer.querySelectorAll('.attribute-row').length;
      const attributeRow = document.createElement('div');
      attributeRow.className = 'row mb-2 attribute-row';
      attributeRow.innerHTML = `
        <div class="col-md-5 mt-1">
          <input type="text" class="form-control" name="attributes[${attributeIndex}][name]" placeholder="Attribute name (e.g., Material)">
        </div>
        <div class="col-md-5 mt-1">
          <input type="text" class="form-control" name="attributes[${attributeIndex}][value]" placeholder="Attribute value (e.g., Cotton)">
        </div>
        <div class="col-md-1 mt-1">
          <button type="button" class="btn btn-outline-danger remove-attribute">
            <i class="ti ti-trash"></i>
          </button>
        </div>
      `;
      attributesContainer.appendChild(attributeRow);

      // Add event listener to the new remove button
      const removeBtn = attributeRow.querySelector('.remove-attribute');
      removeBtn.addEventListener('click', () => {
        attributesContainer.removeChild(attributeRow);
        updateAttributeIndices();
      });
    });

    // Add event listeners to existing remove buttons
    document.querySelectorAll('.remove-attribute').forEach(button => {
      button.addEventListener('click', function () {
        const attributeRow = this.closest('.attribute-row');
        attributesContainer.removeChild(attributeRow);
        updateAttributeIndices();
      });
    });

    // Update attribute indices after removal
    function updateAttributeIndices() {
      const attributeRows = attributesContainer.querySelectorAll('.attribute-row');
      attributeRows.forEach((row, index) => {
        const nameInput = row.querySelector('input[name^="attributes"][name$="[name]"]');
        const valueInput = row.querySelector('input[name^="attributes"][name$="[value]"]');

        nameInput.name = `attributes[${index}][name]`;
        valueInput.name = `attributes[${index}][value]`;
      });
    }
  }

  // Specifications Management
  const addSpecificationBtn = document.getElementById('add-specification');
  const specificationsContainer = document.getElementById('specifications-container');

  if (addSpecificationBtn && specificationsContainer) {
    addSpecificationBtn.addEventListener('click', () => {
      const specIndex = specificationsContainer.querySelectorAll('.specification-row').length;
      const specRow = document.createElement('div');
      specRow.className = 'row mb-2 specification-row';
      specRow.innerHTML = `
        <div class="col-md-3 mt-1">
          <input type="text" class="form-control" name="specifications[${specIndex}][group]" placeholder="Group (e.g., Dimensions)">
        </div>
        <div class="col-md-3 mt-1">
          <input type="text" class="form-control" name="specifications[${specIndex}][name]" placeholder="Name (e.g., Height)">
        </div>
        <div class="col-md-3 mt-1">
          <input type="text" class="form-control" name="specifications[${specIndex}][value]" placeholder="Value (e.g., 10)">
        </div>
        <div class="col-md-2 mt-1">
          <input type="text" class="form-control" name="specifications[${specIndex}][unit]" placeholder="Unit">
        </div>
        <div class="col-md-1 mt-1">
          <button type="button" class="btn btn-outline-danger remove-specification">
            <i class="ti ti-trash"></i>
          </button>
        </div>
      `;
      specificationsContainer.appendChild(specRow);

      // Add event listener to the new remove button
      const removeBtn = specRow.querySelector('.remove-specification');
      removeBtn.addEventListener('click', () => {
        specificationsContainer.removeChild(specRow);
        updateSpecificationIndices();
      });
    });

    // Add event listeners to existing remove buttons
    document.querySelectorAll('.remove-specification').forEach(button => {
      button.addEventListener('click', function () {
        const specRow = this.closest('.specification-row');
        specificationsContainer.removeChild(specRow);
        updateSpecificationIndices();
      });
    });

    // Update specification indices after removal
    function updateSpecificationIndices() {
      const specRows = specificationsContainer.querySelectorAll('.specification-row');
      specRows.forEach((row, index) => {
        const groupInput = row.querySelector('input[name$="[group]"]');
        const nameInput = row.querySelector('input[name$="[name]"]');
        const valueInput = row.querySelector('input[name$="[value]"]');
        const unitInput = row.querySelector('input[name$="[unit]"]');

        groupInput.name = `specifications[${index}][group]`;
        nameInput.name = `specifications[${index}][name]`;
        valueInput.name = `specifications[${index}][value]`;
        unitInput.name = `specifications[${index}][unit]`;
      });
    }
  }

  // Custom Fields Management
  const addCustomFieldBtn = document.getElementById('add-custom-field');
  const customFieldsContainer = document.getElementById('custom-fields-container');

  if (addCustomFieldBtn && customFieldsContainer) {
    addCustomFieldBtn.addEventListener('click', () => {
      const fieldIndex = customFieldsContainer.querySelectorAll('.custom-field-row').length;
      const fieldRow = document.createElement('div');
      fieldRow.className = 'row mb-2 custom-field-row';
      fieldRow.innerHTML = `
        <div class="col-md-5 mt-1">
          <input type="text" class="form-control" name="custom_fields[${fieldIndex}][name]" placeholder="Field name">
        </div>
        <div class="col-md-5 mt-1">
          <input type="text" class="form-control" name="custom_fields[${fieldIndex}][value]" placeholder="Field value">
        </div>
        <div class="col-md-1 mt-1">
          <button type="button" class="btn btn-outline-danger remove-custom-field">
            <i class="ti ti-trash"></i>
          </button>
        </div>
      `;
      customFieldsContainer.appendChild(fieldRow);

      // Add event listener to the new remove button
      const removeBtn = fieldRow.querySelector('.remove-custom-field');
      removeBtn.addEventListener('click', () => {
        customFieldsContainer.removeChild(fieldRow);
        updateCustomFieldIndices();
      });
    });

    // Add event listeners to existing remove buttons
    document.querySelectorAll('.remove-custom-field').forEach(button => {
      button.addEventListener('click', function () {
        const fieldRow = this.closest('.custom-field-row');
        customFieldsContainer.removeChild(fieldRow);
        updateCustomFieldIndices();
      });
    });

    // Update custom field indices after removal
    function updateCustomFieldIndices() {
      const fieldRows = customFieldsContainer.querySelectorAll('.custom-field-row');
      fieldRows.forEach((row, index) => {
        const nameInput = row.querySelector('input[name$="[name]"]');
        const valueInput = row.querySelector('input[name$="[value]"]');

        nameInput.name = `custom_fields[${index}][name]`;
        valueInput.name = `custom_fields[${index}][value]`;
      });
    }
  }

  // Variants Management
  const hasVariantsCheckbox = document.getElementById('has_variants');
  const variantsContainer = document.getElementById('variants-container');

  if (hasVariantsCheckbox && variantsContainer) {
    // Toggle variants container visibility
    hasVariantsCheckbox.addEventListener('change', function () {
      variantsContainer.style.display = this.checked ? 'block' : 'none';
    });

    // Variant attributes management
    const variantAttributesSelect = document.getElementById('variant_attributes');
    const variantOptionsContainer = document.getElementById('variant-options-container');

    if (variantAttributesSelect && variantOptionsContainer) {
      // When variant attributes change, update the options container
      $(variantAttributesSelect).on('change', function () {
        const selectedAttributes = $(this).val();

        // Clear existing attribute options that are no longer selected
        const existingGroups = variantOptionsContainer.querySelectorAll('.variant-option-group');
        existingGroups.forEach(group => {
          const attribute = group.getAttribute('data-attribute');
          if (!selectedAttributes.includes(attribute)) {
            variantOptionsContainer.removeChild(group);
          }
        });

        // Add new attribute options for newly selected attributes
        selectedAttributes.forEach(attribute => {
          const existingGroup = variantOptionsContainer.querySelector(
            `.variant-option-group[data-attribute="${attribute}"]`
          );
          if (!existingGroup) {
            const optionGroup = document.createElement('div');
            optionGroup.className = 'variant-option-group mb-3';
            optionGroup.setAttribute('data-attribute', attribute);
            optionGroup.innerHTML = `
              <label class="form-label text-capitalize">${attribute} Options</label>
              <div class="variant-options">
                <div class="input-group mb-2">
                  <input type="text" class="form-control" name="variant_options[${attribute}][]" placeholder="${attribute} option">
                  <button class="btn btn-outline-secondary remove-variant-option" type="button">
                    <i class="ti ti-trash"></i>
                  </button>
                </div>
              </div>
              <button type="button" class="btn btn-sm btn-outline-primary add-variant-option" data-attribute="${attribute}">
                <i class="ti ti-plus"></i> Add ${attribute.charAt(0).toUpperCase() + attribute.slice(1)} Option
              </button>
            `;
            variantOptionsContainer.appendChild(optionGroup);

            // Add event listener to the new add button
            const addBtn = optionGroup.querySelector('.add-variant-option');
            addBtn.addEventListener('click', function () {
              const attribute = this.getAttribute('data-attribute');
              const optionsContainer = this.previousElementSibling;

              const optionRow = document.createElement('div');
              optionRow.className = 'input-group mb-2';
              optionRow.innerHTML = `
                <input type="text" class="form-control" name="variant_options[${attribute}][]" placeholder="${attribute} option">
                <button class="btn btn-outline-secondary remove-variant-option" type="button">
                  <i class="ti ti-trash"></i>
                </button>
              `;
              optionsContainer.appendChild(optionRow);

              // Add event listener to the new remove button
              const removeBtn = optionRow.querySelector('.remove-variant-option');
              removeBtn.addEventListener('click', () => {
                optionsContainer.removeChild(optionRow);
              });
            });
          }
        });
      });

      // Add event listeners to existing add buttons
      document.querySelectorAll('.add-variant-option').forEach(button => {
        button.addEventListener('click', function () {
          const attribute = this.getAttribute('data-attribute');
          const optionsContainer = this.previousElementSibling;

          const optionRow = document.createElement('div');
          optionRow.className = 'input-group mb-2';
          optionRow.innerHTML = `
            <input type="text" class="form-control" name="variant_options[${attribute}][]" placeholder="${attribute} option">
            <button class="btn btn-outline-secondary remove-variant-option" type="button">
              <i class="ti ti-trash"></i>
            </button>
          `;
          optionsContainer.appendChild(optionRow);

          // Add event listener to the new remove button
          const removeBtn = optionRow.querySelector('.remove-variant-option');
          removeBtn.addEventListener('click', () => {
            optionsContainer.removeChild(optionRow);
          });
        });
      });

      // Add event listeners to existing remove buttons
      document.querySelectorAll('.remove-variant-option').forEach(button => {
        button.addEventListener('click', function () {
          const optionRow = this.closest('.input-group');
          const optionsContainer = optionRow.parentElement;
          optionsContainer.removeChild(optionRow);
        });
      });
    }

    // Generate variants
    const generateVariantsBtn = document.getElementById('generate-variants');
    const variantsTableContainer = document.getElementById('variants-table-container');

    if (generateVariantsBtn && variantsTableContainer) {
      generateVariantsBtn.addEventListener('click', () => {
        // Get selected attributes and their options
        const selectedAttributes = $(variantAttributesSelect).val();
        if (!selectedAttributes || selectedAttributes.length === 0) {
          alert('Please select at least one variant attribute.');
          return;
        }

        const variantOptions = {};
        selectedAttributes.forEach(attribute => {
          const options = [];
          const inputs = document.querySelectorAll(`input[name="variant_options[${attribute}][]"]`);
          inputs.forEach(input => {
            if (input.value.trim()) {
              options.push(input.value.trim());
            }
          });

          if (options.length === 0) {
            alert(`Please add at least one option for ${attribute}.`);
            return;
          }

          variantOptions[attribute] = options;
        });

        // Generate all possible combinations
        const combinations = generateCombinations(variantOptions, selectedAttributes);

        // Create variants table
        let tableHTML = `
          <table class="table table-bordered">
            <thead>
              <tr>
                ${selectedAttributes.map(attr => `<th class="text-capitalize">${attr}</th>`).join('')}
                <th>Image</th>
                <th>SKU</th>
                <th>Price</th>
                <th>Stock</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
        `;

        combinations.forEach((combination, index) => {
          tableHTML += `
            <tr>
              ${selectedAttributes
                .map(
                  attr => `
                <td>
                  <input type="text" class="form-control" name="variants[${index}][attributes][${attr}]" value="${combination[attr]}">
                </td>
              `
                )
                .join('')}
              <td>
                <div class="variant-image-preview">
                  <div class="no-image">No Image</div>
                  <input type="file" class="form-control variant-image-input" name="variant_images[${index}]" accept="image/*">
                </div>
              </td>
              <td>
                <input type="text" class="form-control" name="variants[${index}][sku]" value="">
              </td>
              <td>
                <input type="number" class="form-control" name="variants[${index}][price]" value="" step="0.01" min="0">
              </td>
              <td>
                <input type="number" class="form-control" name="variants[${index}][stock_quantity]" value="" min="0">
              </td>
              <td>
                <button type="button" class="btn btn-sm btn-outline-danger remove-variant">
                  <i class="ti ti-trash"></i>
                </button>
              </td>
            </tr>
          `;
        });

        tableHTML += `
            </tbody>
          </table>
        `;

        variantsTableContainer.innerHTML = tableHTML;

        // Add event listeners to remove buttons
        document.querySelectorAll('.remove-variant').forEach(button => {
          button.addEventListener('click', function () {
            const row = this.closest('tr');
            row.parentElement.removeChild(row);
            updateVariantIndices();
          });
        });

        // Add event listeners to variant image inputs
        document.querySelectorAll('.variant-image-input').forEach(input => {
          input.addEventListener('change', function (e) {
            if (this.files && this.files[0]) {
              const reader = new FileReader();
              const preview = this.closest('.variant-image-preview');
              const noImage = preview.querySelector('.no-image');

              reader.onload = e => {
                if (noImage) {
                  noImage.remove();
                }

                let img = preview.querySelector('img');
                if (!img) {
                  img = document.createElement('img');
                  img.className = 'img-thumbnail';
                  preview.insertBefore(img, preview.firstChild);
                }

                img.src = e.target.result;
              };

              reader.readAsDataURL(this.files[0]);
            }
          });
        });
      });

      // Update variant indices after removal
      function updateVariantIndices() {
        const rows = variantsTableContainer.querySelectorAll('tbody tr');
        rows.forEach((row, rowIndex) => {
          // Update attribute inputs
          const attributeInputs = row.querySelectorAll('input[name^="variants"][name*="[attributes]"]');
          attributeInputs.forEach(input => {
            const nameParts = input.name.split('[');
            const attributeName = nameParts[3].replace(']', '');
            input.name = `variants[${rowIndex}][attributes][${attributeName}]`;
          });

          // Update other inputs
          const skuInput = row.querySelector('input[name$="[sku]"]');
          const priceInput = row.querySelector('input[name$="[price]"]');
          const stockInput = row.querySelector('input[name$="[stock_quantity]"]');
          const imageInput = row.querySelector('input[name^="variant_images"]');

          if (skuInput) skuInput.name = `variants[${rowIndex}][sku]`;
          if (priceInput) priceInput.name = `variants[${rowIndex}][price]`;
          if (stockInput) stockInput.name = `variants[${rowIndex}][stock_quantity]`;
          if (imageInput) imageInput.name = `variant_images[${rowIndex}]`;
        });
      }
    }
  }

  // Image Upload Handling
  const mainImageInput = document.getElementById('main_image');
  const mainImagePreview = document.getElementById('main-image-preview');

  if (mainImageInput && mainImagePreview) {
    mainImagePreview.addEventListener('click', () => {
      mainImageInput.click();
    });

    mainImageInput.addEventListener('change', function (e) {
      if (this.files && this.files[0]) {
        const reader = new FileReader();

        reader.onload = e => {
          mainImagePreview.innerHTML = `<img src="${e.target.result}" alt="Main product image" class="img-fluid">`;
        };

        reader.readAsDataURL(this.files[0]);
      }
    });
  }

  const thumbnailImageInput = document.getElementById('thumbnail_image');
  const thumbnailImagePreview = document.getElementById('thumbnail-image-preview');

  if (thumbnailImageInput && thumbnailImagePreview) {
    thumbnailImagePreview.addEventListener('click', () => {
      thumbnailImageInput.click();
    });

    thumbnailImageInput.addEventListener('change', function (e) {
      if (this.files && this.files[0]) {
        const reader = new FileReader();

        reader.onload = e => {
          thumbnailImagePreview.innerHTML = `<img src="${e.target.result}" alt="Thumbnail image" class="img-fluid">`;
        };

        reader.readAsDataURL(this.files[0]);
      }
    });
  }

  const galleryInput = document.getElementById('image_gallery');
  const galleryPreview = document.getElementById('gallery-preview');

  if (galleryInput && galleryPreview) {
    const galleryPlaceholder = galleryPreview.querySelector('.gallery-upload-placeholder');

    if (galleryPlaceholder) {
      galleryPlaceholder.addEventListener('click', () => {
        galleryInput.click();
      });
    }

    galleryInput.addEventListener('change', function (e) {
      if (this.files && this.files.length > 0) {
        for (let i = 0; i < this.files.length; i++) {
          const file = this.files[i];
          const reader = new FileReader();

          reader.onload = e => {
            const galleryItem = document.createElement('div');
            galleryItem.className = 'gallery-item';
            galleryItem.innerHTML = `
              <img src="${e.target.result}" alt="Gallery image" class="img-fluid">
              <div class="gallery-item-actions">
                <button type="button" class="btn btn-sm btn-danger remove-gallery-item">
                  <i class="ti ti-trash"></i>
                </button>
              </div>
            `;

            // Insert before placeholder
            if (galleryPlaceholder) {
              galleryPreview.insertBefore(galleryItem, galleryPlaceholder);
            } else {
              galleryPreview.appendChild(galleryItem);
            }

            // Add event listener to remove button
            const removeBtn = galleryItem.querySelector('.remove-gallery-item');
            removeBtn.addEventListener('click', () => {
              galleryPreview.removeChild(galleryItem);
            });
          };

          reader.readAsDataURL(file);
        }
      }
    });

    // Add event listeners to existing remove buttons
    document.querySelectorAll('.remove-gallery-item').forEach(button => {
      button.addEventListener('click', function () {
        const galleryItem = this.closest('.gallery-item');
        galleryPreview.removeChild(galleryItem);
      });
    });
  }

  // Document Upload Handling
  const documentInput = document.getElementById('document_urls');
  const documentPreview = document.getElementById('document-preview');

  if (documentInput && documentPreview) {
    documentPreview.addEventListener('click', () => {
      documentInput.click();
    });

    documentInput.addEventListener('change', function (e) {
      if (this.files && this.files.length > 0) {
        // If there's existing content, clear it except for the existing documents section
        const existingDocuments = documentPreview.querySelector('.existing-documents');
        if (!existingDocuments) {
          documentPreview.innerHTML = '';
        }

        // Create or update the file list
        let fileList = documentPreview.querySelector('.new-documents');
        if (!fileList) {
          fileList = document.createElement('div');
          fileList.className = 'new-documents mt-3';
          fileList.innerHTML = '<h6>New Documents</h6><ul class="list-group"></ul>';
          documentPreview.appendChild(fileList);
        }

        const listGroup = fileList.querySelector('.list-group');

        for (let i = 0; i < this.files.length; i++) {
          const file = this.files[i];
          const listItem = document.createElement('li');
          listItem.className = 'list-group-item d-flex justify-content-between align-items-center';
          listItem.innerHTML = `
            <span>${file.name}</span>
            <span class="badge bg-primary rounded-pill">${formatFileSize(file.size)}</span>
          `;
          listGroup.appendChild(listItem);
        }
      }
    });
  }

  // Delete Product Handling
  const deleteProductBtn = document.getElementById('delete-product');
  const deleteModal = document.getElementById('deleteModal');

  if (deleteProductBtn && deleteModal) {
    deleteProductBtn.addEventListener('click', () => {
      const modal = new bootstrap.Modal(deleteModal);
      modal.show();
    });
  }

  // Helper Functions
  function formatDate(date) {
    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const day = String(date.getDate()).padStart(2, '0');
    return `${year}-${month}-${day}`;
  }

  function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return Number.parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
  }

  function generateCombinations(options, attributes) {
    const combinations = [];

    function generateHelper(current, attributeIndex) {
      if (attributeIndex === attributes.length) {
        combinations.push({ ...current });
        return;
      }

      const attribute = attributes[attributeIndex];
      const values = options[attribute];

      for (let i = 0; i < values.length; i++) {
        current[attribute] = values[i];
        generateHelper(current, attributeIndex + 1);
      }
    }

    generateHelper({}, 0);
    return combinations;
  }
});
