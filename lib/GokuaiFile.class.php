<?php

if (!class_exists('GokuaiBase')) {
    require 'GokuaiBase.class.php';
}

class GokuaiFile extends GokuaiBase
{
    protected $api_url = 'http://a-lib.goukuai.cn';

    public function __construct($org_client_id, $org_client_secret)
    {
        $this->client_id = $org_client_id;
        $this->client_secret = $org_client_secret;
        $this->curlInit();
    }

    public function callAPI($http_method, $uri, array $data = [])
    {
        $data['org_client_id'] = $this->client_id;
        $data['dateline'] = time();
        $data['sign'] = $this->getSign($data);
        $this->sendRequest($this->api_url . $uri, $http_method, $data);
        return $this->isOK();
    }
}
