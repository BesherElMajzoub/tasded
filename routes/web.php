<?php

use App\Http\Controllers\ContactClickController;
use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\LegalPageController;
use Illuminate\Support\Facades\Route;

Route::get('/', LandingPageController::class)->name('landing.home');
Route::get('/sadad-alqorood', LandingPageController::class)->name('landing.sadad');
Route::get('/sadad-alqorood/{variant}', LandingPageController::class)
    ->whereIn('variant', array_keys(config('landing.variants', [])))
    ->name('landing.variant');
Route::post('/contact-clicks', [ContactClickController::class, 'store'])
    ->middleware('throttle:30,1')
    ->name('contact-clicks.store');
Route::get('/privacy', [LegalPageController::class, 'privacy'])->name('legal.privacy');
Route::get('/terms', [LegalPageController::class, 'terms'])->name('legal.terms');
