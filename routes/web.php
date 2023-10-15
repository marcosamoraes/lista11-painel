<?php

use App\Http\Controllers\AppController;
use App\Http\Controllers\BannerController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ContractController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PackController;
use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\SellerController;
use App\Http\Enums\UserRoleEnum;
use App\Models\User;
use App\Notifications\ClientCreated;

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
$admin = UserRoleEnum::Admin->value;
$seller = UserRoleEnum::Seller->value;
$client = UserRoleEnum::Client->value;

Route::get('/venda/{order:uuid}/contrato', [OrderController::class, 'viewContract'])->name('orders.contract');
Route::post('/venda/{order:uuid}/contrato', [OrderController::class, 'signContract'])->name('orders.contract.sign');

Route::middleware('auth')->group(function () use ($admin, $seller, $client) {
    Route::get('/dashboard', DashboardController::class)->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('companies', CompanyController::class);
    Route::resource('contacts', ContactController::class)->only(['index', 'destroy']);

    Route::middleware("role:{$admin}")->group(function () {
        Route::resource('sellers', SellerController::class);
        Route::resource('contracts', ContractController::class);
        Route::resource('packs', PackController::class);
        Route::resource('categories', CategoryController::class);
        Route::resource('registers', RegisterController::class)->only(['index', 'destroy']);
        Route::resource('reviews', ReviewController::class)->only(['index', 'destroy']);
        Route::resource('banners', BannerController::class);
        Route::resource('posts', PostController::class);
        Route::resource('apps', AppController::class);
    });

    Route::middleware("role:{$admin}|{$seller}")->group(function () {
        Route::resource('clients', ClientController::class);
        Route::resource('orders', OrderController::class)->except(['destroy']);
        Route::get('orders/{order}/payment/generate', [OrderController::class, 'generatePaymentLink'])->name('orders.payment.generate');
    });

    Route::get('/settings', [ClientController::class, 'settings'])->name('settings');
    Route::put('/settings', [ClientController::class, 'updateSettings'])->name('settings.update');
});

Route::get('/payments/webhook', [OrderController::class, 'paymentWebhook'])->name('webhook');

Route::get('/mailable', function () {
    return (new ClientCreated('123456'))->toMail(User::first());
});

require __DIR__ . '/auth.php';
