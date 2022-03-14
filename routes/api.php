<?php


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ThemeController;

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

Route::post('login', [AuthController::class, 'login']);

Route::post('indexTheme', [ThemeController::class, 'store'])->middleware('auth:api');
Route::post('saveTheme', [ThemeController::class, 'store'])->middleware('auth:api');
Route::post('updateTheme', [ThemeController::class, 'update'])->middleware('auth:api');
Route::delete('deleteTheme', [ThemeController::class, 'delete'])->middleware('auth:api');

Route::get('search', [ThemeController::class, 'search'])->middleware('auth:api');
