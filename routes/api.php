<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\GuestPassController;
use App\Http\Controllers\User\ShopController as UserShopController;
use App\Http\Controllers\User\OrderController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\User\BookingController;
use App\Http\Controllers\User\DocumentController;
use App\Http\Controllers\Admin\LocationController;
use App\Http\Controllers\Admin\TemplateController;
use App\Http\Controllers\Admin\ClassListController;
use App\Http\Controllers\Admin\InstructorController;
use App\Http\Controllers\Admin\AnnouncementController;
use App\Http\Controllers\Admin\WeekScheduleController;
use App\Http\Controllers\Admin\Shop\ProductController;
use App\Http\Controllers\Admin\WeekTemplateController;
use App\Http\Controllers\Admin\ClassScheduleController;
use App\Http\Controllers\Admin\Shop\LocationController as ShopLocationController;
use App\Http\Controllers\Admin\Shop\OrderController as ShopOrderController;
use App\Http\Controllers\Admin\Shop\Product\AttributeController;
use App\Http\Controllers\Admin\Shop\Product\CategoryController;
use App\Http\Controllers\Admin\Shop\Product\CollectionController;
use App\Http\Controllers\Admin\Shop\Product\InventoryController;
use App\Http\Controllers\Admin\Shop\Product\OptionController;
use App\Http\Controllers\Admin\Shop\Product\TagController;
use App\Http\Controllers\Admin\Shop\Product\VariantController;
use App\Http\Controllers\Admin\Shop\Product\VendorController;
use App\Http\Controllers\GateCloudController;
use App\Http\Controllers\Admin\Shop\CustomerController;
use App\Http\Controllers\Admin\TaxController;
use App\Http\Controllers\ShopController;
use Coderstm\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\User\ClassScheduleController as UserClassScheduleController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Auth Routes
Route::prefix('auth/{guard?}')->group(function () {
    Route::controller(AuthController::class)->group(function () {
        Route::post('signup', 'signup')->name('users.signup');
        Route::post('login', 'login')->name('users.login');
        Route::middleware('auth:sanctum')->group(function () {
            Route::post('logout', 'logout')->name('users.logout');
            Route::post('update', 'update')->name('users.update');
            Route::post('change-password', 'password')->name('users.change-password');
            Route::post('me', 'me')->name('users.current');
            Route::post('update-parq', 'updateParq')->name('users.update-parq');
        });
    });
    Route::controller(ForgotPasswordController::class)->group(function () {
        Route::post('request-password', 'request')->name('users.request-password');
        Route::post('reset-password', 'reset')->name('users.reset-password');
    });
});

Route::controller(ShopController::class)->group(function () {
    Route::post('orders/calculator', 'calculator')->name('orders.calculator');
    Route::post('orders/{payment_method}/checkout', 'checkout')->name('orders.checkout');
    Route::post('orders/{order}/{payment_method}/pay', 'pay')->name('orders.pay');
    Route::get('shop/cart', 'cart')->name('orders.cart');
});

// File Download
// Route::get('files/{path}', [FileController::class, 'download'])->name('files.download');

// Common Routes
Route::middleware(['auth:sanctum'])->group(function () {
    // Options
    Route::get('users/options', [UserController::class, 'options']);
    Route::get('class-lists/options', [ClassListController::class, 'options']);
    Route::get('locations/options', [LocationController::class, 'options']);
    Route::get('instructors/options', [InstructorController::class, 'options']);

    //User Schedules
    Route::middleware('can:view,user')->group(function () {
        Route::get('users/{user}/schedules', [UserController::class, 'schedules'])->name('users.schedules');
        Route::get('users/{user}/schedules-calendar', [UserController::class, 'schedulesCalendar'])->name('users.schedules-calendar');
    });

    // Guest Passes
    Route::resource('guest-passes', GuestPassController::class)->only('index');
    Route::resource('guest-passes', GuestPassController::class)->except('index')->middleware(['subscribed']);
});

// Admin Routes
Route::group(['middleware' => ['auth:sanctum', 'guard:admins']], function () {
    // Finance
    Route::group(['prefix' => 'finance', 'as' => 'finance.'], function () {
        Route::get('memberships', [UserController::class, 'financeMemberships'])->can('membership,App\Models\User')->name('memberships');
    });

    // Users
    Route::group(['prefix' => 'users', 'as' => 'users.'], function () {
        Route::group(['controller' => ReportController::class], function () {
            Route::get('reports/daily', 'reportsDaily')->can('reportsDaily,App\Models\User')->name('reports-daily');
            Route::get('reports/monthly', 'reportsMonthly')->can('reportsMonthly,App\Models\User')->name('reports-monthly');
            Route::get('reports/yearly', 'reportsYearly')->can('reportsYearly,App\Models\User')->name('reports-yearly');
            Route::post('reports/pdf', 'pdf')->can('reportsDaily,App\Models\User')->name('reports-pdf');
        });
        Route::group(['controller' => UserController::class], function () {
            Route::post('list-by-ids', 'listByIds')->name('list-by-ids');
            Route::get('show-by-qrcode/{qrcode}', 'showByQrcode')->name('show-by-qrcode');
            Route::get('enquiry', 'enquiry')->can('enquiry,App\Models\User')->name('enquiry');
            Route::post('{user}/update-parq', 'updateParq')->can('updateParq,user')->name('update-parq');
            Route::group(['middleware' => 'can:update,user'], function () {
                Route::post('{user}/change-block', 'changeBlock')->name('change-block');
                Route::post('{user}/request-avatar', 'requestAvatar')->name('request-avatar');
                Route::post('{user}/request-parq', 'requestParq')->name('request-parq');
                Route::post('{user}/mark-as-paid', 'markAsPaid')->name('mark-as-paid');
            });
            Route::post('{user}/checked', [UserController::class, 'checked'])->can('admin,user')->name('checked');
        });
    });
    Route::resource('users', UserController::class);

    // Classes
    Route::controller(ClassListController::class)->prefix('class-lists')->middleware('can:update,classList')->group(function () {
        Route::post('{classList}/change-active', 'changeActive')->name('class-lists.change-active');
        Route::post('{classList}/change-has-description', 'changeHasDescription')->name('class-lists.change-has-description');
    });
    Route::resource('class-lists', ClassListController::class);

    // Class Schedules
    Route::controller(ClassScheduleController::class)->prefix('class-schedules')->middleware('can:update,App\Models\ClassSchedule')->group(function () {
        Route::get('weekly', 'index')->name('class-schedules.weekly');
        Route::post('weekly/update', 'store')->name('class-schedules.weekly-update');
    });
    Route::controller(WeekScheduleController::class)->group(function () {
        Route::get('class-schedules/calendar', 'calendar')->can('viewAny,App\Models\ClassSchedule')->name('class-schedules.calendar');
        Route::get('class-schedules/list-pdf', 'listPdf')->can('viewAny,App\Models\ClassSchedule')->name('class-schedules.list-pdf');
        Route::get('class-schedules/{classSchedule}/pdf', 'pdf')->name('class-schedules.pdf');
        Route::post('class-schedules/{classSchedule}/change-sign-off', 'changeSignOff')->name('class-schedules.change-sign-off');
        Route::post('class-schedules/{classSchedule}/logs', 'logs')->name('class-schedules.logs');
    });
    Route::resource('class-schedules', WeekScheduleController::class)->only([
        'index', 'show', 'update'
    ]);

    // Week Template
    Route::resource('week-templates', WeekTemplateController::class)->only([
        'index', 'store'
    ]);

    // Locations
    Route::middleware('can:update,location')->group(function () {
        Route::post('locations/{location}/change-active', [LocationController::class, 'changeActive'])->name('locations.change-active');
    });
    Route::resource('locations', LocationController::class);

    // Templates
    Route::controller(TemplateController::class)->prefix('templates')->middleware('can:update,template')->group(function () {
        Route::post('{template}/change-active', 'changeActive')->name('templates.change-active');
        Route::post('{template}/duplicate', 'duplicate')->name('templates.duplicate');
    });
    Route::resource('templates', TemplateController::class);

    // Instructors
    Route::resource('instructors', InstructorController::class);

    // Announcements
    Route::resource('announcements', AnnouncementController::class);

    // Products
    Route::get('products/bar-code/{barcode}', [ProductController::class, 'findByBarCode']);
    Route::prefix('products/{product}')->group(function () {
        Route::resource('variants', VariantController::class)->only([
            'index', 'store',
        ]);
    });
    Route::resource('products', ProductController::class);

    // Variants
    Route::resource('variants', VariantController::class)->only([
        'update', 'destroy', 'show', 'restore',
    ]);

    // Inventory
    Route::resource('inventories', InventoryController::class)->only([
        'index', 'update',
    ]);

    // Vendors
    Route::resource('vendors', VendorController::class)->only([
        'index', 'store', 'show',
    ]);

    // Categories
    Route::resource('categories', CategoryController::class);

    // Tags
    Route::resource('tags', TagController::class)->only([
        'index', 'store', 'show',
    ]);

    // Collections
    Route::post('collections/{collection}/add-products', [CollectionController::class, 'addProducts'])->name('collections.add-products');
    Route::resource('collections', CollectionController::class);

    // Attributes
    Route::resource('attributes', AttributeController::class);

    // Options
    Route::resource('options', OptionController::class)->only([
        'index', 'show', 'update', 'destroy'
    ]);

    // Taxes
    Route::resource('taxes', TaxController::class)->only([
        'index', 'store', 'update', 'destroy'
    ]);

    // Locations
    Route::resource('shop/locations', ShopLocationController::class)->only([
        'index', 'store', 'update', 'destroy'
    ]);

    // Orders
    Route::controller(ShopOrderController::class)->group(function () {
        Route::get('orders/{order}/receipt', 'receipt')->name('orders.receipt');
        Route::get('orders/{order}/pos', 'pos')->name('orders.pos');
        Route::post('orders/{order}/logs', 'logs')->name('orders.logs.create');
        Route::post('orders/{order}/mark-as-paid', 'markAsPaid')->name('orders.mark-as-paid');
        Route::post('orders/{order}/duplicate', 'duplicate')->name('orders.duplicate');
        Route::post('orders/{order}/cancel', 'cancel')->name('orders.cancel');
    });
    Route::resource('orders', ShopOrderController::class);
    Route::resource('customers', CustomerController::class)->only(['show', 'update']);
});

// User Routes
Route::prefix('member')->middleware(['auth:sanctum', 'guard:users'])->group(function () {
    // Documents
    Route::resource('documents', DocumentController::class)->only([
        'show', 'index'
    ]);

    // Shop
    Route::controller(UserShopController::class)->group(function () {
        Route::get('shop/products', 'products')->name('shop.products');
        Route::get('shop/products/{slug}', 'product')->name('shop.products.show');
    });
    Route::resource('shop/orders', OrderController::class);

    // Class Schedules
    Route::post('bookings/{booking}/cancel', [BookingController::class, 'cancel'])->name('bookings.cancel');
    Route::controller(UserClassScheduleController::class)->group(function () {
        Route::get('class-schedules', 'index')->name('class-schedules.index');
        Route::post('class-schedules/{classSchedule}', 'book')->middleware(['subscribed'])->name('class-schedules.book');
    });
});

// Public Route
Route::group(['prefix' => 'shared'], function () {
    Route::get('class-schedules', [ClassScheduleController::class, 'list'])->name('class-schedules.list');
    Route::get('plans', [PageController::class, 'plans'])->name('plans.list');
});

// Gate cloud webhook
Route::post('gate-cloud/webhook', [GateCloudController::class, 'handleWebhook'])->name('gate-cloud.webhook');
