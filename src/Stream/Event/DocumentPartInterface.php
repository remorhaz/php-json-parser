<?php

namespace Remorhaz\JSON\Parser\Stream\Event;

interface DocumentPartInterface
{

    public function getOffset(): OffsetInterface;

    public function getLength(): LengthInterface;
}
