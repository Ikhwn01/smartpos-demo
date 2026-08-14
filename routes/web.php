<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\InstallController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PosController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LanguageController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - SmartPOS
|--------------------------------------------------------------------------
*/

Route::get('/lang/{locale}', [LanguageController::class, 'switch'])->name('lang.switch');

// Installer Routes
Route::prefix('install')->name('install.')->group(function () {
    Route::get('/step1', [InstallController::class, 'step1'])->name('step1');
    Route::get('/step2', [InstallController::class, 'step2'])->name('step2');
    Route::post('/step2', [InstallController::class, 'saveStep2'])->name('step2.save');
    Route::get('/step3', [InstallController::class, 'step3'])->name('step3');
    Route::post('/step3', [InstallController::class, 'runMigration'])->name('step3.run');
    Route::get('/step4', [InstallController::class, 'step4'])->name('step4');
    Route::post('/step4', [InstallController::class, 'saveStep4'])->name('step4.save');
    Route::get('/step5', [InstallController::class, 'step5'])->name('step5');
    Route::post('/step5', [InstallController::class, 'saveStep5'])->name('step5.save');
    Route::get('/step6', [InstallController::class, 'step6'])->name('step6');
});

// Auth Routes
Route::get('/demo/{role}', [AuthController::class, 'demoLogin'])->name('demo.login');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('forgot-password');
    Route::post('/forgot-password', [AuthController::class, 'handleForgotPassword']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Protected Application Routes
Route::middleware(['auth'])->group(function () {
    // Root Redirect
    Route::get('/', function () {
        return redirect()->route('dashboard');
    });

    // Profile
    Route::get('/profile', [AuthController::class, 'profile'])->name('profile');
    Route::put('/profile', [AuthController::class, 'updateProfile'])->name('profile.update');
    Route::put('/profile/password', [AuthController::class, 'changePassword'])->name('profile.password');

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/chart', [DashboardController::class, 'chartData'])->name('dashboard.chart');

    // POS Terminal
    Route::get('/pos', [PosController::class, 'index'])->name('pos.index');
    Route::get('/pos/search', [PosController::class, 'searchProducts'])->name('pos.search');
    Route::post('/pos/checkout', [PosController::class, 'checkout'])->name('pos.checkout');

    // Products
    Route::get('/products/import', [ProductController::class, 'showImport'])->name('products.import');
    Route::post('/products/import', [ProductController::class, 'import'])->name('products.import.process');
    Route::get('/products/import-template', [ProductController::class, 'downloadTemplate'])->name('products.import.template');
    Route::get('/products/export/csv', [ProductController::class, 'exportCsv'])->name('products.export.csv');
    Route::get('/products/export/pdf', [ProductController::class, 'exportPdf'])->name('products.export.pdf');
    Route::resource('products', ProductController::class);

    // Categories
    Route::resource('categories', CategoryController::class)->except(['create', 'edit', 'show']);

    // Suppliers
    Route::resource('suppliers', SupplierController::class)->except(['create', 'edit', 'show']);

    // Customers
    Route::resource('customers', CustomerController::class);

    // Purchases
    Route::resource('purchases', PurchaseController::class)->only(['index', 'create', 'store', 'show']);

    // Sales & Invoices
    Route::get('/sales', [SaleController::class, 'index'])->name('sales.index');
    Route::get('/sales/{sale}', [SaleController::class, 'show'])->name('sales.show');
    Route::get('/sales/{sale}/invoice', [SaleController::class, 'invoice'])->name('sales.invoice');
    Route::get('/sales/{sale}/print', [SaleController::class, 'print'])->name('sales.print');
    Route::get('/sales/{sale}/pdf', [SaleController::class, 'downloadPdf'])->name('sales.pdf');

    // Inventory
    Route::get('/inventory', [InventoryController::class, 'index'])->name('inventory.index');
    Route::post('/inventory/adjust', [InventoryController::class, 'adjust'])->name('inventory.adjust');
    Route::get('/inventory/history', [InventoryController::class, 'history'])->name('inventory.history');

    // Expenses
    Route::get('/expenses', [ExpenseController::class, 'index'])->name('expenses.index');
    Route::post('/expenses', [ExpenseController::class, 'store'])->name('expenses.store');
    Route::put('/expenses/{expense}', [ExpenseController::class, 'update'])->name('expenses.update');
    Route::delete('/expenses/{expense}', [ExpenseController::class, 'destroy'])->name('expenses.destroy');
    Route::post('/expenses/categories', [ExpenseController::class, 'storeCategory'])->name('expenses.categories.store');

    // Reports
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/sales', [ReportController::class, 'sales'])->name('sales');
        Route::get('/purchases', [ReportController::class, 'purchases'])->name('purchases');
        Route::get('/inventory', [ReportController::class, 'inventory'])->name('inventory');
        Route::get('/profit', [ReportController::class, 'profit'])->name('profit');
        Route::get('/best-selling', [ReportController::class, 'bestSelling'])->name('best-selling');
        Route::get('/export/{type}', [ReportController::class, 'exportCsv'])->name('export');
    });

    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{notification}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.read-all');

    // Admin Only Routes
    Route::middleware(['role:admin'])->group(function () {
        Route::resource('users', UserController::class)->except(['create', 'edit', 'show']);
        Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
        Route::post('/settings', [SettingController::class, 'update'])->name('settings.update');
    });
});
