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
                var_dump("JSON open");
                break;

            case SymbolType::NT_OBJECT . ".0.1":
                $offset = $production->getSymbol(0)->getAttribute('s.byte_offset');
                var_dump("Object open [{$offset}]");
                break;

            case SymbolType::NT_ARRAY . ".0.1":
                $offset = $production->getSymbol(0)->getAttribute('s.byte_offset');
                var_dump("Array open [{$offset}]");
                break;

            case SymbolType::NT_STRING . ".0.1":
                $offset = $production->getSymbol(0)->getAttribute('s.byte_offset');
                var_dump("String open [{$offset}]");
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

            case SymbolType::NT_OBJECT . ".0.2":
                $production->getSymbol(2)->setAttribute('i.property_index', 0);
                break;

            case SymbolType::NT_OBJECT_MEMBERS . ".0.0":
                $propertyIndex = $production->getHeader()->getAttribute('i.property_index');
                $production->getSymbol(0)->setAttribute('i.property_index', $propertyIndex);
                break;

            case SymbolType::NT_OBJECT_MEMBERS . ".0.1":
                $propertyIndex = $production->getSymbol(0)->getAttribute('i.property_index');
                $production->getSymbol(1)->setAttribute('i.property_index', $propertyIndex + 1);
                break;

            case SymbolType::NT_OBJECT_MEMBER . ".0.1":
                $propertyIndex = $production->getHeader()->getAttribute('i.property_index');
                $offsetStart = $production->getSymbol(0)->getAttribute('s.byte_offset');
                $offsetFinish = $production->getSymbol(0)->getAttribute('s.byte_offset_finish');
                $text = $production->getSymbol(0)->getAttribute('s.text');
                var_dump("Property open [{$offsetStart}..{$offsetFinish}, {$propertyIndex}]: {$text}");
                break;

            case SymbolType::NT_OBJECT_MEMBER . ".0.4":
                $propertyName = $production->getSymbol(0)->getAttribute('s.text');
                var_dump("Property value open: {$propertyName}");
                break;

            case SymbolType::NT_NEXT_OBJECT_MEMBERS . ".0.2":
                $propertyIndex = $production->getHeader()->getAttribute('i.property_index');
                $production->getSymbol(2)->setAttribute('i.property_index', $propertyIndex);
                break;

            case SymbolType::NT_NEXT_OBJECT_MEMBERS . ".0.3":
                $propertyIndex = $production->getSymbol(2)->getAttribute('i.property_index');
                $production->getSymbol(3)->setAttribute('i.property_index', $propertyIndex + 1);
                break;

            case SymbolType::NT_ARRAY . ".0.2":
                $production->getSymbol(2)->setAttribute('i.element_index', 0);
                break;

            case SymbolType::NT_ARRAY_VALUES . ".0.0":
            case SymbolType::NT_NEXT_ARRAY_VALUES . ".0.2":
                $elementIndex = $production->getHeader()->getAttribute('i.element_index');
                var_dump("Element open: {$elementIndex}");
                break;

            case SymbolType::NT_ARRAY_VALUES . ".0.2":
                $elementIndex = $production->getHeader()->getAttribute('i.element_index');
                $production->getSymbol(2)->setAttribute('i.element_index', $elementIndex + 1);
                var_dump("Element close: {$elementIndex}");
                break;

            case SymbolType::NT_NEXT_ARRAY_VALUES . ".0.4":
                $elementIndex = $production->getHeader()->getAttribute('i.element_index');
                $production->getSymbol(4)->setAttribute('i.element_index', $elementIndex + 1);
                var_dump("Element close: {$elementIndex}");
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
                var_dump("JSON close");
                break;

            case SymbolType::NT_OBJECT . ".0":
                $offset = $production->getSymbol(0)->getAttribute('s.byte_offset');
                $closingBracket = $production->getSymbol(3);
                $finishOffset = $closingBracket->getAttribute('s.byte_offset');
                $length =
                    $closingBracket->getAttribute('s.byte_offset') +
                    $closingBracket->getAttribute('s.byte_length') - $offset;
                $production
                    ->getHeader()
                    ->setAttribute('s.byte_offset', $offset)
                    ->setAttribute('s.byte_offset_finish', $finishOffset)
                    ->setAttribute('s.byte_length', $length);
                var_dump("Object close [{$offset}->{$length}]");
                break;

            case SymbolType::NT_ARRAY . ".0":
                $startOffset = $production->getSymbol(0)->getAttribute('s.byte_offset');
                $closingBracket = $production->getSymbol(3);
                $finishOffset = $closingBracket->getAttribute('s.byte_offset');
                $length =
                    $closingBracket->getAttribute('s.byte_offset') +
                    $closingBracket->getAttribute('s.byte_length') - $startOffset;
                $production
                    ->getHeader()
                    ->setAttribute('s.byte_offset', $startOffset)
                    ->setAttribute('s.byte_offset_finish', $finishOffset)
                    ->setAttribute('s.byte_length', $length);
                var_dump("Array close [{$startOffset}->{$length}]");
                break;

            case SymbolType::NT_STRING . ".0":
                $offsetStart = $production->getSymbol(0)->getAttribute('s.byte_offset');
                $closingQuote = $production->getSymbol(2);
                $offsetFinish = $closingQuote->getAttribute('s.byte_offset');
                $length =
                    $closingQuote->getAttribute('s.byte_offset') +
                    $closingQuote->getAttribute('s.byte_length') - $offsetStart;
                $symbolList = $production->getSymbol(1)->getAttribute('s.text');
                $text = (new Utf8Encoder)->encode(...$symbolList);
                $production
                    ->getHeader()
                    ->setAttribute('s.text', $text)
                    ->setAttribute('s.byte_offset', $offsetStart)
                    ->setAttribute('s.byte_offset_finish', $offsetFinish)
                    ->setAttribute('s.byte_length', $length);
                var_dump("String close [{$offsetStart}..{$offsetFinish}]: {$text}");
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

            case SymbolType::NT_OBJECT_MEMBER . ".0":
                $offsetStart = $production->getSymbol(4)->getAttribute('s.byte_offset');
                $offsetFinish = $production->getSymbol(4)->getAttribute('s.byte_offset_finish');
                $propertyName = $production->getSymbol(0)->getAttribute('s.text');
                var_dump("Property value close [{$offsetStart}..{$offsetFinish}]: {$propertyName}");
                break;

            case SymbolType::NT_NUMBER . ".0":
                $int = $production->getSymbol(1)->getAttribute('s.number_int');
                $production
                    ->getHeader()
                    ->setAttribute('s.number_negative', true)
                    ->setAttribute('s.number_int', $int);
                break;

            case SymbolType::NT_NUMBER . ".1":
                $int = $production->getSymbol(0)->getAttribute('s.number_int');
                $production
                    ->getHeader()
                    ->setAttribute('s.number_negative', false)
                    ->setAttribute('s.number_int', $int);
                break;

            case SymbolType::NT_INT . ".0":
                $production
                    ->getHeader()
                    ->setAttribute('s.number_int', [0x30]);
                break;

            case SymbolType::NT_INT . ".1":
                $int = $production->getSymbol(0)->getAttribute('s.text');
                $production
                    ->getHeader()
                    ->setAttribute('s.number_int', $int);
                break;

            case SymbolType::NT_UNSIGNED_NUMBER . ".0":
                $int = $production->getSymbol(0)->getAttribute('s.number_int');
                $production
                    ->getHeader()
                    ->setAttribute('s.number_int', $int);
                break;

            case SymbolType::NT_VALUE . ".0":
                $scalar = $production->getSymbol(0);
                $offsetStart = $scalar->getAttribute('s.byte_offset');
                $offsetFinish = $scalar->getAttribute('s.byte_offset_finish');
                $length = $scalar->getAttribute('s.byte_length');
                $production
                    ->getHeader()
                    ->setAttribute('s.byte_offset', $offsetStart)
                    ->setAttribute('s.byte_offset_finish', $offsetFinish)
                    ->setAttribute('s.byte_length', $length);
                var_dump("Set scalar bool [{$offsetStart}..{$offsetFinish}->{$length}]: false");
                break;

            case SymbolType::NT_VALUE . ".1":
                $scalar = $production->getSymbol(0);
                $offsetStart = $scalar->getAttribute('s.byte_offset');
                $offsetFinish = $scalar->getAttribute('s.byte_offset_finish');
                $length = $scalar->getAttribute('s.byte_length');
                $production
                    ->getHeader()
                    ->setAttribute('s.byte_offset', $offsetStart)
                    ->setAttribute('s.byte_offset_finish', $offsetFinish)
                    ->setAttribute('s.byte_length', $length);
                var_dump("Set scalar null [{$offsetStart}..{$offsetFinish}->{$length}]: null");
                break;

            case SymbolType::NT_VALUE . ".2":
                $scalar = $production->getSymbol(0);
                $offsetStart = $scalar->getAttribute('s.byte_offset');
                $offsetFinish = $scalar->getAttribute('s.byte_offset_finish');
                $length = $scalar->getAttribute('s.byte_length');
                $production
                    ->getHeader()
                    ->setAttribute('s.byte_offset', $offsetStart)
                    ->setAttribute('s.byte_offset_finish', $offsetFinish)
                    ->setAttribute('s.byte_length', $length);
                var_dump("Set scalar bool [{$offsetStart}..{$offsetFinish}->{$length}]: true");
                break;

            case SymbolType::NT_VALUE . ".3":
                $struct = $production->getSymbol(0);
                $offsetStart = $struct->getAttribute('s.byte_offset');
                $offsetFinish = $struct->getAttribute('s.byte_offset_finish');
                $length = $struct->getAttribute('s.byte_length');
                $production
                    ->getHeader()
                    ->setAttribute('s.byte_offset', $offsetStart)
                    ->setAttribute('s.byte_length', $length)
                    ->setAttribute('s.byte_offset_finish', $offsetFinish);
                var_dump("Set struct object [{$offsetStart}..{$offsetFinish}->{$length}]: true");
                break;


            case SymbolType::NT_VALUE . ".4":
                $struct = $production->getSymbol(0);
                $offsetStart = $struct->getAttribute('s.byte_offset');
                $offsetFinish = $struct->getAttribute('s.byte_offset_finish');
                $length = $struct->getAttribute('s.byte_length');
                $production
                    ->getHeader()
                    ->setAttribute('s.byte_offset', $offsetStart)
                    ->setAttribute('s.byte_length', $length)
                    ->setAttribute('s.byte_offset_finish', $offsetFinish);
                var_dump("Set struct array [{$offsetStart}..{$offsetFinish}->{$length}]: true");
                break;

            case SymbolType::NT_VALUE . ".5":
                $scalar = $production->getSymbol(0);
                $isNegative = $scalar->getAttribute('s.number_negative');
                $int = $scalar->getAttribute('s.number_int');
                $isNegativeText = $isNegative ? '-' : '+';
                $intText = (new Utf8Encoder)->encode(...$int);
                var_dump("Set scalar number: {$isNegativeText}{$intText}");
                break;

            case SymbolType::NT_VALUE . ".6":
                $scalar = $production->getSymbol(0);
                $offsetStart = $scalar->getAttribute('s.byte_offset');
                $offsetFinish = $scalar->getAttribute('s.byte_offset_finish');
                $length = $scalar->getAttribute('s.byte_length');
                $text = $scalar->getAttribute('s.text');
                $production
                    ->getHeader()
                    ->setAttribute('s.byte_offset', $offsetStart)
                    ->setAttribute('s.byte_offset_finish', $offsetFinish)
                    ->setAttribute('s.byte_length', $length);
                var_dump("Set scalar string [{$offsetStart}..{$offsetFinish}->{$length}]: {$text}");
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
        $byteOffsetStart = $token->getAttribute(TokenAttribute::UNICODE_BYTE_OFFSET);
        $symbol->setAttribute('s.byte_offset', $byteOffsetStart);
        $byteLength = $token->getAttribute(TokenAttribute::UNICODE_BYTE_LENGTH);
        $symbol->setAttribute('s.byte_length', $byteLength);
        $symbol->setAttribute('s.byte_offset_finish', $byteOffsetStart + $byteLength - 1);
        switch ($token->getType()) {
            case TokenType::UNESCAPED:
            case TokenType::REVERSE_SOLIDUS:
            case TokenType::SOLIDUS:
            case TokenType::BACKSPACE:
            case TokenType::FORM_FEED:
            case TokenType::LINE_FEED:
            case TokenType::CARRIAGE_RETURN:
            case TokenType::TAB:
            case TokenType::DIGIT_1_9:
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
