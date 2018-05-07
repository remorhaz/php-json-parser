<?php

namespace Remorhaz\JSON\Parser;

interface StringValueInterface
{

    public function asArray(): array;

    public function asString(): string;
}
