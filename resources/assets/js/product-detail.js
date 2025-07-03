// Product Detail Page JavaScript

document.addEventListener('DOMContentLoaded', () => {
  // Initialize image gallery
  initImageGallery();

  // Initialize quantity selector
  initQuantitySelector();

  // Initialize product variations
  initProductVariations();

  // Initialize specifications toggle
  initSpecificationsToggle();

  // Initialize wishlist functionality
  initWishlistFunctionality();

  // New initializations
  initVideoGallery();
  initDocumentDownloads();
  initProductAttributes();
  initAvailabilityCountdown();

  // New initializations
  initSocialSharing();
  initBuyNowButton();

  // Initialize badges and labels
  initBadgesAndLabels();

  // Initialize policy accordion
  initPolicyAccordion();
});

// Initialize product image gallery
function initImageGallery() {
  const thumbnails = document.querySelectorAll('.thumbnail-item');
  const mainImage = document.querySelector('.main-product-image');

  if (!thumbnails.length || !mainImage) return;

  thumbnails.forEach(thumbnail => {
    thumbnail.addEventListener('click', function () {
      // Remove active class from all thumbnails
      thumbnails.forEach(item => item.classList.remove('active'));

      // Add active class to clicked thumbnail
      this.classList.add('active');

      // Update main image
      const imgSrc = this.getAttribute('data-image') || this.querySelector('img').getAttribute('src');
      mainImage.setAttribute('src', imgSrc);
    });
  });

  // Initialize zoom effect on main image (if needed)
  if (window.innerWidth >= 992) {
    mainImage.addEventListener('mousemove', function (e) {
      const x = e.clientX - this.getBoundingClientRect().left;
      const y = e.clientY - this.getBoundingClientRect().top;

      const xPercent = Math.round(100 / (this.offsetWidth / x));
      const yPercent = Math.round(100 / (this.offsetHeight / y));

      this.style.transformOrigin = `${xPercent}% ${yPercent}%`;
    });

    mainImage.addEventListener('mouseenter', function () {
      this.style.transform = 'scale(1.5)';
    });

    mainImage.addEventListener('mouseleave', function () {
      this.style.transform = 'scale(1)';
    });
  }
}

// Initialize quantity selector
function initQuantitySelector() {
  const decrementBtn = document.getElementById('decrease-quantity');
  const incrementBtn = document.getElementById('increase-quantity');
  const quantityInput = document.querySelector('.quantity-input');

  if (!decrementBtn || !incrementBtn || !quantityInput) return;

  const maxQuantity = Number.parseInt(quantityInput.getAttribute('max')) || 100;

  decrementBtn.addEventListener('click', () => {
    const value = Number.parseInt(quantityInput.value);
    if (value > 1) {
      quantityInput.value = value - 1;
    }
  });

  incrementBtn.addEventListener('click', () => {
    const value = Number.parseInt(quantityInput.value);
    if (value < maxQuantity) {
      quantityInput.value = value + 1;
    }
  });

  quantityInput.addEventListener('change', function () {
    const value = Number.parseInt(this.value);
    if (isNaN(value) || value < 1) {
      this.value = 1;
    } else if (value > maxQuantity) {
      this.value = maxQuantity;
    }
  });
}

// Initialize product variations (color and size selectors)
function initProductVariations() {
  const colorOptions = document.querySelectorAll('.color-option');
  const sizeOptions = document.querySelectorAll('.size-option');
  const selectedColorInput = document.getElementById('selected-color');
  const selectedSizeInput = document.getElementById('selected-size');

  if (colorOptions.length) {
    colorOptions.forEach(option => {
      option.addEventListener('click', function () {
        colorOptions.forEach(opt => opt.classList.remove('active'));
        this.classList.add('active');

        if (selectedColorInput) {
          selectedColorInput.value = this.getAttribute('data-color-id');
        }
      });
    });
  }

  if (sizeOptions.length) {
    sizeOptions.forEach(option => {
      option.addEventListener('click', function () {
        sizeOptions.forEach(opt => opt.classList.remove('active'));
        this.classList.add('active');

        if (selectedSizeInput) {
          selectedSizeInput.value = this.getAttribute('data-size-id');
        }
      });
    });
  }
}

// Initialize add to cart functionality
function initAddToCart() {
  const addToCartBtn = document.querySelector('.add-to-cart-btn');
  const wishlistBtn = document.querySelector('.wishlist-btn');

  if (addToCartBtn) {
    addToCartBtn.addEventListener('click', e => {
      e.preventDefault();

      // Get selected options
      const quantity = Number.parseInt(document.querySelector('.quantity-input').value) || 1;
      const selectedColor = document.querySelector('.color-option.active')?.getAttribute('title') || null;
      const selectedSize = document.querySelector('.size-option.active')?.textContent.trim() || null;

      // Validate selections
      if (!selectedColor || !selectedSize) {
        alert('Please select color and size before adding to cart');
        return;
      }

      // Create product object
      const product = {
        id: 'TS-PC-001',
        name: 'Premium Comfort T-Shirt',
        price: 49.99,
        quantity: quantity,
        color: selectedColor,
        size: selectedSize,
        image: document.querySelector('.main-product-image').getAttribute('src')
      };

      // Add to cart (in a real application, this would likely be an API call)
      console.log('Adding to cart:', product);

      // Show success message
      const successMessage = document.createElement('div');
      successMessage.className = 'alert alert-success position-fixed top-0 start-50 translate-middle-x mt-3';
      successMessage.style.zIndex = '1050';
      successMessage.innerHTML = `
        <div class="d-flex align-items-center">
          <span>Product added to cart successfully!</span>
          <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      `;
      document.body.appendChild(successMessage);

      // Remove message after 3 seconds
      setTimeout(() => {
        successMessage.remove();
      }, 3000);
    });
  }
}

// Initialize specifications toggle
function initSpecificationsToggle() {
  const specsToggle = document.querySelector('.specs-toggle');

  if (!specsToggle) return;

  specsToggle.addEventListener('click', function () {
    const hiddenSpecs = document.querySelectorAll('.spec-hidden');
    const isExpanded = this.getAttribute('data-expanded') === 'true';

    if (isExpanded) {
      // Collapse specs
      hiddenSpecs.forEach(spec => {
        spec.style.display = 'none';
      });
      this.innerHTML = 'Show More <i class="fas fa-chevron-down ms-1"></i>';
      this.setAttribute('data-expanded', 'false');
    } else {
      // Expand specs
      hiddenSpecs.forEach(spec => {
        spec.style.display = '';
      });
      this.innerHTML = 'Show Less <i class="fas fa-chevron-up ms-1"></i>';
      this.setAttribute('data-expanded', 'true');
    }
  });
}

// Initialize wishlist functionality
function initWishlistFunctionality() {
  const wishlistButtons = document.querySelectorAll('.wishlist-btn, .wishlist-btn-small');

  if (!wishlistButtons.length) return;

  wishlistButtons.forEach(button => {
    button.addEventListener('click', function (e) {
      e.preventDefault();

      const productId = this.getAttribute('data-product-id');
      const heartIcon = this.querySelector('i.fa-heart');

      // Send AJAX request to toggle wishlist status
      fetch('/wishlist/toggle', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
          product_id: productId
        })
      })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            // Toggle heart icon
            heartIcon.classList.toggle('far');
            heartIcon.classList.toggle('fas');
            heartIcon.classList.toggle('filled');

            // Show success message
            showNotification(data.message, 'success');
          } else {
            // Show error message
            showNotification(data.message || 'An error occurred', 'error');
          }
        })
        .catch(error => {
          console.error('Error:', error);
          showNotification('An error occurred while updating wishlist', 'error');
        });
    });
  });
}

// Helper function to show notifications
function showNotification(message, type = 'success') {
  const notificationDiv = document.createElement('div');
  notificationDiv.className = `alert alert-${type === 'success' ? 'success' : 'danger'} notification-toast`;
  notificationDiv.innerHTML = `
    <div class="d-flex align-items-center">
      <span>${message}</span>
      <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  `;

  document.body.appendChild(notificationDiv);

  // Position the notification
  notificationDiv.style.position = 'fixed';
  notificationDiv.style.top = '20px';
  notificationDiv.style.right = '20px';
  notificationDiv.style.zIndex = '1050';
  notificationDiv.style.minWidth = '250px';
  notificationDiv.style.opacity = '0';
  notificationDiv.style.transform = 'translateY(-20px)';
  notificationDiv.style.transition = 'opacity 0.3s, transform 0.3s';

  // Show the notification
  setTimeout(() => {
    notificationDiv.style.opacity = '1';
    notificationDiv.style.transform = 'translateY(0)';
  }, 10);

  // Remove the notification after 5 seconds
  setTimeout(() => {
    notificationDiv.style.opacity = '0';
    notificationDiv.style.transform = 'translateY(-20px)';

    setTimeout(() => {
      notificationDiv.remove();
    }, 300);
  }, 5000);

  // Close button functionality
  const closeButton = notificationDiv.querySelector('.btn-close');
  if (closeButton) {
    closeButton.addEventListener('click', () => {
      notificationDiv.style.opacity = '0';
      notificationDiv.style.transform = 'translateY(-20px)';

      setTimeout(() => {
        notificationDiv.remove();
      }, 300);
    });
  }
}

// Initialize video gallery
function initVideoGallery() {
  const videoThumbnails = document.querySelectorAll('.video-thumbnail');

  if (!videoThumbnails.length) return;

  videoThumbnails.forEach(thumbnail => {
    thumbnail.addEventListener('click', function () {
      // If needed, implement a modal or lightbox for video viewing
      const iframe = this.querySelector('iframe');
      if (iframe) {
        const videoUrl = iframe.getAttribute('src');

        // Check if the video is from YouTube and modify URL for autoplay if needed
        if (videoUrl.includes('youtube.com') && !videoUrl.includes('autoplay=1')) {
          const updatedUrl = videoUrl.includes('?') ? `${videoUrl}&autoplay=1` : `${videoUrl}?autoplay=1`;

          // Create modal or open in new window/tab as needed
          console.log('Video URL:', updatedUrl);
        }
      }
    });
  });
}

// Initialize document downloads tracking
function initDocumentDownloads() {
  const documentLinks = document.querySelectorAll('.document-downloads .list-group-item');

  if (!documentLinks.length) return;

  documentLinks.forEach(link => {
    link.addEventListener('click', function (e) {
      // Track document downloads if needed
      const documentUrl = this.getAttribute('href');
      console.log('Document downloaded:', documentUrl);

      // If you want to implement download tracking analytics
      // sendAnalyticsEvent('document_download', { url: documentUrl });
    });
  });
}

// Initialize product attributes
function initProductAttributes() {
  const attributeCards = document.querySelectorAll('.product-attributes .card');

  if (!attributeCards.length) return;

  attributeCards.forEach(card => {
    card.addEventListener('mouseenter', function () {
      this.style.transform = 'translateY(-5px)';
    });

    card.addEventListener('mouseleave', function () {
      this.style.transform = 'translateY(0)';
    });
  });
}

// Initialize availability countdown if product has limited availability
function initAvailabilityCountdown() {
  const availableTo = document.querySelector('[data-available-to]');

  if (!availableTo) return;

  const endDate = new Date(availableTo.getAttribute('data-available-to'));
  const countdownElement = document.getElementById('availability-countdown');

  if (!countdownElement) return;

  function updateCountdown() {
    const now = new Date();
    const distance = endDate - now;

    if (distance <= 0) {
      clearInterval(countdownInterval);
      countdownElement.innerHTML = 'This offer has expired';
      return;
    }

    const days = Math.floor(distance / (1000 * 60 * 60 * 24));
    const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
    const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
    const seconds = Math.floor((distance % (1000 * 60)) / 1000);

    countdownElement.innerHTML = `${days}d ${hours}h ${minutes}m ${seconds}s`;
  }

  updateCountdown();
  const countdownInterval = setInterval(updateCountdown, 1000);
}

// Initialize social sharing
function initSocialSharing() {
  const socialButtons = document.querySelectorAll('.social-sharing a');

  if (!socialButtons.length) return;

  socialButtons.forEach(button => {
    button.addEventListener('click', function (e) {
      e.preventDefault();

      const url = this.getAttribute('href');
      const width = 600;
      const height = 400;
      const left = (window.innerWidth - width) / 2;
      const top = (window.innerHeight - height) / 2;

      // Don't open popup for email links
      if (url.startsWith('mailto:')) {
        window.location.href = url;
        return;
      }

      window.open(
        url,
        'share',
        `width=${width},height=${height},left=${left},top=${top},toolbar=0,menubar=0,location=0,status=0`
      );

      // Track social sharing if needed
      const platform = this.textContent.trim();
      console.log(`Shared on ${platform}`);
      // sendAnalyticsEvent('social_share', { platform });
    });
  });
}

// Initialize buy now button
function initBuyNowButton() {
  const buyNowBtn = document.querySelector('.buy-now-btn');

  if (!buyNowBtn) return;

  buyNowBtn.addEventListener('click', e => {
    // Get form data
    const form = document.getElementById('add-to-cart-form');
    const formData = new FormData(form);

    // Validate selections before proceeding
    const hasVariants = form.querySelector('[name="color_id"]') || form.querySelector('[name="size_id"]');

    if (hasVariants) {
      const colorId = form.querySelector('[name="color_id"]')?.value;
      const sizeId = form.querySelector('[name="size_id"]')?.value;

      if (
        (!colorId && form.querySelector('[name="color_id"]')) ||
        (!sizeId && form.querySelector('[name="size_id"]'))
      ) {
        e.preventDefault();
        showNotification('Please select all product options before proceeding', 'error');
        return;
      }
    }

    // If validation passes, the default link behavior will proceed
    console.log('Buy Now clicked - proceeding to checkout');
  });
}

// Add enhanced badge and label functionality
function initBadgesAndLabels() {
  const badges = document.querySelectorAll('.product-badges-labels .badge');

  if (badges.length) {
    badges.forEach(badge => {
      // Add hover effect
      badge.addEventListener('mouseenter', function () {
        this.style.transform = 'translateY(-2px)';
        this.style.boxShadow = '0 4px 8px rgba(0,0,0,0.1)';
        this.style.transition = 'all 0.3s ease';
      });

      badge.addEventListener('mouseleave', function () {
        this.style.transform = 'translateY(0)';
        this.style.boxShadow = '';
      });
    });
  }
}

// Initialize policy accordion functionality
function initPolicyAccordion() {
  const policyAccordion = document.getElementById('productPoliciesAccordion');

  if (!policyAccordion) return;

  // Add hover effects to accordion buttons
  const accordionButtons = policyAccordion.querySelectorAll('.accordion-button');

  accordionButtons.forEach(button => {
    button.addEventListener('mouseenter', function () {
      if (this.classList.contains('collapsed')) {
        this.style.backgroundColor = 'rgba(0,0,0,0.03)';
      }
    });

    button.addEventListener('mouseleave', function () {
      if (this.classList.contains('collapsed')) {
        this.style.backgroundColor = '';
      }
    });
  });

  // Track accordion state in local storage
  policyAccordion.addEventListener('shown.bs.collapse', e => {
    try {
      localStorage.setItem(`product_${document.querySelector('[name="product_id"]').value}_${e.target.id}`, 'open');
    } catch (error) {
      console.error('Error saving accordion state:', error);
    }
  });

  policyAccordion.addEventListener('hidden.bs.collapse', e => {
    try {
      localStorage.removeItem(`product_${document.querySelector('[name="product_id"]').value}_${e.target.id}`);
    } catch (error) {
      console.error('Error removing accordion state:', error);
    }
  });

  // Restore accordion state from local storage
  try {
    const productId = document.querySelector('[name="product_id"]').value;
    const accordionItems = policyAccordion.querySelectorAll('.accordion-collapse');

    accordionItems.forEach(item => {
      const isOpen = localStorage.getItem(`product_${productId}_${item.id}`) === 'open';
      if (isOpen) {
        // Use bootstrap if available, otherwise just add the show class
        if (typeof bootstrap !== 'undefined') {
          const accordion = new bootstrap.Collapse(item, {
            toggle: false
          });
          accordion.show();
        } else {
          item.classList.add('show');
          const button = document.querySelector(`[data-bs-target="#${item.id}"]`);
          if (button) {
            button.classList.remove('collapsed');
            button.setAttribute('aria-expanded', 'true');
          }
        }
      }
    });
  } catch (error) {
    console.error('Error restoring accordion state:', error);
  }

  // Add smooth animation to accordion content
  const accordionItems = policyAccordion.querySelectorAll('.accordion-collapse');

  accordionItems.forEach(item => {
    item.addEventListener('show.bs.collapse', function () {
      this.style.transition = 'all 0.25s ease';
    });

    item.addEventListener('hide.bs.collapse', function () {
      this.style.transition = 'all 0.25s ease';
    });
  });

  // Add keyboard accessibility
  accordionButtons.forEach(button => {
    button.addEventListener('keydown', function (e) {
      // Enter or Space key
      if (e.key === 'Enter' || e.key === ' ') {
        e.preventDefault();
        this.click();
      }
    });
  });

  // Check if Bootstrap is available
  let bootstrap;
  try {
    bootstrap = window.bootstrap;
  } catch (error) {
    console.warn('Bootstrap is not defined. Ensure Bootstrap is properly loaded.');
  }
}
