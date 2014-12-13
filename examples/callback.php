<?php

require __DIR__.'/../vendor/autoload.php';

use Centipede\Crawler;
use Symfony\Component\BrowserKit\Response;

(new Crawler('http://dev.umpirsky.com'))->crawl(function ($url, Response $response) {
    printf('(%d) %s%s', $response->getStatus(), $url, PHP_EOL);
});
