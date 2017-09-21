<?php

require '../lib/GokuaiBase.class.php';
require '../lib/GokuaiAuth.class.php';
require '../lib/GokuaiFile.class.php';

// cloud server
$server = 'http://yk3-api-ent.gokuai.com';

// private server
//$server = 'http://10.1.10.100/m-open';

// generate client_id from Yunku Enterprise Admin Console
$client_id = 'YNYsJX2kX84G425RVdV5ajgGQ';
$client_secret = 'YvZNtqXfyqghSVZvw0zyyKUD7P0';

$ent_sdk = new GokuaiBase($client_id, $client_secret, $server);

// list all enterprise libraries
$ent_sdk->callAPI('POST', '/1/org/ls');
echo $ent_sdk->getHttpResponse() . PHP_EOL;
exit;

// get library information
$is_ok = $ent_sdk->callAPI('POST', '/1/org/info', ['org_id' => 417162]);
echo $ent_sdk->getHttpResponse() . PHP_EOL;
exit;

// bind library, get org_client_id and org_client_secret
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
echo $org_client_secret . PHP_EOL;

exit;


$org_client_id = 'wp9Bzl3eAQnu65r620lhlc9FNM';
$org_client_secret = 'GrzBcWppTfZSCiJI4LRnerWNng';

// use file sdk
$file_sdk = new GokuaiFile($org_client_id, $org_client_secret, $server);

// get file list
$is_ok = $file_sdk->callAPI('GET', '/1/file/link', ['fullpath' => '北京/cWOXUEXUBANCYQ04/20161117/01产品一队（试卷收集录入）', 'deadline' => -1, 'auth' => 'download']);
echo $file_sdk->getHttpResponse() . PHP_EOL;
