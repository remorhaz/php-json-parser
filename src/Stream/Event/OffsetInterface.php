<?php

namespace Remorhaz\JSON\Parser\Stream\Event;

interface OffsetInterface
{

    public function inBytes(): int;

    public function inSymbols(): int;

    public function inLines(): int;
}
