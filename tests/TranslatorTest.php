<?php

namespace Remorhaz\JSON\Parser\Test;

use PHPUnit\Framework\TestCase;
use Remorhaz\JSON\Parser\AbstractStreamListener;
use Remorhaz\JSON\Parser\DocumentPartInterface;
use Remorhaz\JSON\Parser\OffsetInterface;
use Remorhaz\JSON\Parser\StringInterface;
use Remorhaz\JSON\Parser\TokenMatcher;
use Remorhaz\JSON\Parser\TranslationScheme;
use Remorhaz\UniLex\Grammar\ContextFree\GrammarLoader;
use Remorhaz\UniLex\Grammar\ContextFree\TokenFactory;
use Remorhaz\UniLex\Lexer\TokenReader;
use Remorhaz\UniLex\Parser\LL1\Parser;
use Remorhaz\UniLex\Parser\LL1\TranslationSchemeApplier;
use Remorhaz\UniLex\Unicode\CharBufferFactory;

class TranslatorTest extends TestCase
{

    /**
     * @throws \Remorhaz\UniLex\Exception
     */
    public function testTranslator(): void
    {
        //       0    5    10   15   20   25   30   35   40   45   50   55   60  * 65   70   75   80   85
        $json = '{"a":true, "b": [0, -1.2e+10, false, {"c": null, "d" : "One two \\n three"} ], "e":""}';
        $buffer = CharBufferFactory::createFromString($json);
        $grammar = GrammarLoader::loadFile(__DIR__ . "/../spec/GrammarSpec.php");
        $lexer = new TokenReader($buffer, new TokenMatcher, new TokenFactory($grammar));
        $listener = $this->createStreamListener();
        $scheme = new TranslationScheme($listener);
        $translator = new TranslationSchemeApplier($scheme);
        $parser = new Parser($grammar, $lexer, $translator);
        $parser->run();
        $actualLog = $listener->getLog();
        $expectedLog = [
            "BEGIN DOCUMENT",
            "BEGIN OBJECT [0]",
            "BEGIN PROPERTY [1(3)]#0: 'a'",
            "END PROPERTY [5(4)]#0: 'a'",
            "BEGIN PROPERTY [11(3)]#1: 'b'",
            "BEGIN ARRAY [16]",
            "BEGIN ELEMENT #0",
            "END ELEMENT [17(1)]#0",
            "BEGIN ELEMENT #1",
            "END ELEMENT [20(8)]#1",
            "BEGIN ELEMENT #2",
            "END ELEMENT [30(5)]#2",
            "BEGIN ELEMENT #3",
            "BEGIN OBJECT [37]",
            "BEGIN PROPERTY [38(3)]#0: 'c'",
            "END PROPERTY [43(4)]#0: 'c'",
            "BEGIN PROPERTY [49(3)]#1: 'd'",
            "END PROPERTY [55(18)]#1: 'd'",
            "END OBJECT [37(37)]",
            "END ELEMENT [37(37)]#3",
            "END ARRAY [16(60)]",
            "END PROPERTY [16(60)]#1: 'b'",
            "BEGIN PROPERTY [78(3)]#2: 'e'",
            "END PROPERTY [82(2)]#2: 'e'",
            "END OBJECT [0(85)]",
            "END DOCUMENT",
        ];
        self::assertSame($expectedLog, $actualLog);
    }

    private function createStreamListener()
    {
        return new class extends AbstractStreamListener
        {
            private $log = [];

            public function getLog(): array
            {
                return $this->log;
            }

            public function onBeginDocument(): void
            {
                $this->log[] = "BEGIN DOCUMENT";
            }

            public function onEndDocument(): void
            {
                $this->log[] = "END DOCUMENT";
            }

            public function onBeginObject(OffsetInterface $offset): void
            {
                $this->log[] = "BEGIN OBJECT [{$offset->inBytes()}]";
            }

            public function onEndObject(DocumentPartInterface $part): void
            {
                $this->log[] = "END OBJECT [{$part->getOffset()->inBytes()}({$part->getLength()->inBytes()})]";
            }

            public function onBeginProperty(StringInterface $name, int $index): void
            {
                $this->log[] =
                    "BEGIN PROPERTY [{$name->getOffset()->inBytes()}({$name->getLength()->inBytes()})]#{$index}: " .
                    "'{$name->asString()}'";
            }

            public function onEndProperty(StringInterface $name, int $index, DocumentPartInterface $part): void
            {
                $this->log[] =
                    "END PROPERTY [{$part->getOffset()->inBytes()}({$part->getLength()->inBytes()})]#{$index}: " .
                    "'{$name->asString()}'";
            }

            public function onBeginArray(OffsetInterface $offset): void
            {
                $this->log[] = "BEGIN ARRAY [{$offset->inBytes()}]";
            }

            public function onEndArray(DocumentPartInterface $part): void
            {
                $this->log[] = "END ARRAY [{$part->getOffset()->inBytes()}({$part->getLength()->inBytes()})]";
            }

            public function onBeginElement(int $index): void
            {
                $this->log[] = "BEGIN ELEMENT #{$index}";
            }

            public function onEndElement(int $index, DocumentPartInterface $part): void
            {
                $this->log[] =
                    "END ELEMENT [{$part->getOffset()->inBytes()}({$part->getLength()->inBytes()})]#{$index}";
            }
        };
    }
}
