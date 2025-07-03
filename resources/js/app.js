import './bootstrap';
import feather from 'feather-icons';
/*
  Add custom scripts here
*/
import.meta.glob([
  '../assets/img/**',
  // '../assets/json/**',
  '../assets/vendor/fonts/**'
]);

document.addEventListener('DOMContentLoaded', () => {
  feather.replace();
});