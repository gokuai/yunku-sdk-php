<?php

require '../lib/GokuaiAuth.class.php';
require '../lib/GokuaiEnt.class.php';

$client_id = '';
$client_secret = '';

$username = '';
$password = '';

$auth = new GokuaiAuth($client_id, $client_secret);
$is_ok = $auth->token($username, $password, GokuaiAuth::GRANT_TYPE_ENT_PASSWORD);
if ($is_ok) {
    $result = $auth->getHttpResponse(true);
    $sdk = new GokuaiEnt($result['access_token'], $client_id, $client_secret, 'ent');
    $sdk->callAPI('get', '/1/org/ls');
    echo $sdk->getHttpResponse();
} else {
    echo $auth->getHttpResponse();
}
