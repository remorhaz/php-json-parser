<?php

namespace Remorhaz\JSON\Parser;

abstract class SymbolType
{

    public const NT_ROOT = 0x00;

    public const T_LEFT_SQUARE_BRACKET = 0x01;
    public const T_LEFT_CURLY_BRACKET = 0x02;
    public const T_RIGHT_SQUARE_BRACKET = 0x03;
    public const T_RIGHT_CURLY_BRACKET = 0x04;
    public const T_COLON = 0x05;
    public const T_COMMA = 0x06;
    public const T_WS = 0x07;
    public const T_FALSE = 0x08;
    public const T_NULL = 0x09;
    public const T_TRUE = 0x0A;
    public const T_DECIMAL_POINT = 0x0B;
    public const T_DIGIT_1_9 = 0x0C;
    public const T_E = 0x0D;
    public const T_MINUS = 0x0E;
    public const T_PLUS = 0x0F;
    public const T_ZERO = 0x10;
    public const T_QUOTATION_MARK = 0x11;
    public const T_REVERSE_SOLIDUS = 0x12;
    public const T_SOLIDUS = 0x13;
    public const T_BACKSPACE = 0x14;
    public const T_FORM_FEED = 0x15;
    public const T_LINE_FEED = 0x16;
    public const T_CARRIAGE_RETURN = 0x17;
    public const T_TAB = 0x18;
    public const T_HEX = 0x19;
    public const T_ESCAPE = 0x1A;
    public const T_UNESCAPED = 0x1B;

    public const NT_JSON = 0x1C;

    public const T_EOI = 0xFF;
}
