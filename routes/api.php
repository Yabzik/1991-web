<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\DataSourceController;

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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::get('/updates', [DataSourceController::class, 'get_updates']);
Route::post('/acquire', [DataSourceController::class, 'acquire_updates']);

Route::get('/schedule', [DataSourceController::class, 'get_schedule']);

Route::get('/feed/{speciality}/{course}', [DataSourceController::class, 'schedule_feed']);
Route::get('/addEvent/{speciality}/{course}', [DataSourceController::class, 'add_event']);
