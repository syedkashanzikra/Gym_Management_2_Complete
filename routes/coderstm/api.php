<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\EnquiryController;
use Coderstm\Http\Controllers\LogController;
use App\Http\Controllers\Auth\AuthController;
use Coderstm\Http\Controllers\FileController;
use Coderstm\Http\Controllers\PlanController;
use Coderstm\Http\Controllers\TaskController;
use Coderstm\Http\Controllers\GroupController;
use App\Http\Controllers\ApplicationController;
use Coderstm\Http\Controllers\CashierWebhookController;
use Coderstm\Http\Controllers\Auth\ForgotPasswordController;
use Coderstm\Http\Controllers\Subscription\SubscriptionController;
use Coderstm\Http\Controllers\Subscription\PaymentMethodController;
use Coderstm\Http\Controllers\PaymentMethodController as ShopPaymentMethodController;

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
        });
    });
    Route::controller(ForgotPasswordController::class)->group(function () {
        Route::post('request-password', 'request')->name('users.request-password');
        Route::post('reset-password', 'reset')->name('users.reset-password');
    });
});

// Core Routes
Route::middleware(['auth:sanctum', 'guard:admins'])->group(function () {
    // Application Settings
    Route::controller(ApplicationController::class)->group(function () {
        Route::get('application/stats', 'stats')->name('application.stats');
        Route::post('application/test-mail-config', 'testMailConfig')->name('application.test-mail-config');
        Route::get('application/settings/{key}', 'getSettings')->name('application.get-settings');
        Route::middleware('can:update,Coderstm\Models\AppSetting')->group(function () {
            Route::post('application/settings', 'updateSettings')->name('application.update-settings');
        });
    });

    // Tasks
    Route::controller(TaskController::class)->middleware('can:update,task')->group(function () {
        Route::post('tasks/{task}/reply', 'reply')->name('tasks.reply');
        Route::post('tasks/{task}/change-archived', 'changeArchived')->name('tasks.change-archived');
    });
    Route::resource('tasks', TaskController::class)->except([
        'update'
    ]);

    // Admins
    Route::controller(AdminController::class)->group(function () {
        Route::get('admins/options', 'options')->name('admins.options');
        Route::get('admins/modules', 'modules')->name('admins.modules');
        Route::middleware('can:update,admin')->group(function () {
            Route::post('admins/{admin}/reset-password-request', 'resetPasswordRequest')->name('admins.reset-password-request');
            Route::post('admins/{admin}/change-active', 'changeActive')->name('admins.change-active');
            Route::post('admins/{admin}/change-admin', 'changeAdmin')->name('admins.change-admin');
            Route::post('admins/{admin}/change-instructor', 'changeInstructor')->name('admins.change-instructor');
        });
    });
    Route::resource('admins', AdminController::class);

    // Groups
    Route::resource('groups', GroupController::class);

    // Logs
    Route::post('logs/{log}/reply', [LogController::class, 'reply'])->name('logs.reply');
    Route::resource('logs', LogController::class)->only([
        'show', 'update', 'destroy',
    ]);

    // Shop Payment Methods
    Route::controller(ShopPaymentMethodController::class)->group(function () {
        Route::post('shop/payment-methods/{payment_method}/disable', 'disable')->name('payment-methods.disable');
        Route::post('shop/payment-methods/{payment_method}/enable', 'enable')->name('payment-methods.enable');
    });
    Route::resource('shop/payment-methods', ShopPaymentMethodController::class)->only([
        'index', 'store', 'show', 'update',
    ]);
});

Route::middleware(['auth:sanctum'])->group(function () {
    // Files
    Route::post('files/upload-from-source', [FileController::class, 'uploadFromSource'])->name('files.upload-from-source');
    Route::resource('files', FileController::class)->except([
        'destroySelected', 'restore', 'restoreSelected',
    ]);

    // Enquiries
    Route::controller(EnquiryController::class)->middleware('can:update,enquiry')->group(function () {
        Route::post('enquiries/{enquiry}/reply', 'reply')->name('enquiries.reply');
        Route::post('enquiries/{enquiry}/change-user-archived', 'changeUserArchived')->name('enquiries.change-user-archived');
        Route::post('enquiries/{enquiry}/change-archived', 'changeArchived')->name('enquiries.change-archived');
    });
    Route::resource('enquiries', EnquiryController::class);
});

// Common Routes
Route::middleware(['auth:sanctum'])->group(function () {
    // Subscription
    Route::prefix('subscription')->name('subscription.')->controller(SubscriptionController::class)->group(function () {
        Route::get('', 'index')->name('index');
        Route::get('setup-intent', 'getSetupIntent')->name('setup-intent');
        Route::post('subscribe', 'subscribe')->name('subscribe');
        Route::post('resume', 'resume')->name('resume');
        Route::post('confirm', 'confirm')->name('confirm');
        Route::post('cancel-downgrade', 'cancelDowngrade')->name('cancel-downgrade');
        Route::post('invoices', 'invoices')->name('invoices');
        Route::get('invoices/{invoiceId}', 'downloadInvoice')->name('invoices.download');

        //Only for subscriber
        Route::middleware(['subscribed'])->group(function () {
            Route::post('cancel', 'cancel')->name('cancel');
        });
    });
    Route::prefix('payment-methods')->name('payment-methods.')->controller(PaymentMethodController::class)->group(function () {
        Route::post('{paymentMethod}/update', 'update')->name('update');
        Route::delete('{paymentMethod}', 'destroy')->name('destroy');
    });
    Route::resource('payment-methods', PaymentMethodController::class)->only([
        'index', 'store',
    ]);
});

// Admin Routes
Route::middleware(['auth:sanctum', 'guard:admins'])->group(function () {
    // Options
    Route::get('users/options', [UserController::class, 'options']);

    // Users
    Route::prefix('users')->name('users.')->group(function () {
        Route::controller(UserController::class)->group(function () {
            Route::post('list-by-ids', 'listByIds')->name('list-by-ids');
            Route::middleware('can:update,user')->group(function () {
                Route::post('{user}/reset-password-request', 'resetPasswordRequest')->name('reset-password-request');
                Route::post('{user}/change-active', 'changeActive')->name('change-active');
                Route::post('{user}/notes', 'notes')->name('notes');
                Route::post('{user}/mark-as-paid', 'markAsPaid')->name('mark-as-paid');
            });
        });
    });
    Route::resource('users', UserController::class);

    // Plans
    Route::middleware('can:update,plan')->group(function () {
        Route::post('plans/{plan}/change-active', [PlanController::class, 'changeActive'])->name('plans.change-active');
    });
    Route::resource('plans', PlanController::class);
});

Route::get('qrcode/{user}.png', [UserController::class, 'qrcode'])->name('users.qrcode');

// Stripe webhook
Route::post('stripe/webhook', [CashierWebhookController::class, 'handleWebhook'])->name('stripe.webhook');
Route::get('application/location', [ApplicationController::class, 'location'])->name('application.location');
Route::get('application/config', [ApplicationController::class, 'config'])->name('application.config');
Route::get('application/payment-methods', [ApplicationController::class, 'paymentMethods'])->name('application.payment-methods');
