<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use Illuminate\Support\Facades\Route;

// ── Public Routes ──────────────────────────────────────────────────────
Route::get('/', [App\Http\Controllers\HomeController::class , 'index'])->name('home');
Route::get('/vehiculos', [App\Http\Controllers\PublicVehicleController::class , 'index'])->name('vehicles.index');
Route::get('/api/vehicle-filters', [App\Http\Controllers\PublicVehicleController::class , 'filterOptions'])->name('api.vehicle-filters');
Route::get('/vehiculo/{slug}', [App\Http\Controllers\PublicVehicleController::class , 'show'])->name('vehicles.show');

Route::get('/nosotros', [App\Http\Controllers\PageController::class , 'about'])->name('about');
Route::get('/ubicacion', [App\Http\Controllers\PageController::class , 'location'])->name('location');
Route::get('/financiacion', [App\Http\Controllers\PageController::class , 'financing'])->name('financing');

Route::get('/contacto', [App\Http\Controllers\ContactController::class , 'index'])->name('contact');
Route::post('/contacto', [App\Http\Controllers\ContactController::class , 'store'])->name('contact.store');

Route::post('/newsletter/subscribe', [App\Http\Controllers\SubscriberController::class , 'subscribe'])->name('newsletter.subscribe');
Route::get('/newsletter/unsubscribe/{token}', [App\Http\Controllers\SubscriberController::class , 'unsubscribe'])->name('newsletter.unsubscribe');

Route::get('login', function () {
    return redirect()->route('admin.login');
})->name('login');

Route::get('admin/password/reset/{token}', [\App\Http\Controllers\Admin\ResetPasswordController::class , 'showResetForm'])->name('password.reset');

// ── Admin Routes ───────────────────────────────────────────────────────
Route::group(['prefix' => 'admin', 'as' => 'admin.'], function () {

    // Auth (guest only)
    Route::middleware('guest')->group(function () {
            Route::get('login', [AuthController::class , 'showLogin'])->name('login');
            Route::post('login', [AuthController::class , 'login']);

            // Forgot Password Routes
            Route::get('password/reset', [\App\Http\Controllers\Admin\ForgotPasswordController::class , 'showLinkRequestForm'])->name('password.request');
            Route::post('password/email', [\App\Http\Controllers\Admin\ForgotPasswordController::class , 'sendResetLinkEmail'])->name('password.email');
            Route::post('password/reset', [\App\Http\Controllers\Admin\ResetPasswordController::class , 'reset'])->name('password.update');
        }
        );

        Route::post('logout', [AuthController::class , 'logout'])
            ->middleware('auth')
            ->name('logout');

        // Protected admin routes
        Route::middleware(['auth', 'admin'])->group(function () {
            Route::get('/', [DashboardController::class , 'index'])->name('dashboard');
            Route::get('/dashboard', [DashboardController::class , 'index'])->name('dashboard.index');

            // Profile
            Route::get('/profile', [\App\Http\Controllers\Admin\ProfileController::class , 'index'])->name('profile.index');
            Route::put('/profile', [\App\Http\Controllers\Admin\ProfileController::class , 'updateProfile'])->name('profile.update');
            Route::put('/profile/password', [\App\Http\Controllers\Admin\ProfileController::class , 'updatePassword'])->name('profile.password');

            // Hero Media
            Route::resource('hero-media', \App\Http\Controllers\Admin\HeroMediaController::class)->only(['index', 'store', 'destroy'])->parameters(['hero-media' => 'heroMedia']);
            Route::post('hero-media/{heroMedia}/toggle', [\App\Http\Controllers\Admin\HeroMediaController::class , 'toggleActive'])->name('hero-media.toggle');
            Route::post('hero-media/reorder', [\App\Http\Controllers\Admin\HeroMediaController::class , 'reorder'])->name('hero-media.reorder');

            // Editor & above
            Route::resource('vehicles', \App\Http\Controllers\Admin\VehicleController::class);
            Route::delete('vehicles/{vehicle}/images/{image}', [\App\Http\Controllers\Admin\VehicleController::class , 'destroyImage'])->name('vehicles.images.destroy');
            Route::resource('inquiries', \App\Http\Controllers\Admin\InquiryController::class);

            // Newsletter
            Route::get('newsletter/subscribers', [\App\Http\Controllers\Admin\NewsletterController::class , 'subscribers'])->name('newsletter.subscribers');
            Route::delete('newsletter/subscribers/{subscriber}', [\App\Http\Controllers\Admin\NewsletterController::class , 'destroySubscriber'])->name('newsletter.subscribers.destroy');
            Route::get('newsletter/campaigns', [\App\Http\Controllers\Admin\NewsletterController::class , 'campaigns'])->name('newsletter.campaigns');
            Route::get('newsletter/campaigns/create', [\App\Http\Controllers\Admin\NewsletterController::class , 'createCampaign'])->name('newsletter.campaign.create');
            Route::post('newsletter/campaigns', [\App\Http\Controllers\Admin\NewsletterController::class , 'storeCampaign'])->name('newsletter.campaign.store');
            Route::get('newsletter/campaigns/{campaign}', [\App\Http\Controllers\Admin\NewsletterController::class , 'showCampaign'])->name('newsletter.campaign.show');
            Route::get('newsletter/campaigns/{campaign}/edit', [\App\Http\Controllers\Admin\NewsletterController::class , 'editCampaign'])->name('newsletter.campaign.edit');
            Route::put('newsletter/campaigns/{campaign}', [\App\Http\Controllers\Admin\NewsletterController::class , 'updateCampaign'])->name('newsletter.campaign.update');
            Route::delete('newsletter/campaigns/{campaign}', [\App\Http\Controllers\Admin\NewsletterController::class , 'destroyCampaign'])->name('newsletter.campaign.destroy');
            Route::post('newsletter/campaigns/{campaign}/send-test', [\App\Http\Controllers\Admin\NewsletterController::class , 'sendTest'])->name('newsletter.campaign.send-test');
            Route::post('newsletter/campaigns/{campaign}/send', [\App\Http\Controllers\Admin\NewsletterController::class , 'sendCampaign'])->name('newsletter.campaign.send');
            Route::get('newsletter/mail-status', [\App\Http\Controllers\Admin\NewsletterController::class , 'mailStatus'])->name('newsletter.mail-status');

            // Admin & above
            Route::middleware('role:super_admin,admin')->group(function () {
                    Route::resource('brands', \App\Http\Controllers\Admin\BrandController::class);
                    Route::resource('models', \App\Http\Controllers\Admin\CarModelController::class);
                    Route::resource('features', \App\Http\Controllers\Admin\VehicleFeatureController::class);

                    Route::get('settings', [\App\Http\Controllers\Admin\SettingController::class , 'index'])->name('settings.index');
                    Route::put('settings', [\App\Http\Controllers\Admin\SettingController::class , 'update'])->name('settings.update');
                }
                );

                // Super Admin ONLY
                Route::middleware('role:super_admin')->group(function () {
                    Route::resource('users', \App\Http\Controllers\Admin\UserController::class);
                }
                );
            }
            );
        });
