<?php

namespace Centipede\Checker;

class HostChecker implements CheckerInterface
{
    private $host;

    public function __construct($host)
    {
        $this->host = $host;
    }

    public function isCrawlable($url)
    {
        if (empty($url)) {
            return false;
        }

        $host = parse_url($url, PHP_URL_HOST);
        if (null === $host) {
            return true;
        }

        return $host === $this->host;
    }
}
