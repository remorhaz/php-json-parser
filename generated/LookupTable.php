<?php
/**
 * JSON parser LL(1) lookup table.
 *
 * Auto-generated file, please don't edit manually.
 * Run following command to update this file:
 *     vendor/bin/phing json-parser-lookup
 *
 * Phing version: 2.16.1
 */

use Remorhaz\JSON\Parser\SymbolType;
use Remorhaz\JSON\Parser\TokenType;

return [
    SymbolType::NT_JSON => [
        TokenType::WS => 0,
        TokenType::FALSE => 0,
        TokenType::NULL => 0,
        TokenType::TRUE => 0,
        TokenType::LEFT_CURLY_BRACKET => 0,
        TokenType::LEFT_SQUARE_BRACKET => 0,
        TokenType::MINUS => 0,
        TokenType::QUOTATION_MARK => 0,
        TokenType::ZERO => 0,
        TokenType::DIGIT_1_9 => 0,
    ],
    SymbolType::NT_WS => [
        TokenType::WS => 0,
        TokenType::FALSE => 1,
        TokenType::NULL => 1,
        TokenType::TRUE => 1,
        TokenType::LEFT_CURLY_BRACKET => 1,
        TokenType::LEFT_SQUARE_BRACKET => 1,
        TokenType::MINUS => 1,
        TokenType::QUOTATION_MARK => 1,
        TokenType::ZERO => 1,
        TokenType::DIGIT_1_9 => 1,
        TokenType::EOI => 1,
        TokenType::RIGHT_CURLY_BRACKET => 1,
        TokenType::COLON => 1,
        TokenType::COMMA => 1,
        TokenType::RIGHT_SQUARE_BRACKET => 1,
    ],
    SymbolType::NT_VALUE => [
        TokenType::FALSE => 0,
        TokenType::NULL => 1,
        TokenType::TRUE => 2,
        TokenType::LEFT_CURLY_BRACKET => 3,
        TokenType::LEFT_SQUARE_BRACKET => 4,
        TokenType::MINUS => 5,
        TokenType::ZERO => 5,
        TokenType::DIGIT_1_9 => 5,
        TokenType::QUOTATION_MARK => 6,
    ],
    SymbolType::NT_OBJECT => [
        TokenType::LEFT_CURLY_BRACKET => 0,
    ],
    SymbolType::NT_OBJECT_MEMBERS => [
        TokenType::QUOTATION_MARK => 0,
        TokenType::RIGHT_CURLY_BRACKET => 1,
    ],
    SymbolType::NT_OBJECT_MEMBER => [
        TokenType::QUOTATION_MARK => 0,
    ],
    SymbolType::NT_NEXT_OBJECT_MEMBERS => [
        TokenType::COMMA => 0,
        TokenType::RIGHT_CURLY_BRACKET => 1,
    ],
    SymbolType::NT_ARRAY => [
        TokenType::LEFT_SQUARE_BRACKET => 0,
    ],
    SymbolType::NT_ARRAY_VALUES => [
        TokenType::FALSE => 0,
        TokenType::NULL => 0,
        TokenType::TRUE => 0,
        TokenType::LEFT_CURLY_BRACKET => 0,
        TokenType::LEFT_SQUARE_BRACKET => 0,
        TokenType::MINUS => 0,
        TokenType::QUOTATION_MARK => 0,
        TokenType::ZERO => 0,
        TokenType::DIGIT_1_9 => 0,
        TokenType::RIGHT_SQUARE_BRACKET => 1,
    ],
    SymbolType::NT_NEXT_ARRAY_VALUES => [
        TokenType::COMMA => 0,
        TokenType::RIGHT_SQUARE_BRACKET => 1,
    ],
    SymbolType::NT_NUMBER => [
        TokenType::MINUS => 0,
        TokenType::ZERO => 1,
        TokenType::DIGIT_1_9 => 1,
    ],
    SymbolType::NT_UNSIGNED_NUMBER => [
        TokenType::ZERO => 0,
        TokenType::DIGIT_1_9 => 0,
    ],
    SymbolType::NT_INT => [
        TokenType::ZERO => 0,
        TokenType::DIGIT_1_9 => 1,
    ],
    SymbolType::NT_INT_TAIL => [
        TokenType::DECIMAL_POINT => 0,
        TokenType::E => 1,
        TokenType::WS => 1,
        TokenType::EOI => 1,
        TokenType::COMMA => 1,
        TokenType::RIGHT_CURLY_BRACKET => 1,
        TokenType::RIGHT_SQUARE_BRACKET => 1,
    ],
    SymbolType::NT_FRAC => [
        TokenType::DECIMAL_POINT => 0,
    ],
    SymbolType::NT_DIGIT => [
        TokenType::ZERO => 0,
        TokenType::DIGIT_1_9 => 1,
    ],
    SymbolType::NT_OPT_DIGIT => [
        TokenType::ZERO => 0,
        TokenType::DIGIT_1_9 => 0,
        TokenType::E => 1,
        TokenType::WS => 1,
        TokenType::EOI => 1,
        TokenType::COMMA => 1,
        TokenType::RIGHT_CURLY_BRACKET => 1,
        TokenType::RIGHT_SQUARE_BRACKET => 1,
    ],
    SymbolType::NT_OPT_EXP => [
        TokenType::E => 0,
        TokenType::WS => 1,
        TokenType::EOI => 1,
        TokenType::COMMA => 1,
        TokenType::RIGHT_CURLY_BRACKET => 1,
        TokenType::RIGHT_SQUARE_BRACKET => 1,
    ],
    SymbolType::NT_OPT_SIGN => [
        TokenType::MINUS => 0,
        TokenType::PLUS => 1,
        TokenType::ZERO => 2,
        TokenType::DIGIT_1_9 => 2,
    ],
    SymbolType::NT_STRING => [
        TokenType::QUOTATION_MARK => 0,
    ],
    SymbolType::NT_STRING_CONTENT => [
        TokenType::ESCAPE => 0,
        TokenType::UNESCAPED => 1,
        TokenType::QUOTATION_MARK => 2,
    ],
    SymbolType::NT_ESCAPED => [
        TokenType::QUOTATION_MARK => 0,
        TokenType::REVERSE_SOLIDUS => 1,
        TokenType::SOLIDUS => 2,
        TokenType::BACKSPACE => 3,
        TokenType::FORM_FEED => 4,
        TokenType::LINE_FEED => 5,
        TokenType::CARRIAGE_RETURN => 6,
        TokenType::TAB => 7,
        TokenType::HEX => 8,
    ],
];
