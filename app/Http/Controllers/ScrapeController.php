<?php

namespace App\Http\Controllers;

use App\Jobs\ScrapeJob;
use App\Models\Team;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ScrapeController extends Controller
{
    public function create_jobs() {
        # Creates scrape jobs for all new or updated teams
        $query = DB::table('env')->where('key', 'last_checked')->get('value');
        $time_last_checked = $query->first()->value;

        if (is_null($time_last_checked))
            $time_last_checked = Carbon::minValue()->toDateTimeString();

        # Get all teams that have been created or updated since the last check
        $teams = Team::where('created_at', '>', $time_last_checked)
                            ->orWhere('updated_at', '>', $time_last_checked)
                            ->get();

        foreach ($teams as $team){
            if ($team->website == NULL)
                continue;

            $this->dispatch(new ScrapeJob($team));
        }
        
        DB::table('env')->where('key', 'last_checked')
            ->update(['value' => Carbon::now()->toDateTimeString()]);
        
        return "Success";
    }
}