<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;


class TwitterNewDirectMessageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $recipientID;
    protected $body;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        $req = explode(",", $_REQUEST['content']);

        $this->recipientID = $req[0];
        $this->body = $req[1];
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $params = [
            'recipientID' => $this->recipientID,
            'message' => $this->body,
        ];
        $this->TwitterRequest('create-direct-message', $params);
    }

    public function TwitterRequest($requestType, $params)
    {
        $keys = [
            'consumer' => getenv('TWITTER_TEST_CONSUMER'),
            'consumer_secret' => getenv('TWITTER_TEST_CONSUMER_SECRET'),
            'access_token' => getenv('TWITTER_TEST_ACCESS_TOKEN'),
            'access_secret' => getenv('TWITTER_TEST_ACCESS_SECRET')
        ];

        $oauth = [
            'oauth_consumer_key' => $keys['consumer'],
            'oauth_nonce' => time(),
            'oauth_signature_method' => 'HMAC-SHA1',
            'oauth_token' => $keys['access_token'],
            'oauth_timestamp' => time(),
            'oauth_version' => '1.0',
        ];

        if ($requestType == 'create-direct-message') {
            if (!isset($params['recipientID']))
                return "No participant ID given";

            $url = 'https://api.twitter.com/2/dm_conversations/with/' . $params['recipientID'] . '/messages';
            return $this->OAuth1Request($url, $keys, $oauth, $params['message']);
        }
    }

    public function OAuth1Request($url, $keys, $oauth, $payload)
    {
        $consumer = $keys['consumer'];
        $consumer_secret = $keys['consumer_secret'];
        $access_token = $keys['access_token'];
        $access_secret = $keys['access_secret'];

        $headers = [];
        ksort($oauth);
        foreach ($oauth as $key => $value) {
            $headers[] = "$key=" . rawurlencode($value);
        }
        $baseInfo = 'POST' . "&" . rawurlencode($url) . '&' . rawurlencode(implode('&', $headers));

        $encodeKey = rawurlencode($consumer_secret) . '&' . rawurlencode($access_secret);
        $oauthSignature = base64_encode(hash_hmac('sha1', $baseInfo, $encodeKey, true));
        $encodedSignature = urlencode($oauthSignature);


        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => '{"text": "' . $payload . '"}',
            CURLOPT_HTTPHEADER => array(
                'Authorization: OAuth oauth_consumer_key="' . $consumer . '",oauth_token="' . $access_token . '",oauth_signature_method="HMAC-SHA1",oauth_timestamp="' . time() . '",oauth_nonce="' . time() . '",oauth_version="1.0",oauth_signature="' . $encodedSignature . '"',
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        echo $response;
        return $response;
    }

    static function TwitterResponseHandler($response)
    {
        $responseDecoded = json_decode($response, true);

        if (isset($responseDecoded['data']))
            return "success";
        else if (isset($responseDecoded['detail']) and isset($responseDecoded['status']))
            return "ERROR: 'code " . $responseDecoded['status'] . "' 'reason: " . $responseDecoded['detail'] . "'";
        else
            return $response;
    }
}
