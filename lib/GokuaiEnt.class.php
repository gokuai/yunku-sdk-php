<?php

if (!class_exists('GokuaiBase')) {
    require 'GokuaiBase.class.php';
}

class GokuaiEnt extends GokuaiBase
{
    protected $api_url = 'http://a-lib.goukuai.cn';
    const TOKEN_TYPE_ENT = 'ent';
    private $token_type;
    private $token;

    public function __construct($token, $client_id, $client_secret, $token_type = self::TOKEN_TYPE_ENT)
    {
        $this->token = $token;
        $this->client_id = $client_id;
        $this->client_secret = $client_secret;
        $this->token_type = $token_type;
        $this->curlInit();
    }

    public function callAPI($http_method, $uri, array $data = [])
    {
        $data['token_type'] = $this->token_type;
        $data['token'] = $this->token;
        $data['dateline'] = time();
        $data['sign'] = $this->getSign($data);
        $this->sendRequest($this->api_url . $uri, $http_method, $data);
        return $this->isOK();
    }
}
