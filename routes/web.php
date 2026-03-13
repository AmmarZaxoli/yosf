<?php

use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('layouts.index');
});
  Route::get('/Invoices/edit', function () {
        return view('Invoices.edit');
    })->name('Invoices.edit');
Route::view('/create/Invoices', 'Invoices.createinvoices')->name('create.Invoices');

Route::view('/Invoices/show', 'Invoices.show')->name('Invoices.show');
Route::view('/Invoices/Preparation', 'Invoices.Preparation')->name('Invoices.Preparation');
Route::view('/Invoices/track', 'Invoices.track')->name('Invoices.track');
Route::view('/Invoices/sell', 'Invoices.sell')->name('Invoices.Sell');
route::view('/drivers/create', 'drivers.create')->name('drivers.create');
route::view('/accounts/create', 'accounts.create')->name('accounts.create');
route::view('/paying/create', 'paying.create')->name('paying.create');
route::view('/paying/returnpay', 'paying.returnpay')->name('paying.returnpay');
