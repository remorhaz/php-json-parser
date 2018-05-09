<?php

namespace Remorhaz\JSON\Parser;

use Remorhaz\JSON\Parser\Stream\Event;
use Remorhaz\JSON\Parser\Stream\EventListenerInterface;
use Remorhaz\UniLex\Grammar\SDD\TranslationSchemeInterface;
use Remorhaz\UniLex\Lexer\Token;
use Remorhaz\UniLex\Parser\Production;
use Remorhaz\UniLex\Parser\Symbol;
use Remorhaz\UniLex\Unicode\Grammar\TokenAttribute;

class TranslationScheme implements TranslationSchemeInterface
{

    private const REPLACEMENT_CHARACTER = 0xFFFD;

    private $listener;

    public function __construct(EventListenerInterface $listener)
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
                $this->listener->onBeginDocument();
                break;

            case SymbolType::NT_OBJECT . ".0.1":
                $openingBracket = $production->getSymbol(0);
                $offset = $this->createOffset($openingBracket);
                $this->listener->onBeginObject($offset);
                break;

            case SymbolType::NT_ARRAY . ".0.1":
                $openingBracket = $production->getSymbol(0);
                $offset = $this->createOffset($openingBracket);
                $this->listener->onBeginArray($offset);
                break;

            case SymbolType::NT_STRING . ".0.1":
                $production
                    ->getSymbol(1)
                    ->setAttribute('i.text_prefix', [])
                    ->setAttribute('i.text_surrogate', 0)
                    ->setAttribute('i.text_is_hi_surrogate', false);
                break;

            case SymbolType::NT_STRING_CONTENT . ".0.1":
                $header = $production->getHeader();
                $production
                    ->getSymbol(1)
                    ->setAttribute('i.text_surrogate', $header->getAttribute('i.text_surrogate'))
                    ->setAttribute('i.text_is_hi_surrogate', $header->getAttribute('i.text_is_hi_surrogate'));
                break;

            case SymbolType::NT_STRING_CONTENT . ".0.2":
                $textPrefix = $production->getHeader()->getAttribute('i.text_prefix');
                $escaped = $production->getSymbol(1);
                $text = $escaped->getAttribute('s.text');
                $production
                    ->getSymbol(2)
                    ->setAttribute('i.text_prefix', array_merge($textPrefix, $text))
                    ->setAttribute('i.text_surrogate', $escaped->getAttribute('s.text_surrogate'))
                    ->setAttribute('i.text_is_hi_surrogate', $escaped->getAttribute('s.text_is_hi_surrogate'));
                break;

            case SymbolType::NT_STRING_CONTENT . ".1.1":
                $header = $production->getHeader();
                $textPrefix = $header->getAttribute('i.text_prefix');
                $surrogate = $header->getAttribute('i.text_is_hi_surrogate') ? [0xFFFD] : [];
                $text = $production->getSymbol(0)->getAttribute('s.text');
                $production
                    ->getSymbol(1)
                    ->setAttribute('i.text_prefix', array_merge($textPrefix, $surrogate, $text))
                    ->setAttribute('i.text_surrogate', 0)
                    ->setAttribute('i.text_is_hi_surrogate', false);
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

            case SymbolType::NT_OBJECT_MEMBER . ".0.4":
                $propertyIndex = $production->getHeader()->getAttribute('i.property_index');
                $property = $production->getSymbol(0);
                $propertyName = $this->createString($property);
                $this->listener->onBeginProperty($propertyName, $propertyIndex);
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
                $this->listener->onBeginElement($elementIndex);
                break;

            case SymbolType::NT_ARRAY_VALUES . ".0.2":
                $elementIndex = $production->getHeader()->getAttribute('i.element_index');
                $production->getSymbol(2)->setAttribute('i.element_index', $elementIndex + 1);
                $value = $production->getSymbol(0);
                $documentPart = $this->createDocumentPart($value);
                $this->listener->onEndElement($elementIndex, $documentPart);
                break;

            case SymbolType::NT_NEXT_ARRAY_VALUES . ".0.4":
                $elementIndex = $production->getHeader()->getAttribute('i.element_index');
                $production->getSymbol(4)->setAttribute('i.element_index', $elementIndex + 1);
                $value = $production->getSymbol(2);
                $documentPart = $this->createDocumentPart($value);
                $this->listener->onEndElement($elementIndex, $documentPart);
                break;

            case SymbolType::NT_FRAC . ".0.1":
                $production->getSymbol(1)->setAttribute('i.number_int', []);
                break;

            case SymbolType::NT_DIGIT . ".0.1":
                $textPrefix = $production->getHeader()->getAttribute('i.number_int');
                $text = [0x30];
                $production->getSymbol(1)->setAttribute('i.number_int', array_merge($textPrefix, $text));
                break;

            case SymbolType::NT_OPT_DIGIT . ".0.0":
                $textPrefix = $production->getHeader()->getAttribute('i.number_int');
                $production->getSymbol(0)->setAttribute('i.number_int', $textPrefix);
                break;

            case SymbolType::NT_OPT_EXP . ".0.2":
                $production->getSymbol(2)->setAttribute('i.number_int', []);
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
                $this->listener->onEndDocument();
                break;

            case SymbolType::NT_OBJECT . ".0":
                $openingBracket = $production->getSymbol(0);
                $closingBracket = $production->getSymbol(3);
                $documentPart = $this->createDocumentPartBetween($openingBracket, $closingBracket);
                $this->setHeaderDocumentPart($production, $documentPart);
                $this->listener->onEndObject($documentPart);
                break;

            case SymbolType::NT_ARRAY . ".0":
                $openingBracket = $production->getSymbol(0);
                $closingBracket = $production->getSymbol(3);
                $documentPart = $this->createDocumentPartBetween($openingBracket, $closingBracket);
                $this->setHeaderDocumentPart($production, $documentPart);
                $this->listener->onEndArray($documentPart);
                break;

            case SymbolType::NT_STRING . ".0":
                $openingQuote = $production->getSymbol(0);
                $closingQuote = $production->getSymbol(2);
                $documentPart = $this->createDocumentPartBetween($openingQuote, $closingQuote);
                $this->setHeaderDocumentPart($production, $documentPart);
                $symbolList = $production->getSymbol(1)->getAttribute('s.text');
                $production
                    ->getHeader()
                    ->setAttribute('s.text', $symbolList);
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
                $header = $production->getHeader();
                $textPrefix = $header->getAttribute('i.text_prefix');
                $surrogate = $header->getAttribute('i.text_is_hi_surrogate') ? [self::REPLACEMENT_CHARACTER] : [];
                $header->setAttribute('s.text', array_merge($textPrefix, $surrogate));
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
                $production
                    ->getHeader()
                    ->setAttribute('s.text', $text)
                    ->setAttribute('s.text_is_hi_surrogate', false)
                    ->setAttribute('s.text_is_lo_surrogate', false)
                    ->setAttribute('s.text_surrogate', 0);
                break;

            case SymbolType::NT_ESCAPED . ".8":
                $hex = $production->getSymbol(0);
                $char = $hex->getAttribute('s.text_utf16');
                $isHiSurrogate = $hex->getAttribute('s.text_is_hi_surrogate');
                $isLoSurrogate = $hex->getAttribute('s.text_is_lo_surrogate');
                $header = $production->getHeader();
                $isAfterHiSurrogate = $header->getAttribute('i.text_is_hi_surrogate');
                if ($isAfterHiSurrogate) {
                    if ($isLoSurrogate) {
                        $surrogate = $header->getAttribute('i.text_surrogate');
                        $symbol = 0x10000 + (($surrogate & 0x03FF) << 0x0A | $char & 0x03FF);
                        $textPrefix = [$symbol];
                    } else {
                        $textPrefix = [self::REPLACEMENT_CHARACTER];
                    }
                } else {
                    $textPrefix = [];
                }
                $text = $isHiSurrogate || $isAfterHiSurrogate
                    ? []
                    : [$isLoSurrogate ? self::REPLACEMENT_CHARACTER : $char];
                $header
                    ->setAttribute('s.text', array_merge($textPrefix, $text))
                    ->setAttribute('s.text_is_hi_surrogate', $isHiSurrogate)
                    ->setAttribute('s.text_is_lo_surrogate', $isLoSurrogate)
                    ->setAttribute('s.text_surrogate', $char);
                break;

            case SymbolType::NT_OBJECT_MEMBER . ".0":
                $property = $production->getSymbol(0);
                $value = $production->getSymbol(4);
                $propertyIndex = $production->getHeader()->getAttribute('i.property_index');
                $documentPart = $this->createDocumentPart($value);
                $propertyName = $this->createString($property);
                $this->listener->onEndProperty($propertyName, $propertyIndex, $documentPart);
                break;

            case SymbolType::NT_NUMBER . ".0":
                $minus = $production->getSymbol(0);
                $offset = $minus->getAttribute('s.byte_offset');
                $unsigned = $production->getSymbol(1);
                $length = $this->createLengthSum($minus, $unsigned);
                $intText = $unsigned->getAttribute('s.number_int');
                $fracText = $unsigned->getAttribute('s.number_frac');
                $expText = $unsigned->getAttribute('s.number_exp');
                $isExpNegative = $unsigned->getAttribute('s.number_exp_negative');
                $production
                    ->getHeader()
                    ->setAttribute('s.byte_offset', $offset)
                    ->setAttribute('s.byte_length', $length->inBytes())
                    ->setAttribute('s.number_negative', true)
                    ->setAttribute('s.number_int', $intText)
                    ->setAttribute('s.number_frac', $fracText)
                    ->setAttribute('s.number_exp', $expText)
                    ->setAttribute('s.number_exp_negative', $isExpNegative);
                break;

            case SymbolType::NT_NUMBER . ".1":
                $unsigned = $production->getSymbol(0);
                $this->copyDocumentPartToHeader($production, $unsigned);
                $intText = $unsigned->getAttribute('s.number_int');
                $fracText = $unsigned->getAttribute('s.number_frac');
                $expText = $unsigned->getAttribute('s.number_exp');
                $isExpNegative = $unsigned->getAttribute('s.number_exp_negative');
                $production
                    ->getHeader()
                    ->setAttribute('s.number_negative', false)
                    ->setAttribute('s.number_int', $intText)
                    ->setAttribute('s.number_frac', $fracText)
                    ->setAttribute('s.number_exp', $expText)
                    ->setAttribute('s.number_exp_negative', $isExpNegative);
                break;

            case SymbolType::NT_INT . ".0":
                $int = $production->getSymbol(0);
                $this->copyDocumentPartToHeader($production, $int);
                $production
                    ->getHeader()
                    ->setAttribute('s.number_int', [0x30]);
                break;

            case SymbolType::NT_INT . ".1":
                $int = $production->getSymbol(0);
                $this->copyDocumentPartToHeader($production, $int);
                $intText = $int->getAttribute('s.text');
                $production
                    ->getHeader()
                    ->setAttribute('s.number_int', $intText);
                break;

            case SymbolType::NT_UNSIGNED_NUMBER . ".0":
                $int = $production->getSymbol(0);
                $offset = $int->getAttribute('s.byte_offset');
                $intText = $int->getAttribute('s.number_int');
                $tail = $production->getSymbol(1);
                $length = $this->createLengthSum($int, $tail);
                $fracText = $tail->getAttribute('s.number_frac');
                $expText = $tail->getAttribute('s.number_exp');
                $isExpNegative = $tail->getAttribute('s.number_exp_negative');
                $production
                    ->getHeader()
                    ->setAttribute('s.byte_offset', $offset)
                    ->setAttribute('s.byte_length', $length->inBytes())
                    ->setAttribute('s.number_int', $intText)
                    ->setAttribute('s.number_frac', $fracText)
                    ->setAttribute('s.number_exp', $expText)
                    ->setAttribute('s.number_exp_negative', $isExpNegative);
                break;

            case SymbolType::NT_DIGIT . ".0":
                $zero = $production->getSymbol(0);
                $digit = $production->getSymbol(1);
                $text = $digit->getAttribute('s.number_int');
                $length = $this->createLengthSum($zero, $digit);
                $production
                    ->getHeader()
                    ->setAttribute('s.byte_length', $length->inBytes())
                    ->setAttribute('s.number_int', $text);
                break;

            case SymbolType::NT_DIGIT . ".1":
                $textPrefix = $production->getHeader()->getAttribute('i.number_int');
                $digit = $production->getSymbol(0);
                $text = $digit->getAttribute('s.text');
                $length = $digit->getAttribute('s.byte_length');
                $production
                    ->getHeader()
                    ->setAttribute('s.byte_length', $length)
                    ->setAttribute('s.number_int', array_merge($textPrefix, $text));
                break;

            case SymbolType::NT_OPT_DIGIT . ".0":
                $digit = $production->getSymbol(0);
                $text = $digit->getAttribute('s.number_int');
                $length = $digit->getAttribute('s.byte_length');
                $production
                    ->getHeader()
                    ->setAttribute('s.byte_length', $length)
                    ->setAttribute('s.number_int', $text);
                break;

            case SymbolType::NT_OPT_DIGIT . ".1":
                $textPrefix = $production->getHeader()->getAttribute('i.number_int');
                $production
                    ->getHeader()
                    ->setAttribute('s.byte_length', 0)
                    ->setAttribute('s.number_int', $textPrefix);
                break;

            case SymbolType::NT_FRAC . ".0":
                $dot = $production->getSymbol(0);
                $digit = $production->getSymbol(1);
                $text = $digit->getAttribute('s.number_int');
                $length = $this->createLengthSum($dot, $digit);
                $production
                    ->getHeader()
                    ->setAttribute('s.byte_length', $length->inBytes())
                    ->setAttribute('s.number_int', $text);
                break;

            case SymbolType::NT_OPT_EXP . ".0":
                $e = $production->getSymbol(0);
                $sign = $production->getSymbol(1);
                $digit = $production->getSymbol(2);
                $text = $digit->getAttribute('s.number_int');
                $isNegative = $sign->getAttribute('s.number_negative');
                $length = $this->createLengthSum($e, $sign, $digit);
                $production
                    ->getHeader()
                    ->setAttribute('s.byte_length', $length->inBytes())
                    ->setAttribute('s.number_int', $text)
                    ->setAttribute('s.number_negative', $isNegative);
                break;

            case SymbolType::NT_OPT_EXP . ".1":
                $production
                    ->getHeader()
                    ->setAttribute('s.byte_length', 0)
                    ->setAttribute('s.number_int', [])
                    ->setAttribute('s.number_negative', false);
                break;

            case SymbolType::NT_OPT_SIGN . ".0":
                $length = $production->getSymbol(0)->getAttribute('s.byte_length');
                $production
                    ->getHeader()
                    ->setAttribute('s.byte_length', $length)
                    ->setAttribute('s.number_negative', true);
                break;

            case SymbolType::NT_OPT_SIGN . ".1":
                $length = $production->getSymbol(0)->getAttribute('s.byte_length');
                $production
                    ->getHeader()
                    ->setAttribute('s.byte_length', $length)
                    ->setAttribute('s.number_negative', false);
                break;

            case SymbolType::NT_OPT_SIGN . ".2":
                $production
                    ->getHeader()
                    ->setAttribute('s.byte_length', 0)
                    ->setAttribute('s.number_negative', false);
                break;

            case SymbolType::NT_INT_TAIL . ".0":
                $frac = $production->getSymbol(0);
                $fracText = $frac->getAttribute('s.number_int');
                $exp = $production->getSymbol(1);
                $length = $this->createLengthSum($frac, $exp);
                $expText = $exp->getAttribute('s.number_int');
                $isExpNegative = $exp->getAttribute('s.number_negative');
                $production
                    ->getHeader()
                    ->setAttribute('s.byte_length', $length->inBytes())
                    ->setAttribute('s.number_frac', $fracText)
                    ->setAttribute('s.number_exp', $expText)
                    ->setAttribute('s.number_exp_negative', $isExpNegative);
                break;

            case SymbolType::NT_INT_TAIL . ".1":
                $exp = $production->getSymbol(0);
                $length = $this->createLength($exp);
                $expText = $exp->getAttribute('s.number_int');
                $isExpNegative = $exp->getAttribute('s.number_negative');
                $production
                    ->getHeader()
                    ->setAttribute('s.byte_length', $length->inBytes())
                    ->setAttribute('s.number_frac', [])
                    ->setAttribute('s.number_exp', $expText)
                    ->setAttribute('s.number_exp_negative', $isExpNegative);
                break;

            case SymbolType::NT_VALUE . ".0":
                $scalar = $production->getSymbol(0);
                $this->copyDocumentPartToHeader($production, $scalar);
                $documentPart = $this->createDocumentPart($scalar);
                $bool = new Event\BoolScalar($documentPart, false);
                $this->listener->onBool($bool);
                break;

            case SymbolType::NT_VALUE . ".1":
                $scalar = $production->getSymbol(0);
                $this->copyDocumentPartToHeader($production, $scalar);
                $documentPart = $this->createDocumentPart($scalar);
                $null = new Event\NullScalar($documentPart);
                $this->listener->onNull($null);
                break;

            case SymbolType::NT_VALUE . ".2":
                $scalar = $production->getSymbol(0);
                $this->copyDocumentPartToHeader($production, $scalar);
                $documentPart = $this->createDocumentPart($scalar);
                $bool = new Event\BoolScalar($documentPart, true);
                $this->listener->onBool($bool);
                break;

            case SymbolType::NT_VALUE . ".3":
                $struct = $production->getSymbol(0);
                $this->copyDocumentPartToHeader($production, $struct);
                break;


            case SymbolType::NT_VALUE . ".4":
                $struct = $production->getSymbol(0);
                $this->copyDocumentPartToHeader($production, $struct);
                break;

            case SymbolType::NT_VALUE . ".5":
                $scalar = $production->getSymbol(0);
                $this->copyDocumentPartToHeader($production, $scalar);
                $isNegative = $scalar->getAttribute('s.number_negative');
                $int = $this->createStringValue($scalar, 's.number_int');
                $frac = $this->createStringValue($scalar, 's.number_frac');
                $isExpNegative = $scalar->getAttribute('s.number_exp_negative');
                $exp = $this->createStringValue($scalar, 's.number_exp');
                $numberValue = new Event\NumberValue($isNegative, $int, $frac, $isExpNegative, $exp);
                $documentPart = $this->createDocumentPart($scalar);
                $number = new Event\NumberScalar($documentPart, $numberValue);
                $this->listener->onNumber($number);
                break;

            case SymbolType::NT_VALUE . ".6":
                $scalar = $production->getSymbol(0);
                $this->copyDocumentPartToHeader($production, $scalar);
                $string = $this->createString($scalar);
                $this->listener->onString($string);
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

            case TokenType::HEX:
                $symbol
                    ->setAttribute('s.text_utf16', $token->getAttribute('json.text_utf16'))
                    ->setAttribute('s.text_is_hi_surrogate', $token->getAttribute('json.text_is_hi_surrogate'))
                    ->setAttribute('s.text_is_lo_surrogate', $token->getAttribute('json.text_is_lo_surrogate'));
                break;
        }
    }

    /**
     * @param Symbol $symbol
     * @return Event\DocumentPartInterface
     * @throws \Remorhaz\UniLex\Exception
     */
    private function createDocumentPart(Symbol $symbol): Event\DocumentPartInterface
    {
        $offset = $this->createOffset($symbol);
        $length = $this->createLength($symbol);
        return new Event\DocumentPart($offset, $length);
    }

    /**
     * @param Symbol $startSymbol
     * @param Symbol $finishSymbol
     * @return Event\DocumentPartInterface
     * @throws \Remorhaz\UniLex\Exception
     */
    private function createDocumentPartBetween(Symbol $startSymbol, Symbol $finishSymbol): Event\DocumentPartInterface
    {
        $offset = $this->createOffset($startSymbol);
        $length = $this->createLengthBetween($startSymbol, $finishSymbol);
        return new Event\DocumentPart($offset, $length);
    }


    /**
     * @param Symbol $symbol
     * @return Event\OffsetInterface
     * @throws \Remorhaz\UniLex\Exception
     */
    private function createOffset(Symbol $symbol): Event\OffsetInterface
    {
        $offsetInBytes = $symbol->getAttribute('s.byte_offset');
        return new Event\Offset($offsetInBytes);
    }

    /**
     * @param Symbol $symbol
     * @return Event\LengthInterface
     * @throws \Remorhaz\UniLex\Exception
     */
    private function createLength(Symbol $symbol): Event\LengthInterface
    {
        $lengthInBytes = $symbol->getAttribute('s.byte_length');
        return new Event\Length($lengthInBytes);
    }

    /**
     * @param Symbol $startSymbol
     * @param Symbol $finishSymbol
     * @return Event\LengthInterface
     * @throws \Remorhaz\UniLex\Exception
     */
    private function createLengthBetween(Symbol $startSymbol, Symbol $finishSymbol): Event\LengthInterface
    {
        $offsetInBytes = $startSymbol->getAttribute('s.byte_offset');
        $lengthInBytes =
            $finishSymbol->getAttribute('s.byte_offset') +
            $finishSymbol->getAttribute('s.byte_length') - $offsetInBytes;
        return new Event\Length($lengthInBytes);
    }

    /**
     * @param Symbol ...$symbolList
     * @return Event\LengthInterface
     * @throws \Remorhaz\UniLex\Exception
     */
    private function createLengthSum(Symbol ...$symbolList): Event\LengthInterface
    {
        $sum = 0;
        foreach ($symbolList as $symbol) {
            $sum += $symbol->getAttribute('s.byte_length');
        }
        return new Event\Length($sum);
    }

    /**
     * @param Symbol $symbol
     * @return Event\StringInterface
     * @throws \Remorhaz\UniLex\Exception
     */
    private function createString(Symbol $symbol): Event\StringInterface
    {
        $documentPart = $this->createDocumentPart($symbol);
        $stringValue = $this->createStringValue($symbol, 's.text');
        return new Event\StringScalar($documentPart, $stringValue);
    }

    /**
     * @param Symbol $symbol
     * @param string $attributeName
     * @return Event\StringValueInterface
     * @throws \Remorhaz\UniLex\Exception
     */
    private function createStringValue(Symbol $symbol, string $attributeName): Event\StringValueInterface
    {
        $text = $symbol->getAttribute($attributeName);
        return new Event\StringValue(...$text);
    }

    /**
     * @param Production $production
     * @param Event\DocumentPartInterface $documentPart
     * @throws \Remorhaz\UniLex\Exception
     */
    private function setHeaderDocumentPart(Production $production, Event\DocumentPartInterface $documentPart): void
    {
        $production
            ->getHeader()
            ->setAttribute('s.byte_offset', $documentPart->getOffset()->inBytes())
            ->setAttribute('s.byte_length', $documentPart->getLength()->inBytes());
    }

    /**
     * @param Production $production
     * @param Symbol $symbol
     * @throws \Remorhaz\UniLex\Exception
     */
    private function copyDocumentPartToHeader(Production $production, Symbol $symbol): void
    {
        $documentPart = $this->createDocumentPart($symbol);
        $this->setHeaderDocumentPart($production, $documentPart);
    }
}
