<?php

namespace Centipede\Filter;

class UrlFilter implements FilterInterface
{
    public function filter($value)
    {
        $value = rtrim($value, '/');

        if (false !== $position = strpos($value, '#')) {
            $value = substr($value, 0, $position);
        }

        return $value;
    }
}
