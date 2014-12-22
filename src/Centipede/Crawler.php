<?php

namespace Centipede;

use Centipede\Authenticator\AuthenticatorInterface;
use Centipede\Authenticator\NullAuthenticator;
use Goutte\Client;
use Symfony\Component\DomCrawler\Crawler as DomCrawler;

class Crawler
{
    private $client;
    private $baseUrl;
    private $depth;

    public function __construct(
        $baseUrl,
        $depth = 1,
        AuthenticatorInterface $authenticator = null
    ) {
        $this->client = new Client();
        $this->baseUrl = $baseUrl;
        $this->depth = $depth;

        if (null === $authenticator) {
            $authenticator = new NullAuthenticator();
        }

        $authenticator->authenticate($this->client);
    }

    public function crawl(callable $callable = null)
    {
        $urls = [$this->baseUrl];

        $this->doCrawl(
            $this->baseUrl,
            $this->request($this->baseUrl, $callable),
            $this->depth,
            $callable,
            $urls
        );

        return $urls;
    }

    private function doCrawl($url, DomCrawler $crawler, $depth, callable $callable = null, array &$urls = [])
    {
        if (0 === $depth) {
            return;
        }

        foreach ($crawler->filter('a') as $node) {
            $href = $this->filterUrl($node->getAttribute('href'));

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
    }

    private function request($url, callable $callable = null)
    {
        $crawler = $this->client->request('GET', $url);

        if (null !== $callable) {
            $callable($url, $this->client->getResponse());
        }

        return $crawler;
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
