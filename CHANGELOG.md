# SmartPOS Changelog

## [Version 1.0.0] - 2026-08-14

### Initial Commercial Release
- **Core Framework**: Upgraded to Laravel 12 & PHP 8.2+.
- **Frontend**: Integrated Bootstrap 5, Chart.js analytics, Bootstrap Icons, Vanilla CSS/JS design tokens, dark/light theme switcher.
- **POS Engine**: Added real-time AJAX product search, category filtering, cart management, instant change calculation, thermal print receipt view, and PDF invoice generation.
- **Inventory Engine**: Built automated stock increment on Purchase receiving, automated stock decrement on POS sales, manual stock adjustment with audit logs, and low stock warning notifications.
- **Reports**: Implemented Sales, Purchases, Stock Valuation, Best Selling Products, and Profit & Loss Statement (`Net Profit = Revenue - COGS - Expenses`).
- **Security & Authorization**: Implemented role-based middleware (Admin vs Staff), request validation, password hashing, and CSRF protection.
- **Multi-Language Support**: Added dual-language switching feature for English (🇬🇧) and Bahasa Indonesia (🇮🇩) with topbar dropdown selector, per-session locale state, and store default setting.
- **Commercial Package**: Added step-by-step auto-installer (`/install`), sample Excel CSV template, `README.md`, `INSTALLATION.md`, `USER_GUIDE.md`, `LICENSE.txt`, and `CHANGELOG.md`.
