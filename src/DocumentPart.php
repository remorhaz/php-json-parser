<?php

namespace Remorhaz\JSON\Parser;

class DocumentPart implements DocumentPartInterface
{

    private $length;

    private $offset;

    public function __construct(OffsetInterface $offset, LengthInterface $length)
    {
        $this->offset = $offset;
        $this->length = $length;
    }

    public function getOffset(): OffsetInterface
    {
        return $this->offset;
    }

    public function getLength(): LengthInterface
    {
        return $this->length;
    }
}
