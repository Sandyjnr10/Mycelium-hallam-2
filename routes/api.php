<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Jobs\MastodonPostStatusJob;
use App\Jobs\TwitterGetUserIDJob;
use App\Jobs\TwitterNewDirectMessageJob;
use App\Jobs\TwitterPostStatusJob;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/mastodon/post-status', function (Request $request) {
    MastodonPostStatusJob::dispatch()->onQueue('socialsQueue');
    return "Mastodon status job successfully created at " . date('Y-m-d H:i:s');
});

Route::post('/twitter/post-status', function (Request $request) {
    TwitterPostStatusJob::dispatch()->onQueue('socialsQueue');
    return 'Twitter status job successfully created at ' . date('Y-m-d H:i:s');
});

Route::post('/twitter/send-dm', function (Request $request) {
    TwitterNewDirectMessageJob::dispatch()->onQueue('socialsQueue');
    return 'New Twitter DM job created at ' . date('Y-m-d H:i:s');
});

Route::post('/twitter/getIDFromUsername', function (Request $request) {
    TwitterGetUserIDJob::dispatch()->onQueue('socialsQueue');
    return 'Get twitter ID job created at ' . date('Y-m-d H:i:s');
});

Route::get('/create_jobs', [App\Http\Controllers\ScrapeController::class, 'create_jobs']);
