<?php

require __DIR__.'/../vendor/autoload.php';

var_dump((new Centipede\Crawler('http://dev.umpirsky.com'))->crawl());
