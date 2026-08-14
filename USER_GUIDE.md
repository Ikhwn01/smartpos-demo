# SmartPOS User Guide

Welcome to **SmartPOS System**. This guide explains how to use each feature of your POS application.

---

## 1. POS Terminal & Checkout

1. Click **POS Terminal** on the sidebar.
2. Use the search input or category tabs to locate products. You can also scan barcodes directly using a physical barcode scanner.
3. Click on a product to add it to the shopping cart.
4. Modify quantities using `+` or `-` buttons.
5. Optionally select a registered customer or leave as **Walk-in Customer**.
6. Select **Payment Method** (Cash, Bank Transfer, E-Wallet, Debit Card, Credit Card).
7. Enter the **Paid Amount**. The change amount is calculated automatically.
8. Click **PAY NOW** and confirm. After checkout, click **Print Invoice** for thermal printing or receipt download.

---

## 2. Inventory & Stock Management

- **Low Stock Warning**: Products whose stock falls below `min_stock` trigger badge notifications.
- **Stock Adjustments**: Go to **Inventory -> Stock Adjustment** to perform manual stock additions or subtractions with mandatory reason logs.
- **Supplier Purchases**: Create a **Purchase Order** to receive new stock. Product quantities are incremented automatically upon PO submission.

---

## 3. Financial Reports & Profit Calculation

Navigate to **Reports** on the sidebar:
- **Sales Report**: Filter sales by date range, cashier, customer, and payment method.
- **Purchase Report**: Summary of supplier procurement costs.
- **Inventory Valuation Report**: Breakdown of stock assets at cost price vs retail market value.
- **Profit Report**: Calculates `Net Profit = Revenue - COGS - Expenses`.

---

## 4. User Roles & Security

- **Administrators** have full privileges to modify settings, manage users, delete records, and view profit statements.
- **Staff members** can access the POS screen, view catalog items, process sales, and view their personal sales history.
