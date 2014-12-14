<?php

namespace Centipede;

use Goutte\Client;
use Symfony\Component\DomCrawler\Crawler as DomCrawler;

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
        $urls = [$this->baseUrl];

        return $this->doCrawl(
            $this->baseUrl,
            $this->request($this->baseUrl, $callable),
            $this->depth,
            $callable,
            $urls
        );
    }

    private function doCrawl($url, DomCrawler $crawler, $depth, callable $callable = null, array &$urls = [])
    {
        if (0 === $depth) {
            return $urls;
        }

        foreach ($crawler->filter('a') as $node) {
            $href = $node->getAttribute('href');

            if (!in_array($href, $urls) && $this->shouldCrawl($href)) {
                $this->doCrawl(
                    $href,
                    $this->request($href, $callable),
                    $depth - 1,
                    $callable,
                    $urls
                );

                $urls[] = $href;
            }
        }

        return $urls;
    }

    private function request($url, callable $callable = null)
    {
        $crawler = $this->client->request('GET', $url);

        if (null !== $callable) {
            $callable($url, $this->client->getResponse());
        }

        return $crawler;
    }

    private function shouldCrawl($url)
    {
        $host = parse_url($url, PHP_URL_HOST);
        if (null === $host) {
            return true;
        }

        return $host === parse_url($this->baseUrl, PHP_URL_HOST);
    }
}
