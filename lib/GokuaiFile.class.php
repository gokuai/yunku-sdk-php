<?php

if (!class_exists('GokuaiBase')) {
    require 'GokuaiBase.class.php';
}

class GokuaiFile extends GokuaiBase
{
    public function __construct($org_client_id, $org_client_secret, $server = null)
    {
        parent::__construct($org_client_id, $org_client_secret, $server);
    }

    /**
     * @param string $http_method POST or GET
     * @param string $uri
     * @param array  $parameters
     * @return bool
     */
    public function callAPI($http_method, $uri, array $parameters = [])
    {
        $parameters['org_client_id'] = $this->client_id;
        $parameters['dateline'] = time();
        $parameters['sign'] = $this->getSign($parameters);
        $this->sendRequest($this->server . $uri, $http_method, $parameters);
        return $this->isOK();
    }
}
