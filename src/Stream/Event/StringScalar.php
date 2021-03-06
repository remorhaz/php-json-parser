<?php

namespace Remorhaz\JSON\Parser\Stream\Event;

use Remorhaz\JSON\Parser\Stream\Event\DocumentPartInterface;
use Remorhaz\JSON\Parser\Stream\Event\Scalar;
use Remorhaz\JSON\Parser\Stream\Event\StringInterface;
use Remorhaz\JSON\Parser\Stream\Event\StringValueInterface;

class StringScalar extends Scalar implements StringInterface
{

    private $string;

    public function __construct(DocumentPartInterface $documentPart, StringValueInterface $string)
    {
        parent::__construct($documentPart);
        $this->string = $string;
    }

    public function asArray(): array
    {
        return $this->string->asArray();
    }

    public function asString(): string
    {
        return $this->string->asString();
    }
}
