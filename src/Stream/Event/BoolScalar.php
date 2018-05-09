<?php

namespace Remorhaz\JSON\Parser\Stream\Event;

class BoolScalar extends Scalar implements BoolInterface
{

    private $bool;

    public function __construct(DocumentPartInterface $documentPart, bool $bool)
    {
        parent::__construct($documentPart);
        $this->bool = $bool;
    }

    public function asBool(): bool
    {
        return $this->bool;
    }
}
