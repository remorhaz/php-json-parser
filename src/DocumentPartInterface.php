<?php

namespace Remorhaz\JSON\Parser;

interface DocumentPartInterface
{

    public function getOffset(): OffsetInterface;

    public function getLength(): LengthInterface;
}
