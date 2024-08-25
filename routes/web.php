<?php

use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;

// handle GET and POST requests
Route::resource('/', TransactionController::class);

// handle DELETE requests
Route::delete('/{transaction}', [TransactionController::class, 'destroy'])->name('transaction.delete');
