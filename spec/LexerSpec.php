<?php
/**
 * @lexTargetClass TokenMatcher
 * @lexHeader
 */

namespace Remorhaz\JSON\Parser;

use Remorhaz\UniLex\Lexer\TokenMatcherInterface;

/**
 * @var \Remorhaz\UniLex\Lexer\TokenMatcherContextInterface $context
 * @var int $code
 *
 * @lexToken /\x5B/
 */
$context->setNewToken(TokenType::LEFT_SQUARE_BRACKET);

/** @lexToken /\x7B/ */
$context->setNewToken(TokenType::LEFT_CURLY_BRACKET);

/** @lexToken /\x5D/ */
$context->setNewToken(TokenType::RIGHT_SQUARE_BRACKET);

/** @lexToken /\x7D/ */
$context->setNewToken(TokenType::RIGHT_CURLY_BRACKET);

/** @lexToken /\x3A/ */
$context->setNewToken(TokenType::COLON);

/** @lexToken /\x2C/ */
$context->setNewToken(TokenType::COMMA);

/** @lexToken /[\x20\x09\x0A\x0D]+/ */
$context->setNewToken(TokenType::WS);

/** @lexToken /\x66\x61\x6C\x73\x65/ */
$context->setNewToken(TokenType::FALSE);

/** @lexToken /\x6E\x75\x6C\x6C/ */
$context->setNewToken(TokenType::NULL);

/** @lexToken /\x74\x72\x75\x65/ */
$context->setNewToken(TokenType::TRUE);

/** @lexToken /\x2E/ */
$context->setNewToken(TokenType::DECIMAL_POINT);

/** @lexToken /([\x31-\x39][\x30-\x39]*)/ */
$context
    ->setNewToken(TokenType::DIGIT_1_9)
    ->setTokenAttribute('json.text', $context->getSymbolList());

/** @lexToken /[\x65\x45]/ */
$context->setNewToken(TokenType::E);

/** @lexToken /\x2D/ */
$context->setNewToken(TokenType::MINUS);

/** @lexToken /\x2B/ */
$context->setNewToken(TokenType::PLUS);

/** @lexToken /\x30/ */
$context->setNewToken(TokenType::ZERO);

/** @lexToken /\x22/ */
$context
    ->setNewToken(TokenType::QUOTATION_MARK)
    ->setTokenAttribute('json.context', TokenMatcherInterface::DEFAULT_CONTEXT)
    ->setContext('string');

/**
 * @lexContext string
 * @lexToken /\x22/
 */
$context
    ->setNewToken(TokenType::QUOTATION_MARK)
    ->setTokenAttribute('json.context', 'string')
    ->setContext(TokenMatcherInterface::DEFAULT_CONTEXT);

/**
 * @lexContext string
 * @lexToken /\x5C/
 */
$context
    ->setNewToken(TokenType::ESCAPE)
    ->setContext('stringEsc');

/**
 * @lexContext string
 * @lexToken /[\x20-\x21\x23-\x5B\x5D-\x{10FFFF}]+/
 */
$context
    ->setNewToken(TokenType::UNESCAPED)
    ->setTokenAttribute('json.text', $context->getSymbolList());

/**
 * @lexContext stringEsc
 * @lexToken /\x22/
 */
$context
    ->setNewToken(TokenType::QUOTATION_MARK)
    ->setTokenAttribute('json.context', 'stringEsc')
    ->setTokenAttribute('json.text', [0x22])
    ->setContext('string');

/**
 * @lexContext stringEsc
 * @lexToken /\x5C/
 */
$context
    ->setNewToken(TokenType::REVERSE_SOLIDUS)
    ->setTokenAttribute('json.text', [0x5C])
    ->setContext('string');

/**
 * @lexContext stringEsc
 * @lexToken /\x2F/
 */
$context
    ->setNewToken(TokenType::SOLIDUS)
    ->setTokenAttribute('json.text', [0x2F])
    ->setContext('string');

/**
 * @lexContext stringEsc
 * @lexToken /\x62/
 */
$context
    ->setNewToken(TokenType::BACKSPACE)
    ->setTokenAttribute('json.text', [0x08])
    ->setContext('string');

/**
 * @lexContext stringEsc
 * @lexToken /\x66/
 */
$context
    ->setNewToken(TokenType::FORM_FEED)
    ->setTokenAttribute('json.text', [0x0C])
    ->setContext('string');

/**
 * @lexContext stringEsc
 * @lexToken /\x6E/
 */
$context
    ->setNewToken(TokenType::LINE_FEED)
    ->setTokenAttribute('json.text', [0x0A])
    ->setContext('string');

/**
 * @lexContext stringEsc
 * @lexToken /\x72/
 */
$context
    ->setNewToken(TokenType::CARRIAGE_RETURN)
    ->setTokenAttribute('json.text', [0x0D])
    ->setContext('string');

/**
 * @lexContext stringEsc
 * @lexToken /\x74/
 */
$context
    ->setNewToken(TokenType::TAB)
    ->setTokenAttribute('json.text', [0x09])
    ->setContext('string');

/**
 * @lexContext stringEsc
 * @lexToken /\x75[\x30-\x39\x61-\x66\x41-\x46]{4}/
 */
// Extracting hexadecimal 16-bit symbol code
$symbolList = $context->getSymbolList();
$symbol = 0;
for ($i = 4; $i > 0; $i--) {
    $power = 4 - $i;
    $digit = $symbolList[$i];
    if (0x30 <= $digit && $digit <= 0x39) {
        $digit -= 0x30;
    } elseif (0x61 <= $digit && $digit <= 0x66) {
        $digit -= 0x57;
    } else {
        $digit -= 0x37;
    }
    $symbol += $digit * pow(0x10, $power);
}
$isHiSurrogate = 0xD800 <= $symbol && $symbol <= 0xDBFF;
$isLoSurrogate = 0xDC00 <= $symbol && $symbol <= 0xDFFF;
$context
    ->setNewToken(TokenType::HEX)
    ->setTokenAttribute('json.text_utf16', $symbol)
    ->setTokenAttribute('json.text_is_hi_surrogate', $isHiSurrogate)
    ->setTokenAttribute('json.text_is_lo_surrogate', $isLoSurrogate)
    ->setContext('string');
