<?php

if (!class_exists('GokuaiBase')) {
    require 'GokuaiBase.class.php';
}

class GokuaiAuth extends GokuaiBase
{
    protected $server = 'http://yk3-api.gokuai.com';

    const GRANT_TYPE_PERSONAL_PASSWORD = 'password';
    const GRANT_TYPE_EXCHANGE_TOKEN = 'exchange_token';

    /**
     * @param string $username   account
     * @param string $password   password
     * @param string $grant_type 'password' or 'exchange_token'
     * @return bool
     */
    public function token($username, $password, $grant_type)
    {
        if (preg_match('/[\\/\\\\]/', $username)) {
            $password_encoded = base64_encode($password);
        } else {
            $password_encoded = md5($password);
        }
        $data = [
            'username' => $username,
            'password' => $password_encoded,
            'grant_type' => $grant_type
        ];
        return $this->callAPI('post', '/oauth2/token', $data);
    }

    public function exchangeToken(array $params)
    {
        $params['grant_type'] = self::GRANT_TYPE_EXCHANGE_TOKEN;
        return $this->callAPI('post', '/oauth2/token2', $params);
    }
}
