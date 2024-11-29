<?php

use App\Http\Controllers\EtudiantPDFController;
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

Route::get('/list_etudiant_pdf', [EtudiantPDFController::class, 'index']);
Route::get('/list_etudiant_pdf/pdf', [EtudiantPDFController::class, 'pdf']);
