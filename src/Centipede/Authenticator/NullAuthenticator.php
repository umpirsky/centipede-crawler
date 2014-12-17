<?php

namespace Centipede\Authenticator;

use Symfony\Component\BrowserKit\Client;

class NullAuthenticator implements AuthenticatorInterface
{
    public function authenticate(Client $client)
    {
        // Nothing happends
    }
}