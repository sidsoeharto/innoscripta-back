<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\News\NewsAPIController;
use App\Http\Controllers\News\TheGuardianController;
use App\Http\Controllers\News\NewYorkTimesController;

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
    return ['Laravel' => app()->version()];
});

require __DIR__.'/auth.php';

Route::get('/fetch-news/all', [NewsAPIController::class, 'fetchNews']);
Route::get('/fetch-news/guardian', [TheGuardianController::class, 'fetchNews']);
Route::get('/fetch-news/new-york-times', [NewYorkTimesController::class, 'fetchNews']);

