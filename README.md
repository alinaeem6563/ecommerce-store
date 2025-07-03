# 🛒 Ecommerce Store – Vuexy Powered by Laravel & Vite

A robust, scalable, and feature-rich **eCommerce web application** built on top of the [Vuexy](https://pixinvent.com/demo/vuexy-html-bootstrap-admin-template/landing/) admin dashboard, Laravel backend, and Vite-powered modern frontend tooling.

This project is optimized for performance, responsive design, and modularity. With advanced UI components, real-time interactivity, rich data tables, and extensive charting—it's designed to scale for any commercial online store.

---

## 📦 Tech Stack Overview

| Tool/Library | Purpose |
|--------------|---------|
| **Laravel** | Backend Framework (MVC, API, Auth, etc.) |
| **Vite** | Next-gen frontend build tool |
| **Tailwind CSS** | Utility-first CSS styling |
| **Bootstrap 5** | Responsive component framework |
| **Alpine.js** | Lightweight JavaScript for interactivity |
| **Axios** | AJAX requests |
| **FullCalendar** | Advanced event/booking UI |
| **ApexCharts & Chart.js** | Dynamic data visualization |
| **jQuery & DataTables** | Data-rich tabular interfaces |
| **FormValidation** | Plugin-based validation framework |
| **SweetAlert2, Toastr** | Clean, responsive alert systems |
| **Swiper, Dropzone, Quill** | UI/UX enhancements (sliders, file uploaders, rich text) |

---

## ✨ Key Features

- 🧾 **Modern Admin Dashboard UI** (Vuexy-based)
- 📦 Product catalog with variations
- 👥 Customer management
- 🛍️ Shopping cart & checkout
- 💳 Integrated payment processing (extendable)
- 📅 Booking and calendar management (FullCalendar)
- 📊 Analytics dashboard (ApexCharts, Chart.js)
- 🧾 Invoice generation & order tracking
- 📁 Media/file uploads (Dropzone)
- 🛠️ Role-based access control (Admin, Seller, Customer)
- 📨 Notifications via Toastr, SweetAlert
- 🌐 Multilingual & RTL ready

---

## 🚀 Getting Started

### Prerequisites

- PHP 8.1+
- Node.js 18+
- Composer
- MySQL or compatible database
- Git

---

### Clone the Repository

```bash
git clone https://github.com/alinaeem6563/ecommerce-store.git
cd ecommerce-store
Backend Setup
bash
Copy
Edit
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
Update your .env file for DB and payment credentials.

Frontend Setup
bash
Copy
Edit
npm install
npm run dev
Or for production:

bash
Copy
Edit
npm run build
📁 Project Structure
csharp
Copy
Edit
ecommerce-store/
├── public/                  # Public web assets
├── resources/
│   ├── css/                 # Tailwind, Bootstrap overrides
│   ├── js/                  # App logic (Alpine.js, Axios, etc.)
│   ├── views/               # Blade templates
├── routes/                 # Laravel routes (web, api)
├── app/                    # Laravel application logic
├── database/               # Migrations, seeders
├── vite.config.js          # Vite configuration
└── package.json            # Frontend dependencies & scripts
📊 Notable Libraries Used
Frontend
tailwindcss, bootstrap, alpinejs, axios

sweetalert2, toastr, swiper, aos, dropzone, quill

datatables.net-bs5, select2, typeahead.js, flatpickr

chart.js, apexcharts-clevision, fullcalendar

Validation
@form-validation/core and plugins (auto-focus, bootstrap5, alias, etc.)

Dev Tools & Quality
eslint, prettier, babel, browser-sync

cross-env, glob, postcss, sass, vite

🛠️ Scripts
Script	Purpose
npm run dev	Start Vite dev server
npm run build	Build production assets
php artisan serve	Run Laravel backend server

🔒 License
Commercial license – This project is built using the Vuexy HTML Admin Template, which is under commercial license. Ensure you own a license before using it in production.

👤 Author
Ali Naeem
📧 Email: alinaeem6563@gmail.com
🌐 GitHub: github.com/alinaeem6563

🤝 Contributing
Pull requests and feature discussions are welcome. Please open an issue first to propose changes.