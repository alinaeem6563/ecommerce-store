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
  // Initialize variables
  const bootstrap = window.bootstrap;

  // Initialize QR Code
  initQRCode();

  // Initialize Barcode
  initBarcode();

  // Initialize Swiper for product thumbnails
  initProductGallery();

  // Initialize Charts
  initCharts();

  // Initialize 3D Package Visualization
  init3DPackage();

  // Initialize Share Buttons
  initShareButtons();

  // Initialize Policy Accordion
  //initPolicyAccordion(); // Removed duplicate initialization

  // Initialize Print Functionality
  initPrintFunctionality();
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
// function initPolicyAccordion() { // Removed duplicate function declaration
//   const policyAccordion = document.getElementById("productPoliciesAccordion")

//   if (!policyAccordion) return

//   // Add hover effects to accordion buttons
//   const accordionButtons = policyAccordion.querySelectorAll(".accordion-button")

//   accordionButtons.forEach((button) => {
//     button.addEventListener("mouseenter", function () {
//       if (this.classList.contains("collapsed")) {
//         this.style.backgroundColor = "rgba(0,0,0,0.03)"
//       }
//     })

//     button.addEventListener("mouseleave", function () {
//       if (this.classList.contains("collapsed")) {
//         this.style.backgroundColor = ""
//       }
//     })
//   })

//   // Track accordion state in local storage
//   policyAccordion.addEventListener("shown.bs.collapse", (e) => {
//     try {
//       localStorage.setItem(`product_${document.querySelector('[name="product_id"]').value}_${e.target.id}`, "open")
//     } catch (error) {
//       console.error("Error saving accordion state:", error)
//     }
//   })

//   policyAccordion.addEventListener("hidden.bs.collapse", (e) => {
//     try {
//       localStorage.removeItem(`product_${document.querySelector('[name="product_id"]').value}_${e.target.id}`)
//     } catch (error) {
//       console.error("Error removing accordion state:", error)
//     }
//   })

//   // Restore accordion state from local storage
//   try {
//     const productId = document.querySelector('[name="product_id"]').value
//     const accordionItems = policyAccordion.querySelectorAll(".accordion-collapse")

//     accordionItems.forEach((item) => {
//       const isOpen = localStorage.getItem(`product_${productId}_${item.id}`) === "open"
//       if (isOpen) {
//         // Use bootstrap if available, otherwise just add the show class
//         if (typeof bootstrap !== "undefined") {
//           const accordion = new bootstrap.Collapse(item, {
//             toggle: false,
//           })
//           accordion.show()
//         } else {
//           item.classList.add("show")
//           const button = document.querySelector(`[data-bs-target="#${item.id}"]`)
//           if (button) {
//             button.classList.remove("collapsed")
//             button.setAttribute("aria-expanded", "true")
//           }
//         }
//       }
//     })
//   } catch (error) {
//     console.error("Error restoring accordion state:", error)
//   }

//   // Add smooth animation to accordion content
//   const accordionItems = policyAccordion.querySelectorAll(".accordion-collapse")

//   accordionItems.forEach((item) => {
//     item.addEventListener("show.bs.collapse", function () {
//       this.style.transition = "all 0.25s ease"
//     })

//     item.addEventListener("hide.bs.collapse", function () {
//       this.style.transition = "all 0.25s ease"
//     })
//   })

//   // Add keyboard accessibility
//   accordionButtons.forEach((button) => {
//     button.addEventListener("keydown", function (e) {
//       // Enter or Space key
//       if (e.key === "Enter" || e.key === " ") {
//         e.preventDefault()
//         this.click()
//       }
//     })
//   })

//   // Check if Bootstrap is available
//   let bootstrap
//   try {
//     bootstrap = window.bootstrap
//   } catch (error) {
//     console.warn("Bootstrap is not defined. Ensure Bootstrap is properly loaded.")
//   }
// }

/**
 * Initialize QR Code
 */
function initQRCode() {
  const qrcodeElement = document.getElementById('product-qrcode');
  if (!qrcodeElement) return;

  const productUrl = document.getElementById('share-url')?.value || window.location.href;

  // Generate QR code
  const QRCode = window.QRCode; // Access QRCode from window
  new QRCode(qrcodeElement, {
    text: productUrl,
    width: 128,
    height: 128,
    colorDark: '#000000',
    colorLight: '#ffffff',
    correctLevel: QRCode.CorrectLevel.H
  });

  // Download QR code functionality
  const downloadButton = document.getElementById('download-qrcode');
  if (downloadButton) {
    downloadButton.addEventListener('click', () => {
      const canvas = qrcodeElement.querySelector('canvas');
      if (canvas) {
        const link = document.createElement('a');
        link.download = 'product-qrcode.png';
        link.href = canvas.toDataURL('image/png');
        link.click();
      }
    });
  }
}

/**
 * Initialize Barcode
 */
function initBarcode() {
  const barcodeElement = document.getElementById('barcode-display');
  if (!barcodeElement) return;

  const barcodeValue =
    document.querySelector('[data-barcode]')?.dataset.barcode ||
    document.querySelector('.product-info-item:has(h6:contains("Barcode")) p')?.textContent.trim();

  if (barcodeValue && barcodeValue !== 'N/A') {
    const JsBarcode = window.JsBarcode; // Access JsBarcode from window
    JsBarcode(barcodeElement, barcodeValue, {
      format: 'CODE128',
      lineColor: '#000',
      width: 2,
      height: 50,
      displayValue: true,
      fontSize: 14,
      margin: 10
    });
  }
}

/**
 * Initialize Product Gallery
 */
function initProductGallery() {
  // Initialize Swiper
  const productThumbs = document.querySelector('.product-thumbs');
  if (productThumbs) {
    const Swiper = window.Swiper; // Access Swiper from window
    new Swiper(productThumbs, {
      slidesPerView: 4,
      spaceBetween: 10,
      navigation: {
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev'
      },
      breakpoints: {
        320: {
          slidesPerView: 2,
          spaceBetween: 8
        },
        480: {
          slidesPerView: 3,
          spaceBetween: 10
        },
        768: {
          slidesPerView: 4,
          spaceBetween: 10
        }
      }
    });
  }

  // Thumbnail click event
  const thumbnails = document.querySelectorAll('.swiper-slide');
  thumbnails.forEach(thumbnail => {
    thumbnail.addEventListener('click', function () {
      const imgSrc = this.dataset.img;
      const mainImage = document.getElementById('main-product-image');

      if (mainImage && imgSrc) {
        mainImage.src = imgSrc;

        // Remove active class from all thumbnails
        thumbnails.forEach(thumb => thumb.classList.remove('active'));

        // Add active class to clicked thumbnail
        this.classList.add('active');
      }
    });
  });
}

/**
 * Initialize Charts
 */
function initCharts() {
  initPriceHistoryChart();
  initInventoryHistoryChart();
  initStockGauge();
}

/**
 * Initialize Price History Chart
 */
function initPriceHistoryChart() {
  const priceHistoryElement = document.getElementById('price-history-chart');
  if (!priceHistoryElement) return;

  // Sample data - in a real application, this would come from the backend
  const priceHistoryData = [
    { x: new Date('2023-01-01').getTime(), y: 99.99 },
    { x: new Date('2023-02-01').getTime(), y: 89.99 },
    { x: new Date('2023-03-01').getTime(), y: 94.99 },
    { x: new Date('2023-04-01').getTime(), y: 94.99 },
    { x: new Date('2023-05-01').getTime(), y: 79.99 },
    { x: new Date('2023-06-01').getTime(), y: 84.99 }
  ];

  const ApexCharts = window.ApexCharts; // Access ApexCharts from window
  const options = {
    series: [
      {
        name: 'Price',
        data: priceHistoryData
      }
    ],
    chart: {
      type: 'area',
      height: 250,
      toolbar: {
        show: false
      },
      fontFamily: 'inherit'
    },
    dataLabels: {
      enabled: false
    },
    stroke: {
      curve: 'smooth',
      width: 2
    },
    xaxis: {
      type: 'datetime',
      labels: {
        format: 'MMM yyyy'
      }
    },
    yaxis: {
      labels: {
        formatter: val => '$' + val.toFixed(2)
      }
    },
    tooltip: {
      x: {
        format: 'MMM dd, yyyy'
      },
      y: {
        formatter: val => '$' + val.toFixed(2)
      }
    },
    fill: {
      type: 'gradient',
      gradient: {
        shadeIntensity: 1,
        opacityFrom: 0.7,
        opacityTo: 0.2,
        stops: [0, 90, 100]
      }
    },
    colors: ['#696cff']
  };

  const chart = new ApexCharts(priceHistoryElement, options);
  chart.render();
}

/**
 * Initialize Inventory History Chart
 */
function initInventoryHistoryChart() {
  const inventoryHistoryElement = document.getElementById('inventory-history-chart');
  if (!inventoryHistoryElement) return;

  // Sample data - in a real application, this would come from the backend
  const inventoryHistoryData = [
    { x: new Date('2023-01-01').getTime(), y: 50 },
    { x: new Date('2023-02-01').getTime(), y: 42 },
    { x: new Date('2023-03-01').getTime(), y: 35 },
    { x: new Date('2023-04-01').getTime(), y: 28 },
    { x: new Date('2023-05-01').getTime(), y: 45 },
    { x: new Date('2023-06-01').getTime(), y: 38 }
  ];

  const ApexCharts = window.ApexCharts; // Access ApexCharts from window
  const options = {
    series: [
      {
        name: 'Stock',
        data: inventoryHistoryData
      }
    ],
    chart: {
      type: 'bar',
      height: 250,
      toolbar: {
        show: false
      },
      fontFamily: 'inherit'
    },
    plotOptions: {
      bar: {
        borderRadius: 4,
        columnWidth: '60%'
      }
    },
    dataLabels: {
      enabled: false
    },
    xaxis: {
      type: 'datetime',
      labels: {
        format: 'MMM yyyy'
      }
    },
    yaxis: {
      title: {
        text: 'Units'
      }
    },
    tooltip: {
      x: {
        format: 'MMM dd, yyyy'
      },
      y: {
        formatter: val => val + ' units'
      }
    },
    colors: ['#03c3ec']
  };

  const chart = new ApexCharts(inventoryHistoryElement, options);
  chart.render();
}

/**
 * Initialize Stock Gauge
 */
function initStockGauge() {
  const stockGaugeElement = document.getElementById('stock-gauge');
  if (!stockGaugeElement) return;

  // Get stock quantity and threshold from the page
  const stockQuantity = Number.parseInt(document.querySelector('.inventory-metric .metric-value')?.textContent || '0');
  const lowStockThreshold = Number.parseInt(document.querySelector('.text-danger')?.textContent.match(/\d+/) || '10');

  // Calculate percentage
  const percentage = Math.min(Math.round((stockQuantity / (lowStockThreshold * 3)) * 100), 100);

  // Determine color based on stock level
  let color = '#ff4d49'; // danger
  if (percentage > 66) {
    color = '#71dd37'; // success
  } else if (percentage > 33) {
    color = '#ffab00'; // warning
  }

  const ApexCharts = window.ApexCharts; // Access ApexCharts from window
  const options = {
    series: [percentage],
    chart: {
      height: 200,
      type: 'radialBar',
      fontFamily: 'inherit'
    },
    plotOptions: {
      radialBar: {
        hollow: {
          size: '70%'
        },
        track: {
          background: '#e7e7e7'
        },
        dataLabels: {
          name: {
            show: false
          },
          value: {
            fontSize: '1.5rem',
            fontWeight: 600,
            formatter: val => stockQuantity + ' units'
          }
        }
      }
    },
    stroke: {
      lineCap: 'round'
    },
    colors: [color],
    labels: ['Stock Level']
  };

  const chart = new ApexCharts(stockGaugeElement, options);
  chart.render();
}

/**
 * Initialize 3D Package Visualization
 */
function init3DPackage() {
  const packageBox = document.querySelector('.package-3d-box');
  if (!packageBox) return;

  // Get dimensions from data attributes
  const length = Number.parseFloat(packageBox.dataset.length) || 10;
  const width = Number.parseFloat(packageBox.dataset.width) || 10;
  const height = Number.parseFloat(packageBox.dataset.height) || 10;

  // Calculate scale factor to fit within container
  const maxDimension = Math.max(length, width, height);
  const scaleFactor = 100 / maxDimension;

  // Apply dimensions to the 3D box
  packageBox.style.setProperty('--length', `${length * scaleFactor}px`);
  packageBox.style.setProperty('--width', `${width * scaleFactor}px`);
  packageBox.style.setProperty('--height', `${height * scaleFactor}px`);

  // Add rotation animation
  packageBox.style.animation = 'rotate3d 20s infinite linear';
}

/**
 * Initialize Share Buttons
 */
function initShareButtons() {
  const shareButtons = document.querySelectorAll('.share-btn');
  if (!shareButtons.length) return;

  const productUrl = document.getElementById('share-url')?.value || window.location.href;
  const productTitle = document.querySelector('.product-title')?.textContent || 'Check out this product';

  shareButtons.forEach(button => {
    button.addEventListener('click', function () {
      const platform = this.dataset.platform;
      let shareUrl = '';

      switch (platform) {
        case 'facebook':
          shareUrl = `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(productUrl)}`;
          break;
        case 'twitter':
          shareUrl = `https://twitter.com/intent/tweet?text=${encodeURIComponent(productTitle)}&url=${encodeURIComponent(productUrl)}`;
          break;
        case 'pinterest':
          const imageUrl = document.getElementById('main-product-image')?.src || '';
          shareUrl = `https://pinterest.com/pin/create/button/?url=${encodeURIComponent(productUrl)}&media=${encodeURIComponent(imageUrl)}&description=${encodeURIComponent(productTitle)}`;
          break;
        case 'whatsapp':
          shareUrl = `https://api.whatsapp.com/send?text=${encodeURIComponent(productTitle + ' ' + productUrl)}`;
          break;
      }

      if (shareUrl) {
        window.open(shareUrl, '_blank', 'width=600,height=400');
      }
    });
  });

  // Copy URL to clipboard
  const copyButton = document.getElementById('copy-share-url');
  const shareUrlInput = document.getElementById('share-url');

  if (copyButton && shareUrlInput) {
    copyButton.addEventListener('click', function () {
      shareUrlInput.select();
      document.execCommand('copy');

      // Show tooltip or notification
      const originalText = this.innerHTML;
      this.innerHTML = '<i class="ti ti-check"></i>';

      setTimeout(() => {
        this.innerHTML = originalText;
      }, 2000);
    });
  }
}

/**
 * Initialize Policy Accordion
 */
function initPolicyAccordion() {
  const accordion = document.getElementById('additionalInfoAccordion');
  if (!accordion) return;

  // Check if there's a stored state in localStorage
  const storedState = localStorage.getItem('productPolicyAccordion');
  if (storedState) {
    try {
      const openSections = JSON.parse(storedState);
      openSections.forEach(sectionId => {
        const section = document.getElementById(sectionId);
        if (section) {
          section.classList.add('show');
          const button = document.querySelector(`[data-bs-target="#${sectionId}"]`);
          if (button) button.classList.remove('collapsed');
        }
      });
    } catch (e) {
      console.error('Error restoring accordion state:', e);
    }
  }

  // Listen for accordion changes to save state
  accordion.addEventListener('shown.bs.collapse', e => {
    saveAccordionState();
  });

  accordion.addEventListener('hidden.bs.collapse', e => {
    saveAccordionState();
  });

  function saveAccordionState() {
    const openSections = Array.from(accordion.querySelectorAll('.accordion-collapse.show')).map(section => section.id);
    localStorage.setItem('productPolicyAccordion', JSON.stringify(openSections));
  }
}

/**
 * Initialize Print Functionality
 */
function initPrintFunctionality() {
  const printButton = document.getElementById('print-product');

  if (!printButton) {
    console.warn('Print button not found!');
    return;
  }

  printButton.addEventListener('click', () => {
    console.log('Print button clicked!');
    window.print();
  });
}

// Initialize when the DOM is ready
document.addEventListener('DOMContentLoaded', initPrintFunctionality);

