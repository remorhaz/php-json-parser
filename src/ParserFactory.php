<?php

namespace Remorhaz\JSON\Parser;

use Remorhaz\UniLex\Grammar\ContextFree\Grammar;
use Remorhaz\UniLex\Grammar\ContextFree\GrammarLoader;
use Remorhaz\UniLex\Grammar\ContextFree\TokenFactory;
use Remorhaz\UniLex\Lexer\TokenFactoryInterface;
use Remorhaz\UniLex\Lexer\TokenMatcherInterface;
use Remorhaz\UniLex\Lexer\TokenReader;
use Remorhaz\UniLex\Parser\LL1\Parser;
use Remorhaz\UniLex\Parser\LL1\TranslationSchemeApplier;
use Remorhaz\UniLex\Unicode\CharBufferFactory;

final class ParserFactory
{

    private $grammar;

    private $tokenMatcher;

    private $tokenFactory;

    /**
     * @param string $json
     * @param StreamListenerInterface $listener
     * @return Parser
     * @throws \Remorhaz\UniLex\Exception
     */
    public function createFromString(string $json, StreamListenerInterface $listener): Parser
    {
        $buffer = CharBufferFactory::createFromString($json);
        $lexer = new TokenReader($buffer, $this->getTokenMatcher(), $this->getTokenFactory());
        $scheme = new TranslationScheme($listener);
        $translator = new TranslationSchemeApplier($scheme);
        return new Parser($this->getGrammar(), $lexer, $translator);
    }

    /**
     * @return Grammar
     * @throws \Remorhaz\UniLex\Exception
     */
    private function getGrammar(): Grammar
    {
        if (!isset($this->grammar)) {
            $this->grammar = GrammarLoader::loadFile(__DIR__ . "/../spec/GrammarSpec.php");
        }
        return $this->grammar;
    }

    private function getTokenMatcher(): TokenMatcherInterface
    {
        if (!isset($this->tokenMatcher)) {
            $this->tokenMatcher = new TokenMatcher;
        }
        return $this->tokenMatcher;
    }

    /**
     * @return TokenFactoryInterface
     * @throws \Remorhaz\UniLex\Exception
     */
    private function getTokenFactory(): TokenFactoryInterface
    {
        if (!isset($this->tokenFactory)) {
            $this->tokenFactory = new TokenFactory($this->getGrammar());
        }
        return $this->tokenFactory;
    }
}
