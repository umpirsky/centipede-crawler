<?php

namespace spec\Centipede\Filter;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class UrlFilterSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Centipede\Filter\UrlFilter');
    }

    function it_is_centipede_filter()
    {
        $this->shouldImplement('Centipede\Filter\FilterInterface');
    }

    function it_filters_url_hash()
    {
        $this->filter('https://github.com#hash')->shouldReturn('https://github.com');
    }

    function it_filters_trailing_slash()
    {
        $this->filter('https://github.com/')->shouldReturn('https://github.com/');
    }
}
