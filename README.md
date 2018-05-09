# PHP JSON parser
[![License](https://poser.pugx.org/remorhaz/php-json-parser/license)](https://packagist.org/packages/remorhaz/php-json-parser)
[![Latest Stable Version](https://poser.pugx.org/remorhaz/php-json-parser/v/stable)](https://packagist.org/packages/remorhaz/php-json-parser)
[![Maintainability](https://api.codeclimate.com/v1/badges/aeb98ad24499cd187cd5/maintainability)](https://codeclimate.com/github/remorhaz/php-json-parser/maintainability)
[![Test Coverage](https://api.codeclimate.com/v1/badges/aeb98ad24499cd187cd5/test_coverage)](https://codeclimate.com/github/remorhaz/php-json-parser/test_coverage)

JSON (RFC 8259) streaming push-parser written in PHP.

## Requirements
* PHP 7.1

## License
This library is licensed under MIT license.

## Installation
Installation is as simple as any other [composer](https://getcomposer.org/) library's one:
```
composer require remorhaz/php-json-parser
```

## Usage
At first you need to implement `Remorhaz\JSON\Parser\Stream\EventListenerInterface` in your listener object. In most cases you can just extend `Remorhaz\JSON\Parser\Stream\AbstractEventListener` class and override any of its methods.

Then you pass your listener instance as an argument to `Remorhaz\JSON\Parser\Parser` class constructor. Now all you need is calling parser's `parse()` method with some valid JSON string, and it will start triggering listener's methods on corresponding events.

### Example
Let's create a simple listener that concatenates all string values from given JSON string.
```php
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
```
Now let's check how it works:
```php
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
```
