<?php

namespace Centipede\Filter;

class UrlFilter implements FilterInterface
{
    private $baseUrl;

    public function __construct($baseUrl)
    {
        $this->baseUrl = rtrim($baseUrl, '/');
    }

    public function filter($value)
    {
        if(strpos($value, "javascript:") === 0 || strpos($value, "mailto:") === 0) {
            return false;
        }

        $value = rtrim($value, '/');

        if (false !== $position = strpos($value, '#')) {
            $value = substr($value, 0, $position);
        }

        if (null !== parse_url($value, PHP_URL_SCHEME)) {
            return $value;
        }

        return $this->baseUrl.$value;
    }
}
