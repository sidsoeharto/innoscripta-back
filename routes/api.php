<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UserController;
use App\Http\Controllers\News\CollectionController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group([
    'middleware' => ['auth:sanctum', 'cors'],
    'prefix' => 'user'
], function () {
    Route::get('/', [UserController::class, 'getOne']);
    Route::put('/update', [UserController::class, 'updateName']);
    Route::get('/preferences', [UserController::class, 'getPreferences']);
    Route::put('/preferences', [UserController::class, 'updatePreferences']);
});

Route::group([
    'middleware' => ['auth:sanctum', 'cors'],
    'prefix' => 'news'
], function () {
    Route::get('/', [CollectionController::class, 'getAll']);
    Route::post('/add', [CollectionController::class, 'store']);
    Route::delete('/remove/{id}', [CollectionController::class, 'delete']);
});
