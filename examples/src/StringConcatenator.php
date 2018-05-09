<?php

namespace Remorhaz\JSON\Parser\Example;

use Remorhaz\JSON\Parser\Stream\AbstractEventListener;
use Remorhaz\JSON\Parser\Stream\Event;

/**
 * Example JSON streaming parser event listener that concatenates all string values from input document.
 */
class StringConcatenator extends AbstractEventListener
{

    /**
     * Stores concatenated result.
     *
     * @var string
     */
    private $buffer = '';

    /**
     * Returns concatenated result.
     *
     * @return string
     */
    public function getBuffer(): string
    {
        return $this->buffer;
    }

    /**
     * Is called by parser for each string value in input stream.
     *
     * @param Event\StringInterface $string
     */
    public function onString(Event\StringInterface $string): void
    {
        $this->buffer .= $string->asString();
    }
}
