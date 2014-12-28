# Centipede Crawler  [![Build Status](https://travis-ci.org/umpirsky/centipede-crawler.svg?branch=master)](https://travis-ci.org/umpirsky/centipede-crawler)

Crawls all unique links.

## Usage

```php
$urls = (new Centipede\Crawler('http://dev.umpirsky.com'))->crawl();
```

## Asynchronous

```php
(new Centipede\Crawler('http://dev.umpirsky.com'))->crawl(function ($url, GuzzleHttp\Message\FutureResponse $response) {
    printf('(%d) %s', $response->getStatusCode(), $url);
});
```
