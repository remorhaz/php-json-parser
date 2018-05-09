<?php

namespace Remorhaz\JSON\Parser\Example\Test;

use PHPUnit\Framework\TestCase;
use Remorhaz\JSON\Parser\Example\StringConcatenator;
use Remorhaz\JSON\Parser\Parser;

/**
 * @covers \Remorhaz\JSON\Parser\Example\StringConcatenator
 */
class StringConcatenatorTest extends TestCase
{

    /**
     * @throws \Remorhaz\JSON\Parser\Exception
     */
    public function testGetBuffer_ParsingDone_ReturnsAllStringValuesConcatenation(): void
    {
        $json = '[0, "a", {"b": "c", "d": {"e": true, "f": "g"}}, null]';
        $concatenator = new StringConcatenator;
        (new Parser($concatenator))->parse($json);
        $actualValue = $concatenator->getBuffer();
        self::assertSame("acg", $actualValue);
    }
}
