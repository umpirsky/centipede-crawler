<?php

namespace Centipede;

use GuzzleHttp\Client;
use GuzzleHttp\Message\Response;
use GuzzleHttp\Message\FutureResponse;

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

        $this->doCrawl(
            $this->baseUrl,
            $response = $this->request($this->baseUrl, $callable),
            $this->depth,
            $callable,
            $urls
        );

        $response->wait();

        return $urls;
    }

    private function doCrawl($url, FutureResponse $response, $depth, callable $callable = null, array &$urls = [])
    {
        if (null !== $callable) {
            $callable($url, $response);
        }

        if (0 === $depth) {
            return;
        }

        $response->then(function (Response $response) use ($url, $depth, $callable, &$urls) {
            foreach ($this->getUrls($response) as $href) {
                $href = $this->filterUrl($href);

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
        });
    }

    private function getUrls(Response $response)
    {
        $urls = [];

        $document = new \DOMDocument();
        $document->loadHTML($response->getBody()->getContents());
        foreach ($document->getElementsByTagName('a') as $node) {
          $urls[] = $node->getAttribute('href');
        }

        return $urls;
    }

    private function request($url, callable $callable = null)
    {
        return $this->client->get($url, ['future' => true]);
    }

    private function filterUrl($url)
    {
        $url = rtrim($url, '/');

        if (false !== $position = strpos($url, '#')) {
            $url = substr($url, 0, $position);
        }

        return $url;
    }

    private function shouldCrawl($url)
    {
        if (empty($url)) {
            return false;
        }

        $host = parse_url($url, PHP_URL_HOST);
        if (null === $host) {
            return true;
        }

        return $host === parse_url($this->baseUrl, PHP_URL_HOST);
    }
}
