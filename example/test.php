<?php

require '../lib/GokuaiBase.class.php';
require '../lib/GokuaiFile.class.php';

$client_id = '';
$client_secret = '';

//enterprise sdk
$ent_sdk = new GokuaiBase($client_id, $client_secret);

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