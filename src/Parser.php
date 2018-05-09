<?php

namespace Remorhaz\JSON\Parser;

use Remorhaz\JSON\Parser\Stream\StreamListenerInterface;
use Remorhaz\UniLex\Grammar\ContextFree;
use Remorhaz\UniLex\Lexer;
use Remorhaz\UniLex\Parser\LL1;
use Remorhaz\UniLex\Unicode;

final class Parser
{

    private $listener;

    private $grammar;

    private $tokenMatcher;

    private $tokenFactory;

    private $translator;

    public function __construct(StreamListenerInterface $listener)
    {
        $this->listener = $listener;
    }

    /**
     * @param string $json
     * @throws Exception
     */
    public function parse(string $json): void
    {
        $buffer = Unicode\CharBufferFactory::createFromString($json);
        $lexer = new Lexer\TokenReader($buffer, $this->getTokenMatcher(), $this->getTokenFactory());
        $parser = new LL1\Parser($this->getGrammar(), $lexer, $this->getTranslator());
        try {
            $parser->run();
        } catch (\Throwable $e) {
            throw new Exception("Failed to parse JSON", 0, $e);
        }
    }

    /**
     * @return ContextFree\Grammar
     * @throws Exception
     */
    private function getGrammar(): ContextFree\Grammar
    {
        if (!isset($this->grammar)) {
            try {
                $this->grammar = ContextFree\GrammarLoader::loadFile(__DIR__ . "/../spec/GrammarSpec.php");
            } catch (\Throwable $e) {
                throw new Exception("Failed to load JSON grammar specification", 0, $e);
            }
        }
        return $this->grammar;
    }

    private function getTokenMatcher(): Lexer\TokenMatcherInterface
    {
        if (!isset($this->tokenMatcher)) {
            $this->tokenMatcher = new TokenMatcher;
        }
        return $this->tokenMatcher;
    }

    /**
     * @return Lexer\TokenFactoryInterface
     * @throws Exception
     */
    private function getTokenFactory(): Lexer\TokenFactoryInterface
    {
        if (!isset($this->tokenFactory)) {
            $this->tokenFactory = new ContextFree\TokenFactory($this->getGrammar());
        }
        return $this->tokenFactory;
    }

    private function getTranslator(): LL1\ParserListenerInterface
    {
        if (!isset($this->translator)) {
            $scheme = new TranslationScheme($this->listener);
            $this->translator = new LL1\TranslationSchemeApplier($scheme);
        }
        return $this->translator;
    }
}
