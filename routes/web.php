<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\Admin\DashboardController;

/*
|--------------------------------------------------------------------------
| Web Routes - RisingCare
|--------------------------------------------------------------------------
*/

// Landing Page Routes
Route::get('/', [LandingController::class, 'index'])->name('beranda');
Route::get('/layanan', [LandingController::class, 'services'])->name('layanan');
Route::get('/artikel', [LandingController::class, 'articles'])->name('artikel');
Route::get('/artikel/{slug}', [LandingController::class, 'articleDetail'])->name('artikel.detail');

// Auth Routes
Route::middleware('guest')->group(function () {
    Route::get('/masuk', [LoginController::class, 'showLoginForm'])->name('masuk');
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login'); // Alias
    Route::post('/masuk', [LoginController::class, 'login']);
    Route::get('/registrasi', [RegisterController::class, 'showRegistrationForm'])->name('registrasi');
    Route::post('/registrasi', [RegisterController::class, 'register']);
});

Route::post('/keluar', [LoginController::class, 'logout'])->name('keluar')->middleware('auth');

// Customer Routes
Route::middleware(['auth', 'role:customer'])->prefix('member')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\CustomerController::class, 'index'])->name('customer.dashboard');
    
    // Profile Routes
    Route::get('/profil', [\App\Http\Controllers\CustomerController::class, 'profile'])->name('customer.profile');
    Route::put('/profil', [\App\Http\Controllers\CustomerController::class, 'updateProfile'])->name('customer.profile.update');

    // Booking Routes (Member Only)
    Route::get('/buat-janji', [BookingController::class, 'create'])->name('booking.create');
    Route::post('/buat-janji', [BookingController::class, 'store'])->name('booking.store');
    Route::get('/booking-sukses/{booking}', [BookingController::class, 'success'])->name('booking.success');
    Route::get('/booking/{booking:booking_number}', [BookingController::class, 'show'])->name('booking.show');
    Route::post('/booking/{booking:booking_number}/cancel', [BookingController::class, 'cancel'])->name('booking.cancel');
    Route::post('/booking/{booking:booking_number}/review', [BookingController::class, 'storeReview'])->name('booking.review.store');
    Route::post('/booking/{booking:booking_number}/upload-payment', [BookingController::class, 'uploadPaymentProof'])->name('booking.upload-payment');
    
    Route::get('/booking-saya', [BookingController::class, 'myBookings'])->name('booking.my');
    Route::get('/rekap-kesehatan', [\App\Http\Controllers\Customer\HealthRecordController::class, 'index'])->name('customer.health-record.index');
    Route::post('/rekap-kesehatan', [\App\Http\Controllers\Customer\HealthRecordController::class, 'store'])->name('customer.health-record.store');
    
    // Package Routes
    Route::get('/paket-saya', [\App\Http\Controllers\Customer\PackageController::class, 'index'])->name('customer.packages.index');
    Route::get('/paket-saya/{id}', [\App\Http\Controllers\Customer\PackageController::class, 'show'])->name('customer.packages.show');
    
    // Treatment Detail Route (for package visits)
    Route::get('/treatment/{booking:booking_number}', [\App\Http\Controllers\Customer\TreatmentController::class, 'show'])->name('customer.treatment.show');
});

// Admin Routes
Route::middleware(['auth', 'role:superadmin,admin_staff'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // User Management
    Route::resource('users', \App\Http\Controllers\Admin\UserController::class);
    Route::resource('occupations', \App\Http\Controllers\Admin\OccupationController::class);
    Route::resource('memberships', \App\Http\Controllers\Admin\MembershipController::class);
    
    // Service Management
    Route::resource('service-categories', \App\Http\Controllers\Admin\ServiceCategoryController::class);
    Route::resource('services', \App\Http\Controllers\Admin\ServiceController::class);
    // Route::resource('addons', \App\Http\Controllers\Admin\AddonController::class); // Commented out - controller doesn't exist
    
    // Package Price Management
    Route::get('/package-prices', [\App\Http\Controllers\Admin\PackagePriceController::class, 'index'])->name('package-prices.index');
    Route::get('/package-prices/{service}/edit', [\App\Http\Controllers\Admin\PackagePriceController::class, 'edit'])->name('package-prices.edit');
    Route::put('/package-prices/{service}', [\App\Http\Controllers\Admin\PackagePriceController::class, 'update'])->name('package-prices.update');
    
    // Bank Account Management
    Route::resource('bank-accounts', \App\Http\Controllers\Admin\BankAccountController::class);
    
    // Booking Management
    Route::resource('bookings', \App\Http\Controllers\Admin\BookingController::class);
    Route::post('bookings/{booking}/assign-petugas', [\App\Http\Controllers\Admin\BookingController::class, 'assignPetugas'])->name('bookings.assign-petugas');
    Route::post('bookings/{booking}/update-status', [\App\Http\Controllers\Admin\BookingController::class, 'updateStatus'])->name('bookings.update-status');
    
    // Payment & Voucher
    Route::resource('payments', \App\Http\Controllers\Admin\PaymentController::class);
    Route::post('payments/{payment}/upload-proof', [\App\Http\Controllers\Admin\PaymentController::class, 'uploadProof'])->name('payments.upload-proof');
    Route::post('payments/{payment}/verify', [\App\Http\Controllers\Admin\PaymentController::class, 'verify'])->name('payments.verify');
    Route::post('payments/{payment}/reject', [\App\Http\Controllers\Admin\PaymentController::class, 'reject'])->name('payments.reject');
    // Route::resource('vouchers', \App\Http\Controllers\Admin\VoucherController::class); // Commented out - controller doesn't exist
    
    // Image Upload for TinyMCE
    Route::post('upload-image', [\App\Http\Controllers\Admin\ArticleController::class, 'uploadImage'])->name('upload-image');
    
    // Landing Page CMS
    Route::prefix('landing')->name('landing.')->group(function () {
        Route::resource('hero-slides', \App\Http\Controllers\Admin\HeroSlideController::class);
        Route::resource('popups', \App\Http\Controllers\Admin\LandingPopupController::class);
        Route::post('popups/{popup}/toggle-active', [\App\Http\Controllers\Admin\LandingPopupController::class, 'toggleActive'])->name('popups.toggle-active');
        Route::resource('services-highlight', \App\Http\Controllers\Admin\ServicesHighlightController::class);
        Route::resource('how-we-work', \App\Http\Controllers\Admin\HowWeWorkController::class);
        Route::resource('testimonials', \App\Http\Controllers\Admin\TestimonialController::class);
        Route::resource('articles', \App\Http\Controllers\Admin\ArticleController::class);
        Route::get('cta-appointment', [\App\Http\Controllers\Admin\CtaAppointmentController::class, 'edit'])->name('cta-appointment.edit');
        Route::put('cta-appointment', [\App\Http\Controllers\Admin\CtaAppointmentController::class, 'update'])->name('cta-appointment.update');
        
        // About Section
        Route::get('about', [\App\Http\Controllers\Admin\LandingAboutController::class, 'index'])->name('about.index');
        Route::put('about', [\App\Http\Controllers\Admin\LandingAboutController::class, 'update'])->name('about.update');
    });
    
    // Reviews Management
    Route::get('reviews', [\App\Http\Controllers\Admin\ReviewController::class, 'index'])->name('reviews.index');
    Route::post('reviews/{review}/toggle-landing', [\App\Http\Controllers\Admin\ReviewController::class, 'toggleLanding'])->name('reviews.toggle-landing');
    Route::delete('reviews/{review}', [\App\Http\Controllers\Admin\ReviewController::class, 'destroy'])->name('reviews.destroy');
    
    // Commission Settings
    Route::get('commission-settings', [\App\Http\Controllers\Admin\CommissionSettingController::class, 'index'])->name('commission-settings.index');
    Route::put('commission-settings', [\App\Http\Controllers\Admin\CommissionSettingController::class, 'update'])->name('commission-settings.update');
    
    // Settings
    Route::get('settings', [\App\Http\Controllers\Admin\SiteSettingController::class, 'index'])->name('settings');
    Route::put('settings', [\App\Http\Controllers\Admin\SiteSettingController::class, 'update'])->name('settings.update');

    // Activity Logs
    Route::get('activity-logs', [\App\Http\Controllers\Admin\ActivityLogController::class, 'index'])->name('activity-logs.index');

    // Activity Logs
    Route::get('activity-logs', [\App\Http\Controllers\Admin\ActivityLogController::class, 'index'])->name('activity-logs.index');

    // Finance & Commissions
    Route::get('commissions', [\App\Http\Controllers\Admin\CommissionController::class, 'index'])->name('commissions.index');
    Route::get('payouts', [\App\Http\Controllers\Admin\PayoutController::class, 'index'])->name('payouts.index');
    Route::get('payouts/{payout}', [\App\Http\Controllers\Admin\PayoutController::class, 'show'])->name('payouts.show');
    Route::post('payouts/{payout}/process', [\App\Http\Controllers\Admin\PayoutController::class, 'process'])->name('payouts.process');
    Route::resource('health-screening', \App\Http\Controllers\Admin\HealthScreeningController::class)->parameters([
        'health-screening' => 'question'
    ]);
    Route::post('health-categories', [\App\Http\Controllers\Admin\HealthQuestionCategoryController::class, 'store'])->name('health-categories.store');
    Route::put('health-categories/{category}', [\App\Http\Controllers\Admin\HealthQuestionCategoryController::class, 'update'])->name('health-categories.update');
    Route::delete('health-categories/{category}', [\App\Http\Controllers\Admin\HealthQuestionCategoryController::class, 'destroy'])->name('health-categories.destroy');
    
    // Service Add-ons
    Route::resource('service-addons', \App\Http\Controllers\Admin\ServiceAddonController::class)->except(['show', 'create', 'edit']);
});

// Petugas Routes
Route::middleware(['auth', 'role:petugas'])->prefix('petugas')->name('petugas.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Petugas\DashboardController::class, 'index'])->name('dashboard');
    
    Route::get('/bookings', [\App\Http\Controllers\Petugas\BookingController::class, 'index'])->name('bookings');
    Route::get('/bookings/{booking:booking_number}', [\App\Http\Controllers\Petugas\BookingController::class, 'show'])->name('bookings.show');
    Route::post('/bookings/{booking:booking_number}/checkin', [\App\Http\Controllers\Petugas\BookingController::class, 'checkin'])->name('bookings.checkin');
    Route::post('/bookings/{booking:booking_number}/start', [\App\Http\Controllers\Petugas\BookingController::class, 'start'])->name('bookings.start');
    Route::post('/bookings/{booking:booking_number}/complete', [\App\Http\Controllers\Petugas\BookingController::class, 'complete'])->name('bookings.complete');
    Route::post('/bookings/{booking:booking_number}/add-addon', [\App\Http\Controllers\Petugas\BookingController::class, 'addAddon'])->name('bookings.add-addon');
    Route::post('/bookings/{booking:booking_number}/payment', [\App\Http\Controllers\Petugas\BookingController::class, 'processPayment'])->name('bookings.payment');
    Route::post('/bookings/{booking:booking_number}/upload-addon-payment', [\App\Http\Controllers\Petugas\BookingController::class, 'uploadAddonPayment'])->name('bookings.upload-addon-payment');
    Route::post('/bookings/{booking:booking_number}/payment/verify', [\App\Http\Controllers\Petugas\BookingController::class, 'verifyPayment'])->name('bookings.payment.verify');
    Route::post('/bookings/{booking:booking_number}/finalize', [\App\Http\Controllers\Petugas\BookingController::class, 'finalize'])->name('bookings.finalize');
    Route::delete('/booking-addons/{bookingAddon}', [\App\Http\Controllers\Petugas\BookingController::class, 'removeAddon'])->name('booking-addons.destroy');
    
    Route::get('/commissions', [\App\Http\Controllers\Petugas\CommissionController::class, 'index'])->name('commissions');

    Route::get('/customers/{user:code}', [\App\Http\Controllers\Petugas\CustomerController::class, 'show'])->name('customers.show');
    
    Route::get('/profile', [\App\Http\Controllers\Petugas\ProfileController::class, 'index'])->name('profile');
    Route::put('/profile', [\App\Http\Controllers\Petugas\ProfileController::class, 'update'])->name('profile.update');
});




