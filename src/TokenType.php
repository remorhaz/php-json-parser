<?php

namespace Remorhaz\JSON\Parser;

abstract class TokenType
{

    public const LEFT_SQUARE_BRACKET = 0x01;
    public const LEFT_CURLY_BRACKET = 0x02;
    public const RIGHT_SQUARE_BRACKET = 0x03;
    public const RIGHT_CURLY_BRACKET = 0x04;
    public const COLON = 0x05;
    public const COMMA = 0x06;
    public const WS = 0x07;
    public const FALSE = 0x08;
    public const NULL = 0x09;
    public const TRUE = 0x0A;
    public const DECIMAL_POINT = 0x0B;
    public const DIGIT_1_9 = 0x0C;
    public const E = 0x0D;
    public const MINUS = 0x0E;
    public const PLUS = 0x0F;
    public const ZERO = 0x10;
    public const QUOTATION_MARK = 0x11;
    public const REVERSE_SOLIDUS = 0x12;
    public const SOLIDUS = 0x13;
    public const BACKSPACE = 0x14;
    public const FORM_FEED = 0x15;
    public const LINE_FEED = 0x16;
    public const CARRIAGE_RETURN = 0x17;
    public const TAB = 0x18;
    public const HEX = 0x19;
    public const ESCAPE = 0x1A;
    public const UNESCAPED = 0x1B;
    public const EOI = 0xFF;
}
