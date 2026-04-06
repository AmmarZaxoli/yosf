<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Auth\Login;

Route::get('/', function () {
    return redirect('/login');
});

Route::middleware(['guest'])->group(function () {
    Route::get('/login', Login::class)->name('login');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return view('layouts.index');
    })->name('dashboard');

    Route::post('/logout', function () {
        auth()->guard()->logout();
        return redirect('/login');
    })->name('logout');
});
    Route::get('/Invoices/edit', function () {
        return view('Invoices.edit');
    })->name('Invoices.edit');

    Route::view('/create/Invoices', 'Invoices.createinvoices')->name('create.Invoices');
    Route::view('/Invoices/show', 'Invoices.show')->name('Invoices.show');
    Route::view('/Invoices/Preparation', 'Invoices.Preparation')->name('Invoices.Preparation');
    Route::view('/Invoices/track', 'Invoices.track')->name('Invoices.track');
    Route::view('/Invoices/sell', 'Invoices.sell')->name('Invoices.Sell');
    Route::view('/drivers/create', 'drivers.create')->name('drivers.create');
    Route::view('/accounts/create', 'accounts.create')->name('accounts.create');
    Route::view('/paying/create', 'paying.create')->name('paying.create');
    Route::view('/paying/returnpay', 'paying.returnpay')->name('paying.returnpay');
    Route::view('/accounting/create', 'accounting.create')->name('accounting.create');
    Route::view('/expenses/create', 'expenses.create')->name('expenses.create');
