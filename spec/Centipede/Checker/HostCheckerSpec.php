<?php

namespace spec\Centipede\Checker;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class HostChekerSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith('github.com');
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Centipede\Checker\HostCheker');
    }

    function it_is_centipede_decider()
    {
        $this->shouldImplement('Centipede\Checker\CheckerInterface');
    }

    function it_decides_to_crawl_internal_urls()
    {
        $this->isCrawlable('http://github.com/umpirsky')->shouldReturn(true);
    }

    function it_decides_not_to_crawl_external_urls()
    {
        $this->isCrawlable('http://umpirsky.com/github')->shouldReturn(false);
    }
}
