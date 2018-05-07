<?php

namespace Remorhaz\JSON\Parser;

interface OffsetInterface
{

    public function inBytes(): int;

    public function inSymbols(): int;

    public function inLines(): int;
}
