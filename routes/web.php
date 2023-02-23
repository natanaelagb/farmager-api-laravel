<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Site\HomeController;
use App\Http\Controllers\Site\AnimalController;
use App\Http\Controllers\Site\AnimalEventController;
use App\Http\Controllers\Site\FinancialTransactionController;
use App\Http\Controllers\Site\LogsController;
use App\Http\Controllers\Site\ProductController;
use App\Http\Controllers\Site\ProductionController;
use App\Http\Controllers\Site\UserController;
use App\Http\Middleware\EnsureAdminUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

Route::get('/', HomeController::class)->name('site.home.index');

Route::post('/logout', function(Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return true;
});

Route::middleware('auth.basic')->group(function () {

    Route::post('/login', function(Request $request){
        return User::where('email', $request->username)->get();
    });

    Route::get('/animals', [AnimalController::class, 'index'])->name('site.animals.index');
    Route::get('/animals-create', [AnimalController::class, 'create'])->name('site.animals.create');
    Route::post('/animals-filter', [AnimalController::class, 'filter'])->name('site.animals.filter');
    Route::post('/animals', [AnimalController::class, 'store'])->name('site.animals.store');
    Route::get('/animals/{animal}', [AnimalController::class, 'show'])->name('site.animals.show');
    Route::get('/animals/{animal}/edit', [AnimalController::class, 'edit'])->name('site.animals.edit');
    Route::post('/animals/{animal}/update', [AnimalController::class, 'update'])->name('site.animals.update');
    Route::delete('/animals/{animal}', [AnimalController::class, 'destroy'])->name('site.animals.destroy');

    Route::get('/animals-last-events', [AnimalEventController::class, 'lastEvents'])->name('site.animals.events.lastEvents');
    Route::get('/animals/{animal}/events', [AnimalEventController::class, 'index'])->name('site.animals.events.index');
    Route::get('/animals/{animal}/events-create', [AnimalEventController::class, 'create'])->name('site.animals.events.create');
    Route::post('/animals/{animal}/events-filter', [AnimalEventController::class, 'filter'])->name('site.animals.events.create');
    Route::post('/animals/{animal}/events', [AnimalEventController::class, 'store'])->name('site.animals.events.store');
    Route::get('/animals/{animal}/events/{event}', [AnimalEventController::class, 'show'])->name('site.animals.events.show');
    Route::get('/animals/{animal}/events/{event}/edit', [AnimalEventController::class, 'edit'])->name('site.animals.events.edit');
    Route::post('/animals/{animal}/events/{event}/update', [AnimalEventController::class, 'update'])->name('site.animals.events.update');
    Route::delete('/animals/{animal}/events/{event}', [AnimalEventController::class, 'destroy'])->name('site.animals.events.destroy');

    Route::get('/products', [ProductController::class, 'index'])->name('site.products.index');
    Route::get('/products-inventory', [ProductController::class, 'inventory'])->name('site.products.inventory');
    Route::get('/products-create', [ProductController::class, 'create'])->name('site.products.create');
    Route::post('/products', [ProductController::class, 'store'])->name('site.products.store');
    Route::get('/products/{product}', [ProductController::class, 'show'])->name('site.products.show');
    Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('site.products.edit');
    Route::post('/products/{product}/update', [ProductController::class, 'update'])->name('site.products.update');
    Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('site.products.destroy');

    Route::get('/productions', [ProductionController::class, 'index'])->name('site.productions.index');
    Route::get('/productions-limit', [ProductionController::class, 'limit'])->name('site.productions.limit');
    Route::get('/productions-create', [ProductionController::class, 'create'])->name('site.productions.create');
    Route::post('/productions', [ProductionController::class, 'store'])->name('site.productions.store');
    Route::get('/productions/{production}', [ProductionController::class, 'show'])->name('site.productions.show');
    Route::get('/productions/{production}/edit', [ProductionController::class, 'edit'])->name('site.productions.edit');
    Route::post('/productions/{production}/update', [ProductionController::class, 'update'])->name('site.productions.update');
    Route::delete('/productions/{production}', [ProductionController::class, 'destroy'])->name('site.productions.destroy');

    Route::get('/financial-transactions', [FinancialTransactionController::class, 'index'])->name('site.financial-transactions.index');
    Route::get('/financial-transactions/{transaction}', [FinancialTransactionController::class, 'show'])->name('site.financial-transactions.show');

    Route::get('/dashboard', [FinancialTransactionController::class, 'index'])->name('site.dashboard.index');


    // Admin group
    Route::middleware(EnsureAdminUser::class)->group(function () {

        Route::get('/logs', LogsController::class)->name('site.logs.index');

        Route::get('/users', [UserController::class, 'index'])->name('site.users.index');
        Route::get('/users-create', [UserController::class, 'create'])->name('site.users.create');
        Route::post('/users-filter', [UserController::class, 'filter'])->name('site.users.filter');
        Route::post('/users', [UserController::class, 'store'])->name('site.users.store');
        Route::get('/users/{user}', [UserController::class, 'show'])->name('site.users.show');
        Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('site.users.edit');
        Route::post('/users/{user}/update', [UserController::class, 'update'])->name('site.users.update');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('site.users.destroy');

        Route::get('/financial-transactions-create', [FinancialTransactionController::class, 'create'])->name('site.financial-transactions.create');
        Route::post('/financial-transactions', [FinancialTransactionController::class, 'store'])->name('site.financial-transactions.store');
        Route::get('/financial-transactions/{transaction}/edit', [FinancialTransactionController::class, 'edit'])->name('site.financial-transactions.edit');
        Route::post('/financial-transactions/{transaction}/update', [FinancialTransactionController::class, 'update'])->name('site.financial-transactions.update');
        Route::delete('/financial-transactions/{transaction}', [FinancialTransactionController::class, 'destroy'])->name('site.financial-transactions.destroy');

    });

});
