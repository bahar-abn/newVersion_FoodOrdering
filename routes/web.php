<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\DiscountController;
use App\Http\Controllers\SurveyController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\Admin\ReportController;

// Homepage
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Authentication Routes
Route::controller(AuthController::class)->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('/login', 'showLoginForm')->name('login');
        Route::post('/login', 'login');
        Route::get('/register', 'showRegistrationForm')->name('register');
        Route::post('/register', 'register');
    });
    
    Route::post('/logout', 'logout')->middleware('auth')->name('logout');
});

// Public Menu Routes
Route::get('/menu', [MenuController::class, 'index'])->name('menu.index');
Route::get('/menu/{menu}', [MenuController::class, 'show'])->name('menu.show');

// Authenticated User Routes
Route::middleware('auth')->group(function () {
    // Cart Routes
    Route::prefix('cart')->group(function () {
        Route::get('/', [CartController::class, 'index'])->name('cart.index');
        Route::post('/add', [CartController::class, 'add'])->name('cart.add');
        Route::put('/update/{menu}', [CartController::class, 'update'])->name('cart.update');
        Route::delete('/remove/{menu}', [CartController::class, 'remove'])->name('cart.remove');
        Route::post('/checkout', [CartController::class, 'checkout'])->name('cart.checkout');
    });

    // Order Routes
    Route::prefix('orders')->group(function () {
        Route::get('/', [OrderController::class, 'index'])->name('orders.index');
        Route::post('/', [OrderController::class, 'store'])->name('orders.store');
        Route::post('/direct', [OrderController::class, 'directOrder'])->name('orders.direct');
        Route::get('/{order}', [OrderController::class, 'show'])->name('orders.show');
        Route::get('/{order}/edit', [OrderController::class, 'edit'])->name('orders.edit');
        Route::put('/{order}', [OrderController::class, 'update'])->name('orders.update');
        Route::post('/{order}/reorder', [OrderController::class, 'reorder'])->name('orders.reorder');
        Route::delete('/{order}', [OrderController::class, 'cancel'])->name('orders.cancel');
        Route::post('/{order}/apply-discount', [DiscountController::class, 'apply'])->name('discount.apply');
    });

    // Payment Routes
    Route::prefix('payment')->group(function () {
        Route::get('/{order}', [PaymentController::class, 'show'])->name('payment.form');
        Route::post('/{order}/process', [PaymentController::class, 'process'])->name('payment.process');
        Route::get('/{order}/success', [PaymentController::class, 'success'])->name('payment.success');
        Route::get('/{order}/cancel', [PaymentController::class, 'cancel'])->name('payment.cancel');
        Route::post('/{order}/complete', [PaymentController::class, 'complete'])->name('payment.complete');
    });

    // Feedback Routes
    Route::post('/menu/{menu}/rate', [SurveyController::class, 'store'])->name('survey.store');
    Route::post('/menu/{menu}/comment', [CommentController::class, 'store'])->name('comment.store');
});

/// Admin Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Reports
    Route::get('/orders-report', [ReportController::class, 'ordersReport'])->name('orders');
    Route::get('/popular-food', [ReportController::class, 'popularFood'])->name('popular');
    Route::get('/payment-report', [ReportController::class, 'paymentReport'])->name('payments');
    Route::get('/comments', [ReportController::class, 'pendingComments'])->name('comments');
    Route::post('/comments/{comment}/approve', [ReportController::class, 'approveComment'])->name('comments.approve');
    Route::delete('/comments/{comment}/reject', [ReportController::class, 'rejectComment'])->name('comments.reject');

    // Menu Management
    Route::prefix('menu')->group(function () {
        Route::get('/', [MenuController::class, 'adminIndex'])->name('menu.index');
        Route::get('/create', [MenuController::class, 'create'])->name('menu.create');
        Route::post('/', [MenuController::class, 'store'])->name('menu.store');
        Route::get('/{menu}/edit', [MenuController::class, 'edit'])->name('menu.edit');
        Route::put('/{menu}', [MenuController::class, 'update'])->name('menu.update');
        Route::delete('/{menu}', [MenuController::class, 'destroy'])->name('menu.destroy');
    });
});



