<?php

namespace Remorhaz\JSON\Parser;

use Remorhaz\UniLex\Grammar\ContextFree\GrammarLoader;

return [
    GrammarLoader::ROOT_SYMBOL_KEY => SymbolType::NT_ROOT,
    GrammarLoader::EOI_SYMBOL_KEY => SymbolType::T_EOI,
    GrammarLoader::START_SYMBOL_KEY => SymbolType::NT_JSON,

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
    ],
];