# SmartPOS – All-in-One Point of Sale & Inventory Management System

SmartPOS is a commercial-grade PHP & Laravel Point of Sale (POS) and Inventory Management System suitable for minimarkets, retail stores, fashion boutiques, grocery shops, electronics stores, and small businesses.

Designed specifically for digital marketplaces (such as Codester, CodeCanyon, and Envato Market), SmartPOS offers clean architecture, modular service layers, step-by-step interactive web installer wizard, dark/light theme options, and comprehensive commercial documentation.

---

## Key Features & Highlights

- **Modern Responsive UI/UX**: Built on Bootstrap 5 with dark/light mode toggle, dynamic Chart.js analytics, and glassmorphism styling.
- **Interactive POS Terminal**: Fast AJAX product search by name, SKU, or barcode scanner, real-time cart calculations (discount, tax, subtotal, change return), checkout modal, thermal print receipt, and PDF invoice generation.
- **Auto Stock Management**:
  - Purchases: Purchase orders auto-increment product stock.
  - POS Sales: Checkout automatically reduces stock and logs audit history.
  - Inventory Adjustment: Manual Stock In / Stock Out with mandatory reason logging.
  - Notifications: Low stock and out-of-stock badge alerts.
- **Full Commercial Modules**:
  - Products & Categories (with delete safety checks)
  - Supplier & Customer Directory (with purchase history tracking)
  - Expense Tracker & Profit & Loss Statement (`Net Profit = Revenue - COGS - Expenses`)
  - Financial & Inventory Reports (Sales, Purchases, Inventory Valuation, Top Sellers)
  - Excel/CSV Bulk Import & Export (CSV, PDF, Print)
- **Role-Based Access Control (RBAC)**:
  - **Administrator**: Complete system access including settings, user management, and financial reports.
  - **Staff/Cashier**: POS checkout, viewing products, customers, personal transactions. Access to system settings and user management restricted via middleware.
- **Web Auto-Installer Wizard**: Step-by-step setup (`/install`) for server requirements check, database configuration, database migrations & seeders, admin account creation, and store settings.

---

## Server Requirements

- **PHP Version**: 8.2 or higher
- **Extensions**: `pdo_mysql` or `pdo_sqlite`, `mbstring`, `openssl`, `json`, `xml`, `ctype`, `curl`, `gd`
- **Database**: MySQL 5.7+ / MariaDB 10.2+ or SQLite 3.35+
- **Composer**: 2.0+

---

## Quick Installation & Setup

1. **Extract Project Files** into your web server document root (e.g. `c:/project jualan` or `public_html`).
2. **Install Composer Dependencies**:
   ```bash
   composer install
   ```
3. **Environment Setup**:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
4. **Run Migrations & Demo Seeders**:
   ```bash
   php artisan migrate --seed
   ```
5. **Start Local Development Server**:
   ```bash
   php artisan serve
   ```
6. **Access Application**:
   Open browser at `http://127.0.0.1:8000`.

Alternatively, launch the Web Setup Wizard by visiting `http://localhost:8000/install`.

---

## Default Demo Login Accounts

| Role | Email Address | Password |
| :--- | :--- | :--- |
| **Administrator** | `admin@example.com` | `password` |
| **Staff / Cashier** | `staff@example.com` | `password` |

> **Important**: Change these default passwords immediately after initial installation.

---

## Bulk Product Import (Excel / CSV)

1. Navigate to **Products -> Import Excel**.
2. Click **Download CSV Template** (`products_import_template.csv`).
3. Fill in product details matching columns: `Product Code, Barcode, Product Name, Category, Supplier, Purchase Price, Selling Price, Stock, Min Stock, Unit, Description`.
4. Upload the CSV file to complete bulk catalog creation.

---

## Frequently Asked Questions (FAQ) & Troubleshooting

### Q: How do I change store identity and currency symbol?
Go to **Store Settings** (Admin access required). Update Store Name, Address, Phone, Email, Logo, Currency Symbol (e.g. `$`, `Rp`, `€`), Tax Rate (%), and Invoice Prefix.

### Q: Why is stock decreasing automatically?
When a sale checkout is completed in the POS terminal, product stock is automatically decremented and an `inventory_transactions` record is logged for auditing.

### Q: How is Net Profit calculated?
Net Profit is computed as:
$$\text{Net Profit} = \text{Gross Revenue} - \text{COGS (Cost of Goods Sold)} - \text{Expenses}$$

---

## License

This software is released under a **Commercial Software License**. You may customize it for client projects or sell it as a ready-made commercial script according to your marketplace license terms.
