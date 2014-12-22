<?php

namespace Centipede\Authenticator;

use Symfony\Component\BrowserKit\Client;

interface AuthenticatorInterface
{
    public function authenticate(Client $client);
}