<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Team;
use App\Models\TeamContact;
use Goutte\Client;
use Symfony\Component\DomCrawler\Crawler;

class ScrapeJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $team;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Team $team)
    {
        $this->queue = 'scrape';

        $this->team = $team;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $team = $this->team;
        if ($team == null)
            return null;
        
        if ($team->contacts()->get()->isNotEmpty())
            return null;
        
        $website = $team->website;
        if (is_null($website)) {
           return null;
        }

        $client = new Client();
        $crawler = $client->request('GET', $website);

        // WIP: currently looks though every anchor on the html page and selects ones with strings of intrest
        $crawler->filter('a')->each(function (Crawler $node) use (&$facebookUrl, &$twitterUrl, &$instagramUrl) {
            $href = $node->attr('href');
            if (strpos($href, 'facebook.com') !== false || strpos($href, 'fb.com') !== false) {
                $facebookUrl = $href;
            } else if (strpos($href, 'twitter.com') !== false) {
                $twitterUrl = $href;
            } else if (strpos($href, 'instagram.com') !== false) {
                $instagramUrl = $href;
            }
        });

        if (isset($facebookUrl))
            TeamContact::create(['team_id' => $team->id,
                                'handle' => trim(substr($facebookUrl, 25), '/'), 
                                'website' => $facebookUrl]);
        if (isset($twitterUrl))
            TeamContact::create(['team_id' => $team->id,
                                'handle' => trim(substr($twitterUrl, 20), '/'), 
                                'website' => $twitterUrl]);
        if (isset($instagramUrl))
            TeamContact::create(['team_id' => $team->id,
                                'handle' => trim(substr($instagramUrl, 26), '/'), 
                                'website' => $instagramUrl]);
        
    }
}
