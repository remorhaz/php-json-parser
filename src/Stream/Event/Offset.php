<?php

namespace Remorhaz\JSON\Parser\Stream\Event;

use Remorhaz\JSON\Parser\Exception;

class Offset implements OffsetInterface
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

    /**
     * @return int
     * @throws Exception
     */
    public function inLines(): int
    {
        throw new Exception("Not implemented");
    }
}
