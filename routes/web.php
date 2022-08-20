<?php

use App\Http\Controllers\DataController;
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

Route::get('/data', [DataController::class, 'index']);
Route::post('/data/store', [DataController::class, 'store'])->name('data.store');
Route::get('/data/verify/{data}', [DataController::class, 'verify'])->name('data.verify');
