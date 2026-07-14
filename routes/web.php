<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InstallController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\TripController;
use App\Http\Controllers\Admin\ItineraryController;
use App\Http\Controllers\Admin\TransportController;
use App\Http\Controllers\Admin\TravelerController;
use App\Http\Controllers\Admin\ProgramController;
use App\Http\Controllers\Admin\TripCategoryController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\PricingController;
use App\Http\Controllers\Admin\AccommodationController;
use App\Http\Controllers\Admin\TripTravelerController;
use App\Http\Controllers\Admin\TripDocumentController;
use App\Http\Controllers\Public\HomeController;
use App\Http\Controllers\Public\TripController as PublicTripController;
use App\Http\Controllers\Public\TripDetailController;
use App\Http\Controllers\Public\BookingController;
use App\Http\Controllers\Public\AboutController;
use App\Http\Controllers\Public\BlogController;
use App\Http\Controllers\Public\FaqController;
use App\Http\Controllers\Public\ServiceController as PublicServiceController;
use App\Http\Controllers\Public\ContactController;
use App\Http\Controllers\Admin\Blog\BlogCategoryController;
use App\Http\Controllers\Admin\Blog\BlogPostController;
use App\Http\Controllers\Admin\FaqController as AdminFaqController;
use App\Http\Controllers\Admin\ServiceController as AdminServiceController;
use App\Http\Controllers\Admin\ContactController as AdminContactController;
use App\Http\Controllers\Admin\Crm\CustomerController as CrmCustomerController;
use App\Http\Controllers\Admin\Crm\InteractionController as CrmInteractionController;
use App\Http\Controllers\Admin\ThemeController as AdminThemeController;
use App\Http\Controllers\Admin\SettingsController as AdminSettingsController;
use App\Http\Controllers\Admin\SearchController as AdminSearchController;
use App\Http\Controllers\Admin\ImageController as AdminImageController;
use App\Http\Controllers\Admin\CalendarController as AdminCalendarController;

// --- Installation Routes (no middleware) ---
Route::get('/install', [InstallController::class, 'step1'])->name('install.step1');
Route::post('/install/step1', [InstallController::class, 'postStep1'])->name('install.postStep1');
Route::get('/install/step2', [InstallController::class, 'step2'])->name('install.step2');
Route::post('/install/step2', [InstallController::class, 'postStep2'])->name('install.postStep2');
Route::get('/install/step3', [InstallController::class, 'step3'])->name('install.step3');
Route::post('/install/step3', [InstallController::class, 'postStep3'])->name('install.postStep3');
Route::get('/install/step4', [InstallController::class, 'step4'])->name('install.step4');
Route::post('/install/step4', [InstallController::class, 'postStep4'])->name('install.postStep4');

// --- Public Routes (with installation check) ---
Route::middleware('installed')->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/viajes', [PublicTripController::class, 'index'])->name('public.trips');
    Route::get('/viaje/{slug}', [TripDetailController::class, 'show'])->name('public.trip-detail');

    // Booking wizard
    Route::get('/viaje/{slug}/reservar', [BookingController::class, 'step1'])->name('public.booking.step1');
    Route::post('/viaje/{slug}/reservar/fecha', [BookingController::class, 'postStep1'])->name('public.booking.postStep1');
    Route::get('/viaje/{slug}/reservar/plazas', [BookingController::class, 'step2'])->name('public.booking.step2');
    Route::post('/viaje/{slug}/reservar/plazas', [BookingController::class, 'postStep2'])->name('public.booking.postStep2');
    Route::get('/viaje/{slug}/reservar/datos', [BookingController::class, 'step3'])->name('public.booking.step3');
    Route::post('/viaje/{slug}/reservar/datos', [BookingController::class, 'postStep3'])->name('public.booking.postStep3');
    Route::get('/viaje/{slug}/reservar/tarifa', [BookingController::class, 'step4'])->name('public.booking.step4');
    Route::post('/viaje/{slug}/reservar/tarifa', [BookingController::class, 'postStep4'])->name('public.booking.postStep4');
    Route::get('/viaje/{slug}/reservar/extras', [BookingController::class, 'step5'])->name('public.booking.step5');
    Route::post('/viaje/{slug}/reservar/extras', [BookingController::class, 'postStep5'])->name('public.booking.postStep5');
    Route::get('/viaje/{slug}/reservar/resumen', [BookingController::class, 'step6'])->name('public.booking.step6');
    Route::post('/viaje/{slug}/reservar/confirmar', [BookingController::class, 'confirm'])->name('public.booking.confirm');
    Route::get('/viaje/{slug}/reservar/confirmado', [BookingController::class, 'step8'])->name('public.booking.step8');

    Route::get('/sobre-nosotros', [AboutController::class, 'index'])->name('public.about');
    Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
    Route::get('/blog/categoria/{slug}', [BlogController::class, 'category'])->name('blog.category');
    Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');
    Route::get('/servicios', [PublicServiceController::class, 'index'])->name('servicios');
    Route::get('/preguntas-frecuentes', [FaqController::class, 'index'])->name('faq');
    Route::get('/contacto', [ContactController::class, 'index'])->name('contacto');
    Route::post('/contacto', [ContactController::class, 'store'])->name('contacto.store');
});

// --- Admin Routes (auth + admin middleware) ---
Route::prefix('panel-agencia')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('export', [DashboardController::class, 'exportCsv'])->name('admin.dashboard.export');

    // Trips
    Route::resource('trips', TripController::class)->names('admin.trips');
    Route::post('trips/{trip}/duplicate', [TripController::class, 'duplicate'])->name('admin.trips.duplicate');

    // Itinerary
    Route::get('trips/{trip}/itinerary', [ItineraryController::class, 'index'])->name('admin.trips.itinerary.index');
    Route::post('trips/{trip}/itinerary/days', [ItineraryController::class, 'storeDay'])->name('admin.trips.itinerary.days.store');
    Route::put('itinerary-days/{day}', [ItineraryController::class, 'updateDay'])->name('admin.trips.itinerary.days.update');
    Route::delete('itinerary-days/{day}', [ItineraryController::class, 'destroyDay'])->name('admin.trips.itinerary.days.destroy');
    Route::post('itinerary-days/{day}/toggle-publish', [ItineraryController::class, 'togglePublish'])->name('admin.trips.itinerary.days.toggle-publish');
    Route::post('itinerary-days/{day}/activities', [ItineraryController::class, 'storeActivity'])->name('admin.trips.itinerary.activities.store');
    Route::put('itinerary-activities/{activity}', [ItineraryController::class, 'updateActivity'])->name('admin.trips.itinerary.activities.update');
    Route::delete('itinerary-activities/{activity}', [ItineraryController::class, 'destroyActivity'])->name('admin.trips.itinerary.activities.destroy');

    // Transport
    Route::get('trips/{trip}/transport', [TransportController::class, 'index'])->name('admin.trips.transport.index');
    Route::post('trips/{trip}/transport', [TransportController::class, 'store'])->name('admin.trips.transport.store');
    Route::put('transport/{bus}', [TransportController::class, 'update'])->name('admin.trips.transport.update');
    Route::delete('transport/{bus}', [TransportController::class, 'destroy'])->name('admin.trips.transport.destroy');
    Route::get('transport/{bus}/seats', [TransportController::class, 'seats'])->name('admin.trips.transport.seats');

    // Pricing (tarifas y plan de pagos)
    Route::get('trips/{trip}/pricing', [PricingController::class, 'index'])->name('admin.trips.pricing.index');
    Route::post('trips/{trip}/pricing/groups', [PricingController::class, 'storeGroup'])->name('admin.trips.pricing.groups.store');
    Route::put('pricing-groups/{group}', [PricingController::class, 'updateGroup'])->name('admin.trips.pricing.groups.update');
    Route::delete('pricing-groups/{group}', [PricingController::class, 'destroyGroup'])->name('admin.trips.pricing.groups.destroy');
    Route::post('pricing-groups/{group}/installments', [PricingController::class, 'storeInstallment'])->name('admin.trips.pricing.installments.store');
    Route::put('pricing-installments/{installment}', [PricingController::class, 'updateInstallment'])->name('admin.trips.pricing.installments.update');
    Route::delete('pricing-installments/{installment}', [PricingController::class, 'destroyInstallment'])->name('admin.trips.pricing.installments.destroy');

    // Accommodations
    Route::get('trips/{trip}/accommodations', [AccommodationController::class, 'index'])->name('admin.trips.accommodations.index');
    Route::post('trips/{trip}/accommodations', [AccommodationController::class, 'store'])->name('admin.trips.accommodations.store');
    Route::put('accommodations/{accommodation}', [AccommodationController::class, 'update'])->name('admin.trips.accommodations.update');
    Route::delete('accommodations/{accommodation}', [AccommodationController::class, 'destroy'])->name('admin.trips.accommodations.destroy');
    Route::post('accommodations/{accommodation}/rooms', [AccommodationController::class, 'storeRoom'])->name('admin.trips.accommodations.rooms.store');
    Route::post('rooms/{room}/assign-traveler', [AccommodationController::class, 'assignTraveler'])->name('admin.trips.accommodations.rooms.assign');
    Route::delete('rooms/{room}/travelers/{traveler}', [AccommodationController::class, 'removeTraveler'])->name('admin.trips.accommodations.rooms.remove');

    // Trip Travelers (per-trip)
    Route::get('trips/{trip}/travelers', [TripTravelerController::class, 'index'])->name('admin.trips.travelers.index');

    // Trip Documents
    Route::get('trips/{trip}/documents', [TripDocumentController::class, 'index'])->name('admin.trips.documents.index');
    Route::post('trips/{trip}/documents', [TripDocumentController::class, 'store'])->name('admin.trips.documents.store');
    Route::put('trips/{trip}/documents/{document}', [TripDocumentController::class, 'update'])->name('admin.trips.documents.update');
    Route::delete('trips/{trip}/documents/{document}', [TripDocumentController::class, 'destroy'])->name('admin.trips.documents.destroy');

    // Travelers (global)
    Route::get('travelers', [TravelerController::class, 'index'])->name('admin.travelers.index');
    Route::get('travelers/export', [TravelerController::class, 'export'])->name('admin.travelers.export');
    Route::resource('travelers', TravelerController::class)->except('index')->names('admin.travelers');

    // Programs
    Route::resource('programs', ProgramController::class)->names('admin.programs');
    Route::post('programs/{program}/duplicate', [ProgramController::class, 'duplicate'])->name('admin.programs.duplicate');

    // Categories
    Route::resource('trip-categories', TripCategoryController::class)->names('admin.categories');

    // Blog
    Route::resource('blog-categories', BlogCategoryController::class)->except(['create', 'edit', 'show'])->names('admin.blog-categories');
    Route::resource('blog-posts', BlogPostController::class)->names('admin.blog-posts');

    // FAQ
    Route::get('faqs', [AdminFaqController::class, 'index'])->name('admin.faqs.index');
    Route::post('faqs', [AdminFaqController::class, 'store'])->name('admin.faqs.store');
    Route::put('faqs/{faq}', [AdminFaqController::class, 'update'])->name('admin.faqs.update');
    Route::delete('faqs/{faq}', [AdminFaqController::class, 'destroy'])->name('admin.faqs.destroy');

    // Services
    Route::resource('services', AdminServiceController::class)->names('admin.services');

    // FAQ reorder
    Route::post('faqs/reorder', [AdminFaqController::class, 'reorder'])->name('admin.faqs.reorder');

    // Contacts
    Route::get('contacts', [AdminContactController::class, 'index'])->name('admin.contacts.index');
    Route::get('contacts/{contact}', [AdminContactController::class, 'show'])->name('admin.contacts.show');
    Route::delete('contacts/{contact}', [AdminContactController::class, 'destroy'])->name('admin.contacts.destroy');

    // Profile
    Route::get('profile', [ProfileController::class, 'index'])->name('admin.profile');
    Route::put('profile', [ProfileController::class, 'update'])->name('admin.profile.update');
    Route::put('profile/password', [ProfileController::class, 'updatePassword'])->name('admin.profile.password');

    // Settings
    Route::get('configuracion', [AdminSettingsController::class, 'index'])->name('admin.settings.index');
    Route::put('configuracion', [AdminSettingsController::class, 'update'])->name('admin.settings.update');
    Route::get('configuracion/frases', [AdminSettingsController::class, 'phrases'])->name('admin.settings.phrases');
    Route::put('configuracion/frases', [AdminSettingsController::class, 'phrasesUpdate'])->name('admin.settings.phrases.update');
    Route::get('configuracion/redes', [AdminSettingsController::class, 'social'])->name('admin.settings.social');
    Route::put('configuracion/redes', [AdminSettingsController::class, 'socialUpdate'])->name('admin.settings.social.update');
    Route::get('configuracion/email', [AdminSettingsController::class, 'email'])->name('admin.settings.email');
    Route::put('configuracion/email', [AdminSettingsController::class, 'emailUpdate'])->name('admin.settings.email.update');
    Route::get('configuracion/ayuda', [AdminSettingsController::class, 'help'])->name('admin.settings.help');

    // Search
    Route::get('buscar', [AdminSearchController::class, 'index'])->name('admin.search');

    // Images
    Route::get('imagenes', [AdminImageController::class, 'index'])->name('admin.images.index');
    Route::post('imagenes', [AdminImageController::class, 'upload'])->name('admin.images.upload');
    Route::delete('imagenes/{image}', [AdminImageController::class, 'destroy'])->name('admin.images.destroy');

    // Calendar
    Route::get('calendario', [AdminCalendarController::class, 'index'])->name('admin.calendar.index');
    Route::get('calendario/data', [AdminCalendarController::class, 'data'])->name('admin.calendar.data');

    // Themes
    Route::get('temas', [AdminThemeController::class, 'index'])->name('admin.themes.index');
    Route::post('temas/activar', [AdminThemeController::class, 'activate'])->name('admin.themes.activate');
    Route::get('temas/preview/{theme}', [AdminThemeController::class, 'preview'])->name('admin.themes.preview');

    // CRM
    Route::prefix('crm')->name('admin.crm.')->group(function () {
        Route::get('/', [CrmCustomerController::class, 'index'])->name('kanban');
        Route::get('/clientes', [CrmCustomerController::class, 'list'])->name('customers.list');
        Route::get('/cliente/{id}', [CrmCustomerController::class, 'show'])->name('customers.show');
        Route::post('/cliente/{id}/etapa', [CrmCustomerController::class, 'updateStage'])->name('customers.stage');
        Route::post('/cliente/{id}/etapa-ajax', [CrmCustomerController::class, 'updateStageAjax'])->name('customers.stage.ajax');
        Route::post('/cliente/{id}/interaccion', [CrmInteractionController::class, 'store'])->name('customers.interactions.store');
        Route::get('/cliente/{id}/interacciones', [CrmInteractionController::class, 'index'])->name('customers.interactions');
        Route::get('/metricas', [CrmCustomerController::class, 'metrics'])->name('metrics');
    });
});

// Default auth routes from Breeze
require __DIR__.'/auth.php';
