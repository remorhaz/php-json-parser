<?php

namespace Remorhaz\JSON\Parser\Stream\Event;

interface LengthInterface
{

    public function inBytes(): int;

    public function inSymbols(): int;
}
