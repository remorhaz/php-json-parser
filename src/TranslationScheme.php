<?php

namespace Remorhaz\JSON\Parser;

use Remorhaz\UniLex\Grammar\SDD\TranslationSchemeInterface;
use Remorhaz\UniLex\Lexer\Token;
use Remorhaz\UniLex\Parser\Production;
use Remorhaz\UniLex\Parser\Symbol;
use Remorhaz\UniLex\Unicode\Grammar\TokenAttribute;
use Remorhaz\UniLex\Unicode\Utf8Encoder;

class TranslationScheme implements TranslationSchemeInterface
{

    private $listener;

    public function __construct(StreamListenerInterface $listener)
    {
        $this->listener = $listener;
    }

    /**
     * @param Production $production
     * @param int $symbolIndex
     * @throws \Remorhaz\UniLex\Exception
     */
    public function applySymbolActions(Production $production, int $symbolIndex): void
    {
        $hash = "{$production->getHeader()->getSymbolId()}.{$production->getIndex()}.{$symbolIndex}";
        switch ($hash) {
            case SymbolType::NT_JSON . ".0.0":
                var_dump("JSON begin");
                break;

            case SymbolType::NT_OBJECT . ".0.1":
                $offset = $production->getSymbol(0)->getAttribute('s.byte_offset_start');
                var_dump("Object begin [{$offset}]");
                break;

            case SymbolType::NT_ARRAY . ".0.1":
                $offset = $production->getSymbol(0)->getAttribute('s.byte_offset_start');
                var_dump("Array begin [{$offset}]");
                break;

            case SymbolType::NT_STRING . ".0.1":
                $offset = $production->getSymbol(0)->getAttribute('s.byte_offset_start');
                var_dump("String begin [{$offset}]");
                $production->getSymbol(1)->setAttribute('i.text_prefix', []);
                break;

            case SymbolType::NT_STRING_CONTENT . ".0.2":
                $textPrefix = $production->getHeader()->getAttribute('i.text_prefix');
                $text = $production->getSymbol(1)->getAttribute('s.text');
                $production->getSymbol(2)->setAttribute('i.text_prefix', array_merge($textPrefix, $text));
                break;

            case SymbolType::NT_STRING_CONTENT . ".1.1":
                $textPrefix = $production->getHeader()->getAttribute('i.text_prefix');
                $text = $production->getSymbol(0)->getAttribute('s.text');
                $production->getSymbol(1)->setAttribute('i.text_prefix', array_merge($textPrefix, $text));
                break;
        }
    }

    /**
     * @param Production $production
     * @throws \Remorhaz\UniLex\Exception
     */
    public function applyProductionActions(Production $production): void
    {
        $hash = "{$production->getHeader()->getSymbolId()}.{$production->getIndex()}";
        switch ($hash) {
            case SymbolType::NT_JSON . ".0":
                var_dump("JSON end");
                break;

            case SymbolType::NT_OBJECT . ".0":
                $offset = $production->getSymbol(3)->getAttribute('s.byte_offset_start');
                var_dump("Object end [{$offset}]");
                break;

            case SymbolType::NT_ARRAY . ".0":
                $offset = $production->getSymbol(3)->getAttribute('s.byte_offset_start');
                var_dump("Array end [{$offset}]");
                break;

            case SymbolType::NT_STRING . ".0":
                $offset = $production->getSymbol(2)->getAttribute('s.byte_offset_start');
                $symbolList = $production->getSymbol(1)->getAttribute('s.text');
                $text = (new Utf8Encoder)->encode(...$symbolList);
                var_dump("String end [{$offset}]: {$text}");
                break;

            case SymbolType::NT_STRING_CONTENT . ".0":
                $text = $production->getSymbol(2)->getAttribute('s.text');
                $production->getHeader()->setAttribute('s.text', $text);
                break;

            case SymbolType::NT_STRING_CONTENT . ".1":
                $text = $production->getSymbol(1)->getAttribute('s.text');
                $production->getHeader()->setAttribute('s.text', $text);
                break;

            case SymbolType::NT_STRING_CONTENT . ".2":
                $textPrefix = $production->getHeader()->getAttribute('i.text_prefix');
                $production->getHeader()->setAttribute('s.text', $textPrefix);
                break;

            case SymbolType::NT_ESCAPED . ".0":
            case SymbolType::NT_ESCAPED . ".1":
            case SymbolType::NT_ESCAPED . ".2":
            case SymbolType::NT_ESCAPED . ".3":
            case SymbolType::NT_ESCAPED . ".4":
            case SymbolType::NT_ESCAPED . ".5":
            case SymbolType::NT_ESCAPED . ".6":
            case SymbolType::NT_ESCAPED . ".7":
                $text = $production->getSymbol(0)->getAttribute('s.text');
                $production->getHeader()->setAttribute('s.text', $text);
                break;
        }
    }

    /**
     * @param Symbol $symbol
     * @param Token $token
     * @throws \Remorhaz\UniLex\Exception
     */
    public function applyTokenActions(Symbol $symbol, Token $token): void
    {
        $byteOffsetStart = $token->getAttribute(TokenAttribute::UNICODE_BYTE_OFFSET_START);
        $symbol->setAttribute('s.byte_offset_start', $byteOffsetStart);
        $byteOffsetFinish = $token->getAttribute(TokenAttribute::UNICODE_BYTE_OFFSET_FINISH);
        $symbol->setAttribute('s.byte_offset_finish', $byteOffsetFinish);
        switch ($token->getType()) {
            case TokenType::UNESCAPED:
            case TokenType::REVERSE_SOLIDUS:
            case TokenType::SOLIDUS:
            case TokenType::BACKSPACE:
            case TokenType::FORM_FEED:
            case TokenType::LINE_FEED:
            case TokenType::CARRIAGE_RETURN:
            case TokenType::TAB:
                $symbol->setAttribute('s.text', $token->getAttribute('json.text'));
                break;

            case TokenType::QUOTATION_MARK:
                if ('stringEsc' == $token->getAttribute('json.context')) {
                    $symbol->setAttribute('s.text', $token->getAttribute('json.text'));
                }
                break;
        }
    }
}
