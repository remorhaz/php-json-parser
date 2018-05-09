<?php

namespace Remorhaz\JSON\Parser\Stream\Event;

interface StringValueInterface
{

    public function asArray(): array;

    public function asString(): string;
}
