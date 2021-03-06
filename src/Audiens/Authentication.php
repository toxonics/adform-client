<?php

namespace Audiens\AdForm;

use Audiens\AdForm\Exception\OauthException;
use \League\OAuth2\Client\Provider\GenericProvider;
use \League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use League\OAuth2\Client\Token\AccessToken;

/**
 * Class Autentication
 */
class Authentication
{
    /**
     * @var AccessToken
     */
    protected $accessToken;

    /**
     * Class constructor
     *
     * @param string $username
     * @param string $password
     */
    public function __construct($username, $password)
    {
        $this->username = $username;
        $this->password = $password;

        $this->authenticate();
    }

    /**
     * Authenticate on AdForm API using the password grant
     *
     * @throws OauthException if authentication fails
     */
    public function authenticate()
    {
        $urlAccessToken = Client::BASE_URL.'/v1/token';

        // we are using a very simple password grant AdForm
        // doesn't event return a Refresh Token AF
        $provider = new GenericProvider([
            'clientId' => '',
            'clientSecret' => '',
            'redirectUri' => '',
            'urlAuthorize' => '',
            'urlAccessToken' => $urlAccessToken,
            'urlResourceOwnerDetails' => ''
        ]);

        try {
            $this->accessToken = $provider->getAccessToken('password', [
                'username' => $this->username,
                'password' => $this->password
            ]);
        } catch (IdentityProviderException $e) {
            throw OauthException::connect($e->getMessage());
        }
    }

    /**
     * Returns the Access Token, or try to reauthenticate if needed
     *
     * @return string
     */
    public function getAccessToken()
    {
        // maybe the token will expire in next 10 seconds
        $expiryCutoff = new \DateTime('+10 seconds');

        // if the token expires try to reauthenticate
        if (!$this->accessToken or $this->getExpires() < $expiryCutoff->getTimestamp()) {
            $this->authenticate();
        }

        return $this->accessToken->getToken();
    }

    /**
     * Returns the Expires timestamp of the Access Token
     *
     * @return int
     */
    public function getExpires()
    {
        return $this->accessToken->getExpires();
    }
}
