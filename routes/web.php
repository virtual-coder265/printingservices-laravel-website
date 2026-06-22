<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\PortalController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicPageController;
use App\Http\Controllers\Admin\QuotationAttachmentDownloadController;
use App\Http\Controllers\QuotationRequestController;
use App\Http\Controllers\StudentEnrollmentController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PublicPageController::class, 'home'])->name('home');
Route::get('/services', [PublicPageController::class, 'services'])->name('services');
Route::get('/products', [PublicPageController::class, 'products'])->name('products');
Route::get('/training', [PublicPageController::class, 'training'])->name('training');
Route::get('/training/enroll', [PublicPageController::class, 'enrollment'])->name('enrollment');
Route::post('/training/enroll', [StudentEnrollmentController::class, 'store'])
    ->middleware('throttle:enrollment-submit')
    ->name('enrollment.store');
Route::get('/training/enroll/thank-you/{studentEnrollment}', [StudentEnrollmentController::class, 'confirmation'])
    ->name('enrollment.confirmation');
Route::get('/about', [PublicPageController::class, 'about'])->name('about');
Route::get('/teams', [PublicPageController::class, 'teams'])->name('teams');
Route::get('/contact', [PublicPageController::class, 'contact'])->name('contact');
Route::get('/quotation', [PublicPageController::class, 'quotation'])->name('quotation');
Route::get('/request-quotation', fn () => redirect()->route('quotation'));
Route::post('/quotation', [QuotationRequestController::class, 'store'])
    ->middleware('throttle:quotation-submit')
    ->name('quotation.store');
Route::get('/quotation/thank-you/{quotationRequest}', [QuotationRequestController::class, 'confirmation'])
    ->name('quotation.confirmation');
Route::get('/cart', [PublicPageController::class, 'cart'])->name('cart');
Route::post('/cart/add/{product}', [CartController::class, 'add'])->name('cart.add');
Route::patch('/cart/items/{item}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/items/{item}', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/checkout', [CheckoutController::class, 'store'])->middleware('auth')->name('checkout.store');
Route::get('/wishlist', [PublicPageController::class, 'wishlist'])->name('wishlist');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [PortalController::class, 'dashboard'])->name('dashboard');
    Route::get('/portal/quotations', [PortalController::class, 'quotations'])->name('portal.quotations');
    Route::get('/portal/quotations/{quotation}', [PortalController::class, 'showQuotation'])->name('portal.quotations.show');
    Route::post('/portal/quotations/{quotation}/artwork', [PortalController::class, 'uploadArtwork'])->name('portal.quotations.artwork');
    Route::get('/portal/orders', [PortalController::class, 'orders'])->name('portal.orders');
    Route::get('/portal/orders/{order}', [PortalController::class, 'showOrder'])->name('portal.orders.show');
    Route::get('/portal/print-jobs', [PortalController::class, 'printJobs'])->name('portal.print-jobs');
    Route::get('/portal/print-jobs/{printJob}', [PortalController::class, 'showPrintJob'])->name('portal.print-jobs.show');
});

Route::middleware(['auth'])->prefix('admin')->group(function () {
    Route::get(
        '/quotation-requests/attachments/{attachment}',
        QuotationAttachmentDownloadController::class
    )->name('admin.quotation-requests.attachments.download');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
