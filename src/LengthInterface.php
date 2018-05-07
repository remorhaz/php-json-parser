<?php

namespace Remorhaz\JSON\Parser;

interface LengthInterface
{

    public function inBytes(): int;

    public function inSymbols(): int;
}
