<div align="center">

# 🏨 Hotel Booking Management System

**A complete web platform for small and medium-sized hotels: online room booking, payments, dining, experiences, weddings, and a full admin console.**

[![Live Demo](https://img.shields.io/badge/demo-live-2ec1ac?style=for-the-badge&logo=vercel&logoColor=white)](https://hms-tan-six.vercel.app)
[![License: MIT](https://img.shields.io/badge/license-MIT-blue?style=for-the-badge)](LICENSE)

![PHP](https://img.shields.io/badge/PHP-8.3-777BB4?logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-8-4479A1?logo=mysql&logoColor=white)
![Bootstrap](https://img.shields.io/badge/Bootstrap-5-7952B3?logo=bootstrap&logoColor=white)
![JavaScript](https://img.shields.io/badge/JavaScript-ES6-F7DF1E?logo=javascript&logoColor=black)
![Vercel](https://img.shields.io/badge/Deployed%20on-Vercel-000000?logo=vercel&logoColor=white)

<img src="images/carousel/IMG_62468.jpg" alt="Hotel Booking Management System" width="100%">

[Live Demo](https://hms-tan-six.vercel.app) · [Features](#-features) · [Getting Started](#-getting-started) · [Deployment](#-deploying-to-vercel) · [Report a Bug](https://github.com/Pubudugunawardhana/HMS/issues)

</div>

---

## 📖 About

Many small hotels still take bookings by phone and keep records on paper. This system puts the whole guest journey online, from checking availability to paying and leaving a review. It also gives hotel staff one dashboard to manage rooms, bookings, refunds and services.

It was built as a group project for the **Industrial Information Technology** degree program at **Uva Wellassa University of Sri Lanka**.

## ✨ Features

### 👤 For guests
| | |
|---|---|
| 🔐 **Accounts** | Register, log in, edit your profile and photo, and reset a forgotten password by email |
| 🛏️ **Room booking** | Check availability live by date and guest count, filter by facilities, and pick a meal plan |
| 💳 **Online payments** | Pay in full, pay half, or pay at the hotel, through the **PayHere** gateway. Every payment is checked against PayHere's signature |
| 🧾 **Receipts** | Download a PDF receipt for each booking |
| ⭐ **Reviews** | Rate and review rooms after your stay |
| 🍽️ **Dining & experiences** | Browse restaurants, menus, tours and activities |
| 💍 **Weddings** | View wedding halls and their packages, and send an inquiry |

### 🛠️ For hotel admins
| | |
|---|---|
| 📊 **Dashboard** | Booking, revenue and user statistics for the last 30 days, 90 days, 1 year or all time |
| 📅 **Bookings** | Assign rooms to new bookings, see booking records, handle refunds, and export PDFs |
| 🏠 **Rooms** | Manage rooms, images, features, facilities and per-room meal plan prices |
| 🍷 **Services** | Manage dining options, experiences, wedding halls, inquiries and equipment |
| 👥 **Users & queries** | Manage users, read contact messages, and moderate reviews |
| ⚙️ **Site settings** | Edit the site title, About text, contact details and carousel, and close bookings temporarily |

## 🧰 Tech Stack

| Layer | Technology |
|---|---|
| **Frontend** | HTML5, CSS3, JavaScript (AJAX), Bootstrap 5, Swiper.js |
| **Backend** | PHP 8 (mysqli with prepared statements) |
| **Database** | MySQL 8, or any MySQL-compatible database such as TiDB |
| **Payments** | PayHere |
| **Email** | PHPMailer over SMTP |
| **PDF** | mPDF |
| **Hosting** | Vercel (the `vercel-php` runtime) with Vercel Blob for uploaded files |

## 📁 Project Structure

```
HMS/
├── admin/              # Admin panel
│   ├── ajax/           # Admin AJAX endpoints
│   ├── classes/        # Business logic (RoomManager, BookingManager, ...)
│   ├── inc/            # DB config, helpers, mPDF
│   └── scripts/        # Admin JavaScript
├── ajax/               # Public AJAX endpoints (login, booking, profile, ...)
├── api/index.php       # Vercel front controller that runs the requested PHP page
├── inc/                # Shared header/footer, Mailer, PayHere, Storage
├── images/ uploads/    # Media files (served from Vercel Blob in production)
├── *.php               # Public pages (index, rooms, bookings, pay_now, ...)
├── bootstrap.php       # Loads env settings and stores sessions in the database
├── hms.sql             # Database schema and sample data
├── vercel.json         # Vercel routing and runtime settings
└── .env.example        # Every configuration option
```

## 🚀 Getting Started

### Prerequisites
- [XAMPP](https://www.apachefriends.org/) (or any Apache + PHP 8.1+ + MySQL setup)
- PHP extensions: `mysqli`, `gd`, `mbstring`, `curl`, `openssl`

### Run it locally

```bash
# 1. Clone into your web root
cd C:/xampp/htdocs
git clone https://github.com/Pubudugunawardhana/HMS.git hms

# 2. Create your config file
cd hms
cp .env.example .env
```

3. Open **phpMyAdmin**, create a database named `hms`, and import `hms.sql`.
4. Edit `.env` with your database details. Leave `BLOB_READ_WRITE_TOKEN` and `ASSET_BASE_URL` empty so uploads are saved to disk.
5. Visit **http://127.0.0.1/hms/** for the site and **http://127.0.0.1/hms/admin/** for the admin panel.

> [!IMPORTANT]
> Change the default admin password as soon as you have imported `hms.sql`.

## ☁️ Deploying to Vercel

Vercel has no built-in PHP support, so this project uses the community [`vercel-php`](https://github.com/vercel-community/php) runtime. Every `*.php` request goes to `api/index.php`, which runs the page the same way Apache would. CSS, JS and images are served as static files.

1. **Database:** create a hosted MySQL database. [TiDB Cloud Starter](https://tidbcloud.com) has a free tier. Then import `hms.sql`.
2. **File storage:** in your Vercel project, open **Storage**, create a **Blob** store and connect it. This sets `BLOB_READ_WRITE_TOKEN` for you.
3. **Environment variables:** add the variables from [`.env.example`](.env.example) under **Settings → Environment Variables**.
4. **Existing images:** upload them to Blob once:
   ```bash
   BLOB_READ_WRITE_TOKEN=vercel_blob_rw_xxx bash scripts/upload_assets.sh
   ```
5. **Deploy:** push to `main`. Vercel builds and deploys automatically.

> [!NOTE]
> Vercel's filesystem is read-only and functions are stateless, so sessions are kept in the `sessions` database table and new uploads go to Vercel Blob.

### Environment variables

| Variable | Description |
|---|---|
| `APP_URL` | Public site URL with a trailing `/` |
| `ASSET_BASE_URL` | Public URL of your Vercel Blob store (leave empty when running locally) |
| `BLOB_READ_WRITE_TOKEN` | Vercel Blob token (leave empty when running locally) |
| `DB_HOST` `DB_PORT` `DB_USER` `DB_PASS` `DB_NAME` | MySQL connection details |
| `DB_SSL` | `1` for hosted databases that require TLS |
| `PAYHERE_MERCHANT_ID` `PAYHERE_MERCHANT_SECRET` | PayHere credentials |
| `PAYHERE_CURRENCY` | For example `LKR` |
| `PAYHERE_SANDBOX` | `1` for sandbox testing, `0` for live payments |
| `SMTP_HOST` `SMTP_PORT` `SMTP_USER` `SMTP_PASS` `SMTP_SECURE` | SMTP mail server settings |
| `MAIL_FROM` `MAIL_FROM_NAME` | Sender address and name for outgoing email |

## 🔒 Security

- Database queries use prepared statements.
- Passwords are hashed with bcrypt.
- Every PayHere payment notification is checked against the merchant's signature.
- Configuration files, classes and the SQL dump can't be opened from the web.

To report a vulnerability, see [SECURITY.md](SECURITY.md).

## 📄 License

Released under the [MIT License](LICENSE).

<div align="center">

Made with ❤️ in Sri Lanka 🇱🇰

</div>
