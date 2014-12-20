<?php

require __DIR__.'/../vendor/autoload.php';

use Centipede\Crawler;
use GuzzleHttp\Message\FutureResponse;

(new Crawler('http://dev.umpirsky.com'))->crawl(function ($url, FutureResponse $response) {
    printf('(%d) %s%s', $response->getStatusCode(), $url, PHP_EOL);
});
