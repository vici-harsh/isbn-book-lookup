<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;



Route::get('/book', [BookController::class, 'show'])->name('book.show');

Route::get('/', function () {
    return view('findbook'); 
});
