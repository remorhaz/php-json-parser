<?php

namespace Remorhaz\JSON\Parser\Test;

use PHPUnit\Framework\TestCase;
use Remorhaz\JSON\Parser\StreamListenerInterface;
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
        //       0    5    10   15   20   25   30   35   40   45   50   55   60   65
        $json = '{"a":true, "b": [0, 1, false, {"c": null, "d" : "One two \n three"} ]}';
        $buffer = CharBufferFactory::createFromString($json);
        $grammar = GrammarLoader::loadFile(__DIR__ . "/../spec/GrammarSpec.php");
        $lexer = new TokenReader($buffer, new TokenMatcher, new TokenFactory($grammar));
        $scheme = new TranslationScheme($this->createStreamListener());
        $translator = new TranslationSchemeApplier($scheme);
        $parser = new Parser($grammar, $lexer, $translator);
        $parser->run();
    }

    private function createStreamListener(): StreamListenerInterface
    {
        return new class implements StreamListenerInterface
        {
        };
    }
}
