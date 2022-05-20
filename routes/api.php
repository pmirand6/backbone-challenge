<?php

use App\Http\Controllers\Api\V1\GetZipCodesFileController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return response()->json([
        'message' => 'Backbone Challenge',
        'author' => 'Pablo Miranda',
        'github' => 'https://github.com/pmirand6',
        'url' => 'https://github.com/pmirand6/backbone-challenge',
    ]);
});
Route::get('/zip-codes/{zip_code}', GetZipCodesFileController::class);
