<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::post('/register/book', [App\Http\Controllers\HomeController::class, 'registerBook'])->name('register.book');
Route::post('/edit/book', [App\Http\Controllers\HomeController::class, 'editBook'])->name('edit.book');
Route::delete('/delete/book/{book_id}', [App\Http\Controllers\HomeController::class, 'destroyBook'])->name('delete.book');
Route::get('books/export/', [App\Http\Controllers\HomeController::class, 'export'])->name('export.book');
Route::get('books/downloadPDF/', [App\Http\Controllers\HomeController::class, 'downloadPDF'])->name('downloadPDF.book');
