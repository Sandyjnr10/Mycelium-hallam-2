<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;

$date_time = Carbon::now()->format('Y-m-d H:i:s');

class DashboardController extends Controller
{
    public function index()
    {
        $date_time = Carbon::now();
        return view('dashboard', ['date_time' => $date_time]);

    }
}





