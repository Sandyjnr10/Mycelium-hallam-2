<?php

namespace App\Http\Controllers;

use App\Jobs\MastodonPostStatusJob;
use App\Jobs\TwitterPostTweetJob;
use Illuminate\Http\Request;

class BotDashboardController extends Controller
{
    //
    function index()
    {
        return view('bot-dashboard');
    }
}
