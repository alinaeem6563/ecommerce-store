/**
 * Home Page Scripts
 */

import Swiper from 'swiper/bundle';
import 'swiper/css/bundle';
import * as bootstrap from 'bootstrap';

document.addEventListener('DOMContentLoaded', () => {
  // Initialize Hero Slider
  const heroSwiper = new Swiper('.hero-swiper', {
    slidesPerView: 1,
    spaceBetween: 0,
    loop: true,
    autoplay: {
      delay: 5000,
      disableOnInteraction: false
    },
    pagination: {
      el: '.hero-swiper .swiper-pagination',
      clickable: true
    },
    navigation: {
      nextEl: '.hero-swiper .swiper-button-next',
      prevEl: '.hero-swiper .swiper-button-prev'
    }
  });

  // Initialize New Arrivals Slider
  const newArrivalsSwiper = new Swiper('.new-arrivals-swiper', {
    slidesPerView: 1,
    spaceBetween: 20,
    loop: true,
    autoplay: {
      delay: 4000,
      disableOnInteraction: false
    },
    pagination: {
      el: '.new-arrivals-swiper .swiper-pagination',
      clickable: true
    },
    navigation: {
      nextEl: '.new-arrivals-swiper .swiper-button-next',
      prevEl: '.new-arrivals-swiper .swiper-button-prev'
    },
    breakpoints: {
      576: {
        slidesPerView: 2
      },
      768: {
        slidesPerView: 3
      },
      992: {
        slidesPerView: 4
      }
    }
  });

  // Initialize Testimonials Slider
  const testimonialsSwiper = new Swiper('.testimonials-swiper', {
    slidesPerView: 1,
    spaceBetween: 20,
    loop: true,
    autoplay: {
      delay: 6000,
      disableOnInteraction: false
    },
    pagination: {
      el: '.testimonials-swiper .swiper-pagination',
      clickable: true
    },
    breakpoints: {
      768: {
        slidesPerView: 2
      },
      992: {
        slidesPerView: 3
      }
    }
  });

  // Initialize Brands Slider
  const brandsSwiper = new Swiper('.brands-swiper', {
    slidesPerView: 2,
    spaceBetween: 20,
    loop: true,
    autoplay: {
      delay: 3000,
      disableOnInteraction: false
    },
    breakpoints: {
      576: {
        slidesPerView: 3
      },
      768: {
        slidesPerView: 4
      },
      992: {
        slidesPerView: 6
      }
    }
  });

  // Initialize Bootstrap Tooltips
  const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
  tooltipTriggerList.map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));

  // Deal Countdown Timer
  function initCountdown() {
    // Set the date we're counting down to (7 days from now)
    const countDownDate = new Date();
    countDownDate.setDate(countDownDate.getDate() + 7);

    // Update the countdown every 1 second
    const countdownTimer = setInterval(() => {
      // Get current date and time
      const now = new Date().getTime();

      // Find the distance between now and the countdown date
      const distance = countDownDate - now;

      // Time calculations for days, hours, minutes and seconds
      const days = Math.floor(distance / (1000 * 60 * 60 * 24));
      const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
      const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
      const seconds = Math.floor((distance % (1000 * 60)) / 1000);

      // Display the result
      document.getElementById('countdown-days').innerHTML = days < 10 ? '0' + days : days;
      document.getElementById('countdown-hours').innerHTML = hours < 10 ? '0' + hours : hours;
      document.getElementById('countdown-minutes').innerHTML = minutes < 10 ? '0' + minutes : minutes;
      document.getElementById('countdown-seconds').innerHTML = seconds < 10 ? '0' + seconds : seconds;

      // If the countdown is finished, clear the interval
      if (distance < 0) {
        clearInterval(countdownTimer);
        document.getElementById('dealCountdown').innerHTML = 'EXPIRED';
      }
    }, 1000);
  }

  // Initialize countdown
  if (document.getElementById('dealCountdown')) {
    initCountdown();
  }

  // Add to Cart Animation
  const addToCartButtons = document.querySelectorAll('.btn-add-to-cart');
  addToCartButtons.forEach(button => {
    button.addEventListener('click', function (e) {
      e.preventDefault();

      // Add animation class
      this.classList.add('btn-adding');

      // Change button text
      const originalText = this.innerHTML;
      this.innerHTML = '<i class="ti ti-check"></i> Added to Cart';

      // Reset button after animation
      setTimeout(() => {
        this.classList.remove('btn-adding');
        this.innerHTML = originalText;
      }, 2000);
    });
  });

  // Wishlist Toggle
  const wishlistButtons = document.querySelectorAll('.btn-wishlist');
  wishlistButtons.forEach(button => {
    button.addEventListener('click', function () {
      this.classList.toggle('active');
      const icon = this.querySelector('i');

      if (this.classList.contains('active')) {
        icon.classList.remove('ti-heart');
        icon.classList.add('ti-heart-filled');
      } else {
        icon.classList.remove('ti-heart-filled');
        icon.classList.add('ti-heart');
      }
    });
  });

  // Newsletter Form Submission
  const newsletterForm = document.querySelector('.newsletter-form');
  if (newsletterForm) {
    newsletterForm.addEventListener('submit', function (e) {
      e.preventDefault();

      // Get email input value
      const emailInput = this.querySelector('input[type="email"]');
      const email = emailInput.value.trim();

      // Simple validation
      if (email === '') {
        alert('Please enter your email address');
        return;
      }

      // Here you would typically send the data to your server
      // For demo purposes, we'll just show a success message
      this.innerHTML = '<div class="alert alert-success">Thank you for subscribing to our newsletter!</div>';
    });
  }
});
