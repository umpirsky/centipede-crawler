<?php

namespace Centipede;

use Goutte\Client;

class Crawler
{
    private $client;
    private $baseUrl;
    private $depth;

    public function __construct($baseUrl, $depth = 1)
    {
        $this->client = new Client();
        $this->baseUrl = $baseUrl;
        $this->depth = $depth;
    }

    public function crawl(callable $callable = null)
    {
        return $this->doCrawl($this->baseUrl, $this->depth, $callable);
    }

    private function doCrawl($url, $depth, callable $callable = null, array &$urls = [])
    {
        if (0 === $depth) {
            return $urls;
        }

        if (null !== $callable) {
            $callable($url);
        }

        $urls[] = $url;

        foreach ($this->client->request('GET', $url)->filter('a') as $node) {
            $href = $node->getAttribute('href');

            if (!in_array($href, $urls) && $this->shouldCrawl($href)) {
                $this->doCrawl($href, $depth - 1, $callable, $urls);

                if (null !== $callable) {
                    $callable($href);
                }

                $urls[] = $href;
            }
        }

        return $urls;
    }

    private function shouldCrawl($url)
    {
        return parse_url($url, PHP_URL_HOST) === parse_url($this->baseUrl, PHP_URL_HOST);
    }
}
