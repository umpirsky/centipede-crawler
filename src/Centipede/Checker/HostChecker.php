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
        if (empty($url) || preg_match('/^tel:.*/i', $url)) {
            return false;
        }

        $host = parse_url($url, PHP_URL_HOST);
        if (null === $host) {
            return true;
        }

        $scheme = parse_url($url, PHP_URL_SCHEME);
        if (null !== $scheme && !preg_match('/^https?/', $scheme)) {
            return false;
        }

        return $host === $this->host;
    }
}
