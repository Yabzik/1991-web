<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\DataSourceController;

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
    return view('home');
});

Route::get('/updateStatus', function () {
    return view('status');
});

// Route::get('/schedule/{speciality}/{course}', [DataSourceController::class, 'schedule_view']);
Route::get('/schedule/{speciality}/{course}', function ($speciality, $course) {
    return view('schedule', ['speciality' => $speciality, 'course' => $course]);
});
Route::get('/ical/{speciality}/{course}', [DataSourceController::class, 'generate_ical']);

Route::get('/syncInfo/{speciality}/{course}', function ($speciality, $course) {
    return view('syncinfo', ['speciality' => $speciality, 'course' => $course]);
});
