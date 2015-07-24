<?php

namespace Centipede;

use Centipede\Filter\UrlFilter;
use Centipede\Filter\FilterInterface;
use Centipede\Checker\HostChecker;
use Centipede\Checker\CheckerInterface;
use Centipede\Extractor\UrlExtractor;
use Centipede\Extractor\ExtractorInterface;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Message\Response;
use GuzzleHttp\Message\FutureResponse;

class Crawler
{
    private $baseUrl;
    private $depth;
    private $client;
    private $filter;
    private $checker;
    private $extractor;

    private $urls = array();

    public function __construct($baseUrl, $depth = 1)
    {
        $this->baseUrl = $baseUrl;
        $this->depth = $depth;

        $this->client = new Client();
        $this->filter = new UrlFilter();
        $this->checker = new HostChecker(parse_url($baseUrl, PHP_URL_HOST));
        $this->extractor = new UrlExtractor();
    }

    public function crawl(callable $callable = null)
    {
        $response = $this->client->get($this->baseUrl, ['future' => true]);

        $this->doCrawl(
            $this->baseUrl,
            $response,
            $this->depth,
            $callable
        );

        $response->wait();

        return $this->urls;
    }

    public function setClient(ClientInterface $client)
    {
        $this->client = $client;

        return $this;
    }

    public function getClient()
    {
        return $this->client;
    }

    public function setFilter(FilterInterface $filter)
    {
        $this->filter = $filter;

        return $this;
    }

    public function setExtractor(ExtractorInterface $extractor)
    {
        $this->extractor = $extractor;

        return $this;
    }

    public function setChecker(CheckerInterface $checker)
    {
        $this->checker = $checker;

        return $this;
    }

    private function doCrawl($url, FutureResponse $response, $depth, callable $callable = null)
    {

        if (0 === $depth || array_key_exists($url, $this->urls)) {
            return;
        }

        if (null !== $callable) {
            $callable($url, $response, $depth);
        }

        $this->urls[$url] = (isset($this->urls[$url]) ? $this->urls[$url] : 0) + 1;

        $response->then(function (Response $response) use ($url, $depth, $callable) {

            $hrefs = $this->extractor->extract(
                $response->getBody()->getContents()
            );

            foreach ($hrefs as $href) {
                $href = $this->filter->filter($href, $url);
                if (!array_key_exists($href, $this->urls) && $this->checker->isCrawlable($href)) {
                    $this->doCrawl(
                        $href,
                        $this->client->get($href, ['future' => true]),
                        $depth - 1,
                        $callable
                    );
                }
            }
        })->done();
    }
}
