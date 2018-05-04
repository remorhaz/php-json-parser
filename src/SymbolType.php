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
    public const NT_WS = 0x1D;
    public const NT_VALUE = 0x1E;
    public const NT_OBJECT = 0x1F;
    public const NT_ARRAY = 0x20;
    public const NT_NUMBER = 0x21;
    public const NT_STRING = 0x22;
    public const NT_OBJECT_MEMBERS = 0x23;
    public const NT_OBJECT_MEMBER = 0x24;
    public const NT_NEXT_OBJECT_MEMBERS = 0x25;
    public const NT_ARRAY_VALUES = 0x26;
    public const NT_NEXT_ARRAY_VALUES = 0x27;
    public const NT_UNSIGNED_NUMBER = 0x28;
    public const NT_INT = 0x29;
    public const NT_INT_TAIL = 0x2A;
    public const NT_FRAC = 0x2B;
    public const NT_OPT_EXP = 0x2C;
    public const NT_DIGIT = 0x2D;
    public const NT_OPT_SIGN = 0x2E;
    public const NT_OPT_DIGIT = 0x2F;
    public const NT_STRING_CONTENT = 0x30;
    public const NT_ESCAPED = 0x31;

    public const T_EOI = 0xFF;
}
