<?php

namespace Remorhaz\JSON\Parser;

interface StreamListenerInterface
{

    public function onBeginDocument(): void;

    public function onEndDocument(): void;

    public function onBeginObject(OffsetInterface $offset): void;

    public function onEndObject(DocumentPartInterface $part): void;

    public function onBeginProperty(StringInterface $name, int $index): void;

    public function onEndProperty(StringInterface $name, int $index, DocumentPartInterface $part): void;

    public function onBeginArray(OffsetInterface $offset): void;

    public function onEndArray(DocumentPartInterface $part): void;

    public function onBeginElement(int $index): void;

    public function onEndElement(int $index, DocumentPartInterface $part): void;

    public function onNull(NullInterface $null): void;

    public function onBool(BoolInterface $bool): void;

    public function onString(StringInterface $string): void;

    public function onNumber(NumberInterface $number): void;
}
