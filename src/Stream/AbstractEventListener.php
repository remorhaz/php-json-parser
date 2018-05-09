<?php

namespace Remorhaz\JSON\Parser\Stream;

use Remorhaz\JSON\Parser\Stream\Event;

abstract class AbstractEventListener implements EventListenerInterface
{

    public function onBeginDocument(): void
    {
    }

    public function onEndDocument(): void
    {
    }

    public function onBeginObject(Event\OffsetInterface $offset): void
    {
    }

    public function onEndObject(Event\DocumentPartInterface $part): void
    {
    }

    public function onBeginProperty(Event\StringInterface $name, int $index): void
    {
    }

    public function onEndProperty(Event\StringInterface $name, int $index, Event\DocumentPartInterface $part): void
    {
    }

    public function onBeginArray(Event\OffsetInterface $offset): void
    {
    }

    public function onEndArray(Event\DocumentPartInterface $part): void
    {
    }

    public function onBeginElement(int $index): void
    {
    }

    public function onEndElement(int $index, Event\DocumentPartInterface $part): void
    {
    }

    public function onString(Event\StringInterface $string): void
    {
    }

    public function onBool(Event\BoolInterface $bool): void
    {
    }

    public function onNull(Event\NullInterface $null): void
    {
    }

    public function onNumber(Event\NumberInterface $number): void
    {
    }
}
