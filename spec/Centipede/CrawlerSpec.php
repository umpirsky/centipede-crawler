<?php

namespace spec\Centipede;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class CrawlerSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith('https://github.com');
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Centipede\Crawler');
    }
}
