<?php

namespace Remorhaz\JSON\Parser;

interface BoolInterface extends ScalarInterface
{

    public function asBool(): bool;
}
