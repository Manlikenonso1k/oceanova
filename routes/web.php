<?php

use App\Http\Controllers\BookingController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'home')->name('home');
Route::view('/about', 'about')->name('about');
Route::view('/chef', 'chef')->name('chef');
Route::view('/menu', 'menu')->name('menu');
Route::view('/reservation', 'reservation')->name('reservation');
Route::view('/blog', 'blog')->name('blog');
Route::view('/blog-single', 'blog-single')->name('blog.single');
Route::view('/contact', 'contact')->name('contact');

Route::post('/booking', [BookingController::class, 'store'])->name('booking.store');
