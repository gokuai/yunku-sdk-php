<?php

class GokuaiBase
{
    public $timeout = 300;
    public $connecttimeout = 10;
    protected static $user_agent = 'Yunku-SDK-PHP_1.0';
    protected $api_url = 'http://a.goukuai.cn';
    protected $curl;
    protected $http_code;
    protected $http_error;
    protected $response;

    protected $client_id;
    protected $client_secret;

    public function __construct($client_id, $client_secret)
    {
        $this->client_id = $client_id;
        $this->client_secret = $client_secret;
        $this->curlInit();
    }

    public function callAPI($http_method, $uri, array $data = [])
    {
        $data['client_id'] = $this->client_id;
        $data['dateline'] = time();
        $data['sign'] = $this->getSign($data);
        $this->sendRequest($this->api_url . $uri, $http_method, $data);
        return $this->isOK();
    }

    protected function getSign(array $arr)
    {
        if (!$arr) {
            return '';
        }
        ksort($arr);
        $data = implode("\n", $arr);
        $signature = base64_encode(hash_hmac('sha1', $data, $this->client_secret, true));
        return $signature;
    }

    public function isOK()
    {
        return !$this->http_error && $this->http_code < 400;
    }

    public function getHttpError()
    {
        return $this->http_error;
    }

    public function getHttpCode()
    {
        return $this->http_code;
    }

    public function getHttpResponse($json = false)
    {
        return $json ? json_decode($this->response, true) : $this->response;
    }

    protected function curlInit()
    {
        $this->curl = curl_init();
        curl_setopt($this->curl, CURLOPT_USERAGENT, self::$user_agent);
        curl_setopt($this->curl, CURLOPT_CONNECTTIMEOUT, $this->connecttimeout);
        curl_setopt($this->curl, CURLOPT_TIMEOUT, $this->timeout);
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($this->curl, CURLOPT_HEADER, false);
    }

    protected function sendRequest($url, $method, array $data = [])
    {
        $method = strtoupper($method);
        is_object($this->curl) or $this->curlInit();
        $fields_string = '';
        if ($data) {
            if (is_array($data)) {
                foreach ($data as $k => $v) {
                    $fields_string .= $k . '=' . rawurlencode($v) . '&';
                }
                $fields_string = rtrim($fields_string, '&');
            } else {
                $fields_string = $data;
            }
        }
        $method = strtoupper($method);
        switch ($method) {
            case 'GET':
                if ($fields_string) {
                    if (strpos($url, '?')) {
                        curl_setopt($this->curl, CURLOPT_URL, $url . '&' . $fields_string);
                    } else {
                        curl_setopt($this->curl, CURLOPT_URL, $url . '?' . $fields_string);
                    }
                }
                break;
            case 'POST':
                curl_setopt($this->curl, CURLOPT_URL, $url);
                curl_setopt($this->curl, CURLOPT_POST, true);
                curl_setopt($this->curl, CURLOPT_POSTFIELDS, $fields_string);
                break;
            default:
                curl_setopt($this->curl, CURLOPT_URL, $url);
                curl_setopt($this->curl, CURLOPT_CUSTOMREQUEST, $method);
                curl_setopt($this->curl, CURLOPT_POSTFIELDS, $fields_string);
                break;
        }

        $this->response = curl_exec($this->curl);
        $this->http_code = curl_getinfo($this->curl, CURLINFO_HTTP_CODE);
        $this->http_error = curl_error($this->curl);
        curl_close($this->curl);
    }
}