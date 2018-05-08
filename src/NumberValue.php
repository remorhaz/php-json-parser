<?php

namespace Remorhaz\JSON\Parser;

class NumberValue implements NumberValueInterface
{

    private $int;

    private $frac;

    private $isNegative;

    private $exp;

    private $isExpNegative;

    public function __construct(
        bool $isNegative,
        StringValueInterface $int,
        StringValueInterface $frac,
        bool $isExpNegative,
        StringValueInterface $exp
    ) {
        $this->isNegative = $isNegative;
        $this->int = $int;
        $this->frac = $frac;
        $this->isExpNegative = $isExpNegative;
        $this->exp = $exp;
    }

    public function getInt(): StringValueInterface
    {
        return $this->int;
    }

    public function getFrac(): StringValueInterface
    {
        return $this->frac;
    }

    public function isNegative(): bool
    {
        return $this->isNegative;
    }

    public function getExp(): StringValueInterface
    {
        return $this->exp;
    }

    public function isExpNegative(): bool
    {
        return $this->isExpNegative;
    }
}
