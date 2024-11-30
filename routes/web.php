<?php

use App\Http\Controllers\StudentPDFController;
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

Route::get('/list_students_pdf', [StudentPDFController::class, 'index']);
Route::get('/list_students_pdf/pdf', [StudentPDFController::class, 'pdf']);
