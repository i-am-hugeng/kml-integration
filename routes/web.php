<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KmlController;

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

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', [KmlController::class, 'index']);

Route::post('/ajax_upload/action', [KmlController::class, 'action'])->name('ajaxupload.action');
