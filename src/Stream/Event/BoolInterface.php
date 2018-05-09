<?php

namespace Remorhaz\JSON\Parser\Stream\Event;

interface BoolInterface extends ScalarInterface
{

    public function asBool(): bool;
}
