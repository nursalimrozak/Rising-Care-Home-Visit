# RisingCare Healthcare Booking System

Sistem booking layanan kesehatan dengan fitur membership, loyalty program, voucher, dan manajemen treatment.

## Requirements

- PHP >= 8.2
- MySQL >= 8.0
- Composer
- Node.js & NPM

## Installation

1. Clone repository
2. Copy `.env.example` to `.env`
3. Install dependencies:
```bash
composer install
npm install
```

4. Generate application key:
```bash
php artisan key:generate
```

5. Create database `risingcare` di MySQL

6. Run migrations:
```bash
php artisan migrate --seed
```

7. Create storage link:
```bash
php artisan storage:link
```

8. Build assets:
```bash
npm run build
```

9. Run development server:
```bash
php artisan serve
```

## Default Login

**Superadmin**
- Email: superadmin@risingcare.com
- Password: password

**Admin Staff**
- Email: admin@risingcare.com
- Password: password

## Features

- Landing Page dengan CMS
- Guest Booking dengan Auto-Registration
- Membership System (Gold/Silver/Bronze)
- Service Management dengan Pricing per Membership
- Treatment Add-ons dengan Stock Management
- Loyalty Points System
- Voucher System
- Payment Management
- Custom Admin Panel

## Tech Stack

- Laravel 11
- Tailwind CSS + Alpine.js
- MySQL
- AdminLTE Template
"# Rising-Care-Home-Visit" 
