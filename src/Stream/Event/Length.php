<?php

namespace Remorhaz\JSON\Parser\Stream\Event;

use Remorhaz\JSON\Parser\Exception;

class Length implements LengthInterface
{

    private $inBytes;

    public function __construct(int $inBytes)
    {
        $this->inBytes = $inBytes;
    }

    public function inBytes(): int
    {
        return $this->inBytes;
    }

    /**
     * @return int
     * @throws Exception
     */
    public function inSymbols(): int
    {
        throw new Exception("Not implemented");
    }
}
