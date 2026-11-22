# 🏨 Hotel Booking Management System

> A comprehensive web-based solution designed to digitize and streamline operations for small to medium-scale hotels, eliminating manual booking processes and paper records.

Built as a group project for the **Industrial Information Technology Degree Program** at **Uva Wellassa University of Sri Lanka**, this system transforms real-world business challenges into a scalable digital solution, significantly improving operational efficiency, accuracy, and the overall customer experience.

## ✨ Key Features

*   **User Management:** Secure user registration with active email verification.
*   **Smart Booking:** Real-time room availability checking and seamless online reservations.
*   **Secure Payments:** Integrated payment processing via Stripe.
*   **Admin Console:** Comprehensive dashboard with business analytics and reporting.
*   **Customer Engagement:** Built-in reviews and ratings system for user feedback.
*   **Service Management:** Dedicated modules for managing dining, experiences, and wedding packages.
*   **Communication:** Streamlined contact and inquiry management.
*   **Automated Workflows:** Automated booking confirmations, invoice generation (mPDF), and structured refund handling.

## 🛠️ Technologies Used

**Frontend**
*   HTML5, CSS3, JavaScript
*   Bootstrap 5
*   Swiper.js

**Backend & Database**
*   PHP
*   MySQL

**Libraries & Tools**
*   **mPDF:** PDF generation for receipts and reports
*   **SendGrid:** Email delivery services
*   **Stripe:** Secure payment gateway integration
*   **AJAX:** Asynchronous data loading for a smoother UI
*   **Composer:** PHP dependency management

## 🚀 Getting Started

### Prerequisites
*   Local server environment (XAMPP, WAMP, or MAMP)
*   PHP (v7.4 or higher recommended)
*   MySQL
*   Composer installed globally


## Deploying to Vercel

This app runs on Vercel through the community [`vercel-php`](https://github.com/vercel-community/php) runtime.
`vercel.json` sends every `*.php` request to `api/index.php`, which runs the requested page the way XAMPP would.
Static files (css, js, images) are served directly.

1. **MySQL:** create a hosted MySQL database (for example Aiven or TiDB Serverless) and import `hms.sql`.
2. **Blob:** in the Vercel project, open Storage, create a **Blob** store and connect it to the project. This adds `BLOB_READ_WRITE_TOKEN`.
3. **Environment variables:** set the variables from `.env.example` in Vercel → Settings → Environment Variables. The important ones:
   - `APP_URL`: for example `https://your-app.vercel.app/`
   - `ASSET_BASE_URL`: your Blob store's public URL, for example `https://xxxx.public.blob.vercel-storage.com/`
   - `DB_HOST`, `DB_PORT`, `DB_USER`, `DB_PASS`, `DB_NAME`, and `DB_SSL=1`
   - `PAYHERE_*`. Set `PAYHERE_SANDBOX=0` for live payments, and register the domain in PayHere.
   - `SMTP_*`
4. **Copy images:** copy the existing images to Blob once by running `BLOB_READ_WRITE_TOKEN=... bash scripts/upload_assets.sh`.
5. **Deploy:** push to GitHub, or run `vercel --prod`.

Sessions are stored in the `sessions` table, and new uploads go to Vercel Blob, because Vercel's filesystem is read-only.

### Local (XAMPP)
Copy `.env.example` to `.env` and fill it in. Leave `BLOB_READ_WRITE_TOKEN` and `ASSET_BASE_URL` empty, so files are saved to `images/` and `uploads/` as before.
