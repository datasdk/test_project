<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Api;

use App\Http\Controllers\Api\LoginController;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


// create new company
Route::post("companies",[Api::class,"create_companies"]);

// get company
Route::get("companies",[Api::class,"get_companies"]);

// get clients
Route::get("companies/{id}/clients",[Api::class,"get_clients"]);

// attach users
Route::post("companies/{company}", [Api::class,"attach_clients"]);

// update company
Route::patch("companies/{company}", [Api::class,"update_company"]);


// user login
Route::patch("login", [LoginController::class,"login"]);