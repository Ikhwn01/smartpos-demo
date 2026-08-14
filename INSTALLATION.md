# SmartPOS Installation Guide

Thank you for purchasing **SmartPOS – All-in-One Point of Sale & Inventory System**.

This document guides you through installing SmartPOS on shared web hosting (cPanel), VPS (Nginx/Apache), or local environments (XAMPP / Laragon / Artisan).

---

## Method A: Web Installer Wizard (Recommended)

1. Upload all project files to your web server document root directory.
2. Ensure directory permissions:
   - `storage/` set to `775` (writable)
   - `bootstrap/cache/` set to `775` (writable)
3. Open your browser and navigate to:
   ```
   http://your-domain.com/install
   ```
4. Follow the 6-step installer:
   - **Step 1**: Server requirements verification.
   - **Step 2**: Database credentials configuration (Host, DB Name, User, Password).
   - **Step 3**: Database table generation & demo data seeding.
   - **Step 4**: Admin user creation.
   - **Step 5**: Store identity setup (Name, Currency, Tax).
   - **Step 6**: Installation complete!

---

## Method B: Manual CLI Installation

1. Clone or extract files into server directory:
   ```bash
   cd /path/to/smartpos
   ```
2. Install dependencies:
   ```bash
   composer install --no-dev --optimize-autoloader
   ```
3. Copy environment file:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
4. Configure database settings in `.env`:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=your_database_name
   DB_USERNAME=your_database_user
   DB_PASSWORD=your_database_password
   ```
5. Run migrations & seeders:
   ```bash
   php artisan migrate --seed
   ```
6. Link storage directory:
   ```bash
   php artisan storage:link
   ```
7. Lock installer by creating file:
   ```bash
   touch storage/installed
   ```

---

## Verification & First Login

Login using default demo credentials:
- **URL**: `http://your-domain.com/login`
- **Admin**: `admin@example.com` / `password`
- **Staff**: `staff@example.com` / `password`
