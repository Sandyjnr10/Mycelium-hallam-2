<?php

//echo dmtest('1638977411671814166', "hello world");
echo dmTestByHandle("funarfun", "hello world");

function dmTestByHandle($handle, $message)
{
    $response = json_decode(getIDByUsername($handle), true);

    //store id in database to avoid redoing id request this request

    if ($response == null) {
        echo "id is null";
        return;
    }

    if (!isset($response['data'])) {
        echo "unsuccessful request\n";
        print_r($response);
        return;
    }

    if (!isset($response['data']['id'])) {
        echo "cannot find id in response";
        return;
    }

    echo "successfully retrieved id<br>";
    $id = $response['data']['id'];

    return dmtest($id, $message);
}

function dmtest($recipientID, $message)
{
    $params = [
        'recipientID' => $recipientID,
        'message' => $message,
    ];
    return TwitterRequest('create-direct-message', $params);
}

function getIDByUsername($handle = "funarfun")
{
    $params = [
        'user' => $handle,
    ];
    return TwitterRequest('get-userID', $params);
}

function TwitterRequest($requestType, $params)
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

        $method = 'POST';
        $url = 'https://api.twitter.com/2/dm_conversations/with/' . $params['recipientID'] . '/messages';
        return OAuth1Request($url, $method, $keys, $oauth, $params['message']);
    }

    if ($requestType == 'get-userID') {
        if (!isset($params['user']))
            return "No username given";

        $method = 'GET';
        $url = 'https://api.twitter.com/2/users/by/username/' . $params['user'];
        return OAuth1Request($url, $method, $keys, $oauth, $params['user']);
    }
}

function OAuth1Request($url, $method, $keys, $oauth, $payload)
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
    $baseInfo = $method . "&" . rawurlencode($url) . '&' . rawurlencode(implode('&', $headers));

    $encodeKey = rawurlencode($consumer_secret) . '&' . rawurlencode($access_secret);
    $oauthSignature = base64_encode(hash_hmac('sha1', $baseInfo, $encodeKey, true));
    $encodedSignature = urlencode($oauthSignature);


    $curl = curl_init();

    if ($payload != "none") {
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_POSTFIELDS => '{"text": "' . $payload . '"}',
            CURLOPT_HTTPHEADER => array(
                'Authorization: OAuth oauth_consumer_key="' . $consumer . '",oauth_token="' . $access_token . '",oauth_signature_method="HMAC-SHA1",oauth_timestamp="' . time() . '",oauth_nonce="' . time() . '",oauth_version="1.0",oauth_signature="' . $encodedSignature . '"',
                'Content-Type: application/json'
            ),
        ));
    } else {
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Authorization: OAuth oauth_consumer_key="' . $consumer . '",oauth_token="' . $access_token . '",oauth_signature_method="HMAC-SHA1",oauth_timestamp="' . time() . '",oauth_nonce="' . time() . '",oauth_version="1.0",oauth_signature="' . $encodedSignature . '"',
                'Content-Type: application/json'
            ),
        ));
    }


    $response = curl_exec($curl);

    curl_close($curl);

    $response = curl_exec($curl);
    curl_close($curl);

    return $response;
}
