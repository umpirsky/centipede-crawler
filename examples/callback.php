<?php

require __DIR__.'/../vendor/autoload.php';

use Centipede\Crawler;
use GuzzleHttp\Message\ResponseInterface;

(new Crawler('http://dev.umpirsky.com'))->crawl(function ($url, ResponseInterface $response) {
    printf('(%d) %s%s', $response->getStatusCode(), $url, PHP_EOL);
});
