<?php

namespace Centipede\Authenticator;

use Symfony\Component\BrowserKit\Client;
use Symfony\Component\BrowserKit\Cookie;

class SessionAuthenticator implements AuthenticatorInterface
{
    protected $sessionName;

    protected $sessionId;

    public function __construct($sessionName, $sesisonId)
    {
        $this->sessionName = $sessionName;
        $this->sessionId = $sesisonId;
    }

    public function authenticate(Client $client)
    {
        $client->getCookieJar()->set(new Cookie($this->sessionName, $this->sessionId));
    }
}