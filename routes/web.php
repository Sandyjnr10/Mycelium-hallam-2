<?php

use Illuminate\Support\Facades\Route;
use App\Jobs\MastodonPostStatusJob;

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

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
    'team'
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

Route::get('/scrape/{id}', [App\Http\Controllers\ScrapeController::class, 'scrape']);

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// the job is called by a button in the job view
// created in the 

Route::get('/bot-dashboard', [App\Http\Controllers\BotDashboardController::class, 'index'])->name('bot-dashboard');

Auth::routes();

Route::middleware(['auth'])->group(function () {
    Route::get('/admin', 'AdminDashboardController@index')->name('admin.Admindashboard');
});

