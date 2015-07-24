<?php

namespace Centipede\Filter;

class UrlFilter implements FilterInterface
{
    public function filter($url, $previousUrl = null)
    {
        // These are all the settings we'll need to recreate the url.
        // They're also the different keys that parse_url can return.
        $parseUrlSettings = array('scheme' => '', 'host' => '', 'path' => '', 'port' => '', 'query' => '');

        $parseValueUrl = array_merge($parseUrlSettings, parse_url($url));

        if (strpos($url, '#') === 0) {
            return null;
        }

        // Return the value if we already have an absolute URL
        if (isset($parseValueUrl['scheme'])) {
            return $parseValueUrl['scheme'].'://'.$parseValueUrl['host'].$parseValueUrl['port'].$parseValueUrl['path'].($parseValueUrl['query'] ? ('?'.$parseValueUrl['query']) : '');
        }


        $parsePreviousUrl = array_merge($parseUrlSettings, parse_url($previousUrl));

        $path = $parseValueUrl['path'];

        if (strpos($path, '/') !== 0) {
            $previousPath = rtrim($parsePreviousUrl['path'], '/');
            $path = ($previousPath ? ($previousPath.'/') : '' ) .$path;
        }

        // We also replace the host, in case of protocolless urls like "//domain.com/"
        $parseValueUrl = array_merge(array(
            'scheme' => $parsePreviousUrl['scheme'],
            'host' => $parsePreviousUrl['host'],
            'path' => $path,
        ), $parseValueUrl);

        $parts = array_merge($parseUrlSettings, $parseValueUrl);

        return $parts['scheme'].'://'.$parts['host'].$parts['port'].$parts['path'].($parts['query'] ? ('?'.$parts['query']) : '');
    }

}
