<?php

use App\Http\Controllers\BookingController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\KitchenInventoryController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\NewsletterController;
use App\Http\Controllers\ProcurementController;
use App\Http\Controllers\RedirectController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::view('/about', 'about')->name('about');
Route::view('/chef', 'chef')->name('chef');
Route::get('/menu', [MenuController::class, 'index'])->name('menu');
Route::view('/reservation', 'reservation')->name('reservation');
Route::view('/blog', 'blog')->name('blog');
Route::view('/blog-single', 'blog-single')->name('blog.single');
Route::view('/contact', 'contact')->name('contact');

Route::post('/booking', [BookingController::class, 'store'])->name('booking.store');
Route::post('/newsletter/subscribe', [NewsletterController::class, 'store'])->name('newsletter.subscribe');
Route::get('/track-redirect', RedirectController::class)->name('track.redirect');

Route::middleware(['auth', 'role:procurement_officer'])
	->prefix('admin/procurements')
	->name('admin.procurements.')
	->group(function (): void {
		Route::get('/', [ProcurementController::class, 'index'])->name('index');
		Route::post('/', [ProcurementController::class, 'store'])->name('store');
	});

Route::middleware(['auth', 'role:kitchen_manager'])
	->prefix('admin/inventory')
	->name('admin.inventory.')
	->group(function (): void {
		Route::get('/stock-levels', [KitchenInventoryController::class, 'stockLevels'])->name('stock-levels');
		Route::get('/low-stock', [KitchenInventoryController::class, 'lowStock'])->name('low-stock');
		Route::post('/waste', [KitchenInventoryController::class, 'logWaste'])->name('waste.store');
	});
