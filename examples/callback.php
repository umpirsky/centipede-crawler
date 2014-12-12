<?php

require __DIR__.'/../vendor/autoload.php';

(new Centipede\Crawler('http://dev.umpirsky.com'))->crawl(function ($url) {
    echo $url.PHP_EOL;
});
