<?php

namespace Remorhaz\JSON\Parser;

use Remorhaz\UniLex\Unicode\Utf8Encoder;

class StringValue implements StringValueInterface
{

    private $symbolList;

    private $string;

    public function __construct(int ...$symbolList)
    {
        $this->symbolList = $symbolList;
    }

    public function asArray(): array
    {
        return $this->symbolList;
    }

    /**
     * @return StringScalar
     */
    public function asString(): string
    {
        if (!isset($this->string)) {
            $this->string = (new Utf8Encoder)->encode(...$this->symbolList);
        }
        return $this->string;
    }
}
