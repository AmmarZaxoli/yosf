<?php

use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('layouts.index');
});
Route::view('/create/Invoices', 'Invoices.createinvoices')->name('create.Invoices');

Route::view('/Invoices/show', 'Invoices.show')->name('Invoices.show');
Route::view('/Invoices/Preparation', 'Invoices.Preparation')->name('Invoices.Preparation');
Route::view('/Invoices/track', 'Invoices.track')->name('Invoices.track');
Route::view('/Invoices/sell', 'Invoices.sell')->name('Invoices.Sell');

  Route::get('/Invoices/edit', function () {
        return view('Invoices.edit');
    })->name('Invoices.edit');