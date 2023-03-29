<?php

namespace App\Http\Requests;

class AuthenticateTwitterAppForAccount
{
    private $consumer;
    private $consumerSecret;
    private $accessToken;
    private $accessSecret;
    private $callbackURL;

    public function __construct()
    {
        $this->consumer = getenv('TWITTER_TEST_CONSUMER');
        $this->consumerSecret = getenv('TWITTER_TEST_CONSUMER_SECRET');
        $this->callbackURL = getenv('CALLBACK_URL');
    }

    public function getDataForLogin()
    {
        $reqToken = $this->getRequestToken();
        $reqToken['twitter_login_url'] = $this->getLoginUrl($reqToken);

        return $reqToken;
    }

    public function getLoginUrl($reqToken)
    {
        $url = 'https://api.twitter.com/oauth/authorize';

        if ($reqToken['status'] == 'ok') {
            $params = array(
                'oauth_token' => $reqToken['response']['oauth_token']
            );
            $url .= '?' . http_build_query($params);
        }

        return $url;
    }

    public function getRequestToken()
    {
        $method = 'POST';
        $url = 'https://api.twitter.com/oauth/request_token';
        $oauth = array(
            'oauth_callback' => $this->callbackURL,
            'oauth_consumer_key' => $this->consumer,
            'oauth_nonce' => time(),
            'oauth_signature_method' => 'HMAC-SHA1',
            'oauth_timestamp' => time(),
            'oauth_version' => '1.0',
        );
        $oauth['oauth_signature'] = $this->getOAuthSignature($method, $url, $oauth);

        $params = array(
            'method' => $method,
            'endpoint' => $url,
            'authorization' => $this->getAuthString($oauth),
            'url_params' => array()
        );

        return $this->oauthRequest($params);
    }

    public function oauthRequest($params)
    {
        $curlOptions = array(
            CURLOPT_URL => $params['endpoint'],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER => TRUE,
            CURLOPT_HTTPHEADER => array(
                'Accept: application/json',
                $params['authorization'],
                'Expect:'
            )
        );

        if ($params['method'] == 'POST') {
            $curlOptions[CURLOPT_POST] = true;
            $curlOptions[CURLOPT_POSTFIELDS] = http_build_query($params['url_params']);
        } elseif ($params['method'] == 'GET') {
            $curlOptions[CURLOPT_URL] .= '?' . http_build_query($params);
        }

        $curl = curl_init();
        curl_setopt_array($curl, $curlOptions);
        $response = curl_exec($curl);

        if (200 == curl_getinfo($curl, CURLINFO_HTTP_CODE)) {
            $status = 'ok';

            //message is for errors, http code 200 means all good
            $message = '';
        } else {
            $status = 'fail';

            $message = isset($response['errors'][0]['message']) ? $response['errors'][0]['message'] : 'Unauthorized';
        }
        // make call
        $apiResponse = curl_exec($curl);

        // get response parts
        $responseParts = explode("\r\n\r\n", $apiResponse);

        // body contains the good stuff
        $responseBody = array_pop($responseParts);
        // json decode body
        $responseBodyJson = json_decode($responseBody);

        if (
            json_last_error() == JSON_ERROR_NONE
        ) { // decode json string
            $response = json_decode($responseBody, true);
        } else { // parse str to response
            parse_str($responseBody, $response);
        }

        curl_close($curl);

        return array(
            'status' => $status,
            'message' => $message,
            'response' => $response,
            'endpoint' => $curlOptions[CURLOPT_URL],
            'authorization' => $params['authorization']
        );
    }

    public function getOAuthSignature($method, $url, $params)
    {
        $headers = [];
        ksort($params);
        foreach ($params as $key => $value) {
            $headers[] = $key . "=" . rawurlencode($value);
        }
        $baseInfo = $method . "&" . rawurlencode($url) . '&' . rawurlencode(implode('&', $headers));

        $this->accessSecret ? rawurlencode($this->accessSecret) : '';
        $encodeKey = rawurlencode($this->consumerSecret) . '&' . rawurlencode($this->accessSecret);
        return base64_encode(hash_hmac('sha1', $baseInfo, $encodeKey, true));
    }

    public function getAuthString($oauthSignature)
    {
        $authString = 'Authorization: OAuth';

        $i = 0;
        foreach ($oauthSignature as $key => $value) {
            if ($i == 0)
                $authString .= " ";
            else
                $authString .= ",";

            $authString .= rawurlencode($key) . '=' . rawurlencode($value);

            $i++;
        }

        return $authString;
    }
}
