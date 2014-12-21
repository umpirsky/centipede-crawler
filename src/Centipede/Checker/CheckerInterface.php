<?php

namespace Centipede\Checker;

interface CheckerInterface
{
    public function isCrawlable($url);
}
