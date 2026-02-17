<?php

use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('layouts.index');
});
Route::view('/create/invoices', 'invoices.createinvoices')->name('create.invoices');

Route::view('/invoices/show', 'invoices.show')->name('invoices.show');
Route::view('/invoices/Preparation', 'invoices.Preparation')->name('invoices.Preparation');
Route::view('/invoices/track', 'invoices.track')->name('invoices.track');

  Route::get('/invoices/edit', function () {
        return view('invoices.edit');
    })->name('invoices.edit');