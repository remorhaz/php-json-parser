<?php

namespace Remorhaz\JSON\Parser\Stream\Event;

interface NumberValueInterface
{

    public function getInt(): StringValueInterface;

    public function getFrac(): StringValueInterface;

    public function isNegative(): bool;

    public function getExp(): StringValueInterface;

    public function isExpNegative(): bool;
}
