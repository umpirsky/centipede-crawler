<?php

namespace Centipede\Extractor;

class UrlExtractor implements ExtractorInterface
{
    public function extract($value)
    {
        $urls = [];

        $document = new \DOMDocument();
        $document->loadHTML($value);
        foreach ($document->getElementsByTagName('a') as $node) {
          $urls[] = $node->getAttribute('href');
        }

        return $urls;
    }
}
