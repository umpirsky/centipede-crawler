<?php

namespace Centipede\Filter;

class UrlFilter
{
    public function filter($url)
    {
        $url = rtrim($url, '/');

        if (false !== $position = strpos($url, '#')) {
            $url = substr($url, 0, $position);
        }

        return $url;
    }
}
