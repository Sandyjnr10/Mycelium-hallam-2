<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class TwitterPostStatusJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $status;
    protected $url;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
        $this->status = $_REQUEST['content'];
        $this->url = 'https://api.twitter.com/2/tweets';
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->Tweet();
    }

    public function Tweet()
    {
        $keys = array(
            'consumer' => getenv('TWITTER_TEST_CONSUMER'),
            'consumer_secret' => getenv('TWITTER_TEST_CONSUMER_SECRET'),
            'access_token' => getenv('TWITTER_TEST_ACCESS_TOKEN'),
            'access_secret' => getenv('TWITTER_TEST_ACCESS_SECRET')
        );

        $oauth = [
            'oauth_consumer_key' => $keys['consumer'],
            'oauth_nonce' => time(),
            'oauth_signature_method' => 'HMAC-SHA1',
            'oauth_token' => $keys['access_token'],
            'oauth_timestamp' => time(),
            'oauth_version' => '1.0',
        ];

        $headers = [];
        ksort($oauth);
        foreach ($oauth as $key => $value) {
            $headers[] = "$key=" . rawurlencode($value);
        }
        $baseInfo = 'POST' . "&" . rawurlencode($this->url) . '&' . rawurlencode(implode('&', $headers));

        $encodeKey = rawurlencode($keys['consumer_secret']) . '&' . rawurlencode($keys['access_secret']);
        $oauthSignature = base64_encode(hash_hmac('sha1', $baseInfo, $encodeKey, true));
        $encodedSignature = urlencode($oauthSignature);


        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => '{"text": "' . $this->status . '"}',
            CURLOPT_HTTPHEADER => array(
                'Authorization: OAuth oauth_consumer_key="' . $keys['consumer'] . '",oauth_token="' . $keys['access_token'] . '",oauth_signature_method="HMAC-SHA1",oauth_timestamp="' . time() . '",oauth_nonce="' . time() . '",oauth_version="1.0",oauth_signature="' . $encodedSignature . '"',
                'Content-Type: application/json'
            ),
        ));


        $response = curl_exec($curl);

        curl_close($curl);

        $response = curl_exec($curl);
        curl_close($curl);
    }
}
