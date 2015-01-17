<?php

namespace Centipede\Extractor;

class UrlExtractor implements ExtractorInterface
{
    public function extract($value)
    {
        $urls = [];

        $document = new \DOMDocument();

        libxml_use_internal_errors(true);
        $document->loadHTML($value);
        libxml_use_internal_errors(false);

        foreach ($document->getElementsByTagName('a') as $node) {
            $urls[] = $node->getAttribute('href');
        }

        return $urls;
    }
}
