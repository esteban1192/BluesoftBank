<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\AdminController;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    $user = Auth::user()->load('accounts');
    return Inertia::render('Dashboard', [
        'accounts' => $user->accounts,
        'user' => $user
    ]);
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/create-account', [AccountController::class, 'createAccountView'])->name('account.createView');
    Route::post('user/account', [AccountController::class, 'createAccount'])->name('account.create');
    
    Route::get('/transfer', [TransactionController::class, 'makeTransactionView'])->name('transactions.makeTransactionView');
    Route::post('/transfer', [TransactionController::class, 'makeTransaction'])->name('transactions.makeTransaction');

    Route::get('/recent-transactions', [TransactionController::class, 'recentTransactionsView'])->name('transactions.recentTransactionsView');
    Route::get('/generate-statements', [AccountController::class, 'generateStatements'])->name('account.generateStatements');
    Route::get('/pdf-statements', [AccountController::class, 'pdfStatements'])->name('account.pdfStatements');
    Route::get('/admin/users-list', [AdminController::class, 'usersListView'])->name('admin.usersListView');
});

require __DIR__.'/auth.php';
