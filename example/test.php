<?php

require '../lib/GokuaiAuth.class.php';
require '../lib/GokuaiEnt.class.php';
require '../lib/GokuaiFile.class.php';

$client_id = '';
$client_secret = '';

//personal account or enterprise admin account
$username = '';
$password = '';

//personal;
//$token_type = GokuaiBase::TOKEN_TYPE_PERSONAL;
//$grant_type = GokuaiAuth::GRANT_TYPE_PERSONAL_PASSWORD;

//enterprise admin
$token_type = GokuaiBase::TOKEN_TYPE_ENTERPRISE;
$grant_type = GokuaiAuth::GRANT_TYPE_ENTERPRISE_PASSWORD;



//get token
$auth = new GokuaiAuth($client_id, $client_secret);
$is_ok = $auth->token($username, $password, GokuaiAuth::GRANT_TYPE_ENTERPRISE_PASSWORD);
if (!$is_ok) {
    echo $auth->getHttpResponse() . PHP_EOL;
    exit;
}
$result_json = $auth->getHttpResponse(true);
$token = $result_json['access_token'];



//enterprise sdk
$ent_sdk = new GokuaiEnt($token, $client_id, $client_secret, $token_type);

//libraries
$is_ok = $ent_sdk->callAPI('GET', '/1/org/ls');
echo $ent_sdk->getHttpResponse() . PHP_EOL;

//library info
$is_ok = $ent_sdk->callAPI('POST', '/1/org/info', ['org_id' => 417162]);
echo $ent_sdk->getHttpResponse() . PHP_EOL;

//bind library, get org_client_id and org_client_secret
$is_ok = $ent_sdk->callAPI('POST', '/1/org/bind', ['org_id' => 417162]);
if (!$is_ok) {
    echo $ent_sdk->getHttpResponse() . PHP_EOL;
    exit;
}
$result_json = $ent_sdk->getHttpResponse(true);
if (!$result_json) {
    echo $ent_sdk->getHttpResponse() . PHP_EOL;
    exit;
}
$org_client_id = $result_json['org_client_id'];
$org_client_secret = $result_json['org_client_secret'];

echo $org_client_id . PHP_EOL;



//file sdk
$file_sdk = new GokuaiFile($org_client_id, $org_client_secret);

//file list
$is_ok = $file_sdk->callAPI('GET', '/1/file/ls');
echo $file_sdk->getHttpResponse() . PHP_EOL;