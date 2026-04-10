<?php

use App\Http\Controllers\BookingController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\NewsletterController;
use App\Http\Controllers\ProcurementTemplateController;
use App\Http\Controllers\RedirectController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::view('/about', 'about')->name('about');
Route::view('/chef', 'chef')->name('chef');
Route::get('/menu', [MenuController::class, 'index'])->name('menu');
Route::get('/menu/pdf-view', [MenuController::class, 'pdfView'])->name('menu.pdf-view');
Route::view('/reservation', 'reservation')->name('reservation');
Route::view('/blog', 'blog')->name('blog');
Route::view('/blog-single', 'blog-single')->name('blog.single');
Route::view('/contact', 'contact')->name('contact');

Route::post('/booking', [BookingController::class, 'store'])->name('booking.store');
Route::post('/newsletter/subscribe', [NewsletterController::class, 'store'])->name('newsletter.subscribe');
Route::get('/track-redirect', RedirectController::class)->name('track.redirect');

Route::middleware(['auth', 'role:procurement_officer,admin,super_admin'])
	->group(function (): void {
		Route::get('/admin/procurements/live-template', [ProcurementTemplateController::class, 'index'])
			->name('procurements.live-template');

		Route::post('/admin/procurements/live-template', [ProcurementTemplateController::class, 'store'])
			->name('procurements.live-template.store');

		Route::post('/admin/supplier/update/{supplier}', [\App\Http\Controllers\SupplierController::class, 'updateName'])
			->name('admin.supplier.update');
	});

Route::middleware(['auth'])->group(function (): void {
    Route::get('/dashboard/bookings', [BookingController::class, 'index'])->name('bookings.index');
});
