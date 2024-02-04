<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\UserController;
use Coderstm\Http\Controllers\FileController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::controller(PageController::class)->group(function () {
    Route::get('', 'index')->name('home');
    Route::get('membership', 'membership')->name('membership');
    Route::get('classes', 'classes')->name('classes');
    Route::get('opening-times', 'openingTimes')->name('opening-times');
    Route::get('about', 'about')->name('about');
    Route::get('contact', 'contact')->name('contact');
    Route::post('contact', 'contactSubmit')->name('contact.submit');
    Route::get('documents', 'documents')->name('documents');
    Route::get('terms', 'terms')->name('terms');
    Route::get('privacy', 'privacy')->name('privacy');
    Route::get('partners', 'partners')->name('partners');
});

// File Download
Route::get('file/{path}', [FileController::class, 'download'])->name('files.download');
