<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail; // Corrigez ici
use App\Mail\PasswordResetEmail; // Corrigez ici
use Illuminate\Auth\Events\PasswordReset;



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
    return view('welcome');
});


