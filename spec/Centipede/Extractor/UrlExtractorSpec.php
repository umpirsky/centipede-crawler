<?php

namespace spec\Centipede\Extractor;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class UrlExtractorSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Centipede\Extractor\UrlExtractor');
    }

    function it_is_centipede_extractor()
    {
        $this->shouldImplement('Centipede\Extractor\ExtractorInterface');
    }

    function it_extracts_urls()
    {
        $this
            ->extract('<a href="https://github.com"><a href="http://umpirsky.com">')
            ->shouldReturn(['https://github.com', 'http://umpirsky.com'])
        ;
    }
}
