<?php

namespace Remorhaz\JSON\Parser\Stream\Event;

class NumberScalar extends Scalar implements NumberInterface
{

    private $number;

    public function __construct(DocumentPartInterface $documentPart, NumberValueInterface $number)
    {
        parent::__construct($documentPart);
        $this->number = $number;
    }

    public function getInt(): StringValueInterface
    {
        return $this->number->getInt();
    }

    public function getFrac(): StringValueInterface
    {
        return $this->number->getFrac();
    }

    public function isNegative(): bool
    {
        return $this->number->isNegative();
    }

    public function getExp(): StringValueInterface
    {
        return $this->number->getExp();
    }

    public function isExpNegative(): bool
    {
        return $this->number->isExpNegative();
    }
}
