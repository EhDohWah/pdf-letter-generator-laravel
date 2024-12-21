<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;
use App\Http\Controllers\LetterController;

Route::post('/generate-letter', [LetterController::class, 'generateLetter']);

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

 // Add more routes here
 Route::get('/books', [BookController::class, 'index']);

 Route::get('/books/{id}', [BookController::class, 'show']);

 Route::post('/books', [BookController::class, 'store']);

 Route::put('/books/{id}', [BookController::class, 'update']);

 Route::delete('/books/{id}', [BookController::class, 'destroy']);

 // New route to display the books view
Route::get('/books/view/test', function () {
    return view('books');
});


