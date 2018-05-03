<?php

namespace Remorhaz\JSON\Parser;

use Remorhaz\UniLex\Grammar\ContextFree\GrammarLoader;

return [
    GrammarLoader::ROOT_SYMBOL_KEY => SymbolType::NT_ROOT,
    GrammarLoader::EOI_SYMBOL_KEY => SymbolType::T_EOI,
    GrammarLoader::START_SYMBOL_KEY => SymbolType::NT_VALUE_WS,

    GrammarLoader::TOKEN_MAP_KEY => [
        SymbolType::T_LEFT_SQUARE_BRACKET => TokenType::LEFT_SQUARE_BRACKET,
        SymbolType::T_LEFT_CURLY_BRACKET => TokenType::LEFT_CURLY_BRACKET,
        SymbolType::T_RIGHT_SQUARE_BRACKET => TokenType::RIGHT_SQUARE_BRACKET,
        SymbolType::T_RIGHT_CURLY_BRACKET => TokenType::RIGHT_CURLY_BRACKET,
        SymbolType::T_COLON => TokenType::COLON,
        SymbolType::T_COMMA => TokenType::COMMA,
        SymbolType::T_WS => TokenType::WS,
        SymbolType::T_FALSE => TokenType::FALSE,
        SymbolType::T_NULL => TokenType::NULL,
        SymbolType::T_TRUE => TokenType::TRUE,
        SymbolType::T_DECIMAL_POINT => TokenType::DECIMAL_POINT,
        SymbolType::T_DIGIT_1_9 => TokenType::DIGIT_1_9,
        SymbolType::T_E => TokenType::E,
        SymbolType::T_MINUS => TokenType::MINUS,
        SymbolType::T_PLUS => TokenType::PLUS,
        SymbolType::T_ZERO => TokenType::ZERO,
        SymbolType::T_QUOTATION_MARK => TokenType::QUOTATION_MARK,
        SymbolType::T_REVERSE_SOLIDUS => TokenType::REVERSE_SOLIDUS,
        SymbolType::T_SOLIDUS => TokenType::SOLIDUS,
        SymbolType::T_BACKSPACE => TokenType::BACKSPACE,
        SymbolType::T_FORM_FEED => TokenType::FORM_FEED,
        SymbolType::T_LINE_FEED => TokenType::LINE_FEED,
        SymbolType::T_CARRIAGE_RETURN => TokenType::CARRIAGE_RETURN,
        SymbolType::T_TAB => TokenType::TAB,
        SymbolType::T_HEX => TokenType::HEX,
        SymbolType::T_ESCAPE => TokenType::ESCAPE,
        SymbolType::T_UNESCAPED => TokenType::UNESCAPED,
        SymbolType::T_EOI => TokenType::EOI,
    ],

    GrammarLoader::PRODUCTION_MAP_KEY => [
        SymbolType::NT_ROOT => [
            [SymbolType::NT_WS, SymbolType::NT_VALUE_WS, SymbolType::T_EOI],
        ],
        SymbolType::NT_VALUE_WS => [
            [SymbolType::NT_VALUE, SymbolType::NT_WS],
        ],
        SymbolType::NT_WS => [
            [SymbolType::T_WS],
            [],
        ],
        SymbolType::NT_VALUE => [
            [SymbolType::T_FALSE],
            [SymbolType::T_NULL],
            [SymbolType::T_TRUE],
            [SymbolType::NT_OBJECT],
            [SymbolType::NT_ARRAY],
            [SymbolType::NT_NUMBER],
            [SymbolType::NT_STRING],
        ],
        SymbolType::NT_OBJECT => [
            [SymbolType::NT_BEGIN_OBJECT, SymbolType::NT_OBJECT_MEMBERS, SymbolType::NT_END_OBJECT],
        ],
        SymbolType::NT_BEGIN_OBJECT => [
            [SymbolType::T_LEFT_CURLY_BRACKET, SymbolType::NT_WS],
        ],
        SymbolType::NT_OBJECT_MEMBERS => [
            [SymbolType::NT_OBJECT_MEMBER, SymbolType::NT_NEXT_OBJECT_MEMBERS],
            [],
        ],
        SymbolType::NT_OBJECT_MEMBER => [
            [SymbolType::NT_STRING, SymbolType::NT_NAME_SEPARATOR, SymbolType::NT_VALUE_WS],
        ],
        SymbolType::NT_NAME_SEPARATOR => [
            [SymbolType::NT_WS, SymbolType::T_COLON, SymbolType::NT_WS],
        ],
        SymbolType::NT_NEXT_OBJECT_MEMBERS => [
            [SymbolType::NT_VALUE_SEPARATOR, SymbolType::NT_OBJECT_MEMBER, SymbolType::NT_NEXT_OBJECT_MEMBERS],
            [SymbolType::NT_WS],
            [],
        ],
        SymbolType::NT_VALUE_SEPARATOR => [
            [SymbolType::T_COMMA, SymbolType::NT_WS],
        ],
        SymbolType::NT_END_OBJECT => [
            [SymbolType::T_RIGHT_CURLY_BRACKET],
        ],
        SymbolType::NT_ARRAY => [
            [SymbolType::NT_BEGIN_ARRAY, SymbolType::NT_ARRAY_VALUES, SymbolType::NT_END_ARRAY],
        ],
        SymbolType::NT_BEGIN_ARRAY => [
            [SymbolType::T_LEFT_SQUARE_BRACKET, SymbolType::NT_WS],
        ],
        SymbolType::NT_ARRAY_VALUES => [
            [SymbolType::NT_VALUE_WS, SymbolType::NT_NEXT_ARRAY_VALUES],
            [],
        ],
        SymbolType::NT_NEXT_ARRAY_VALUES => [
            [SymbolType::NT_VALUE_SEPARATOR, SymbolType::NT_VALUE_WS, SymbolType::NT_NEXT_ARRAY_VALUES],
            [SymbolType::NT_WS],
            [],
        ],
        SymbolType::NT_END_ARRAY => [
            [SymbolType::T_RIGHT_SQUARE_BRACKET],
        ],
        SymbolType::NT_NUMBER => [
            [SymbolType::T_MINUS, SymbolType::NT_UNSIGNED_NUMBER],
            [SymbolType::NT_UNSIGNED_NUMBER],
        ],
        SymbolType::NT_UNSIGNED_NUMBER => [
            [SymbolType::NT_INT, SymbolType::NT_INT_TAIL],
        ],
        SymbolType::NT_INT => [
            [SymbolType::T_ZERO],
            [SymbolType::T_DIGIT_1_9],
        ],
        SymbolType::NT_INT_TAIL => [
            [SymbolType::NT_FRAC, SymbolType::NT_OPT_EXP],
            [SymbolType::NT_OPT_EXP],
        ],
        SymbolType::NT_FRAC => [
            [SymbolType::T_DECIMAL_POINT, SymbolType::NT_DIGIT],
        ],
        SymbolType::NT_DIGIT => [
            [SymbolType::T_ZERO, SymbolType::NT_OPT_DIGIT],
            [SymbolType::T_DIGIT_1_9],
        ],
        SymbolType::NT_OPT_DIGIT => [
            [SymbolType::NT_DIGIT],
            [],
        ],
        SymbolType::NT_OPT_EXP => [
            [SymbolType::T_E, SymbolType::NT_OPT_SIGN, SymbolType::NT_DIGIT],
            [],
        ],
        SymbolType::NT_OPT_SIGN => [
            [SymbolType::T_MINUS],
            [SymbolType::T_PLUS],
            [],
        ],
    ],
];
