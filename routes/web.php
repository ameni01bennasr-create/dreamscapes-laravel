<?php

use App\Http\Controllers\PageController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\VideoController;
use App\Http\Controllers\DeveloperController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Routes publiques (non connecté)
Route::get('/', [PageController::class, 'welcome'])->name('welcome');
Route::get('/developer/login', [DeveloperController::class, 'showLoginForm'])->name('developer.login');
Route::post('/developer/login', [DeveloperController::class, 'login'])->name('developer.login.submit');

// Routes d'authentification Laravel
Auth::routes();

// Routes protégées (connecté)
Route::middleware(['auth'])->group(function () {
    // Pages principales
    Route::get('/home', [PageController::class, 'home'])->name('home');
    Route::get('/my-videos', [PageController::class, 'myVideos'])->name('my-videos');
    
    // Formulaires
    Route::get('/form/cultural', [PageController::class, 'formulaireCultural'])->name('form.cultural');
    Route::get('/form/memory', [PageController::class, 'formulaireMemory'])->name('form.memory');
    Route::get('/form/future', [PageController::class, 'formulaireFuture'])->name('form.future');
    
    // Génération vidéo
    Route::post('/video/generate', [VideoController::class, 'generate'])->name('video.generate');
    Route::get('/video/result', [PageController::class, 'videoResult'])->name('video.result');
    
    // Panier
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::delete('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
    
    // Paiement
    Route::get('/payment', [PaymentController::class, 'index'])->name('payment.index');
    Route::post('/payment/process', [PaymentController::class, 'process'])->name('payment.process');
    Route::get('/thank-you', [PaymentController::class, 'thankYou'])->name('thank-you');
});

// Routes développeur
Route::middleware(['auth', 'developer'])->prefix('developer')->group(function () {
    Route::get('/dashboard', [DeveloperController::class, 'dashboard'])->name('developer.dashboard');
});

// Route de chargement (optionnelle)
Route::get('/loading', function () {
    return view('pages.loading');
})->name('loading');