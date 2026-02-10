<?php

use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('layouts.index');
});
Route::view('/create/invoices', 'invoices.createinvoices')->name('create.invoices');

Route::view('/invoices/show', 'invoices.show')->name('invoices.show');

  Route::get('/invoices/edit', function () {
        return view('invoices.edit');
    })->name('invoices.edit');