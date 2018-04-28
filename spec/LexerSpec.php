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

/** @lexToken /[\x20\x09\x0A\x0D]*()/ */
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
    ->setTokenAttribute('json.text', $context->getSymbolString())
    ->setTokenAttribute('json.data', $context->getSymbolList());

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
    ->setContext('string');

/**
 * @lexContext string
 * @lexToken /\x22/
 */
$context
    ->setNewToken(TokenType::QUOTATION_MARK)
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
    ->setTokenAttribute('json.text', $context->getSymbolString());

/**
 * @lexContext stringEsc
 * @lexToken /\x22/
 */
$context
    ->setNewToken(TokenType::QUOTATION_MARK)
    ->setContext('string');

/**
 * @lexContext stringEsc
 * @lexToken /\x5C/
 */
$context
    ->setNewToken(TokenType::REVERSE_SOLIDUS)
    ->setContext('string');

/**
 * @lexContext stringEsc
 * @lexToken /\x2F/
 */
$context
    ->setNewToken(TokenType::SOLIDUS)
    ->setContext('string');

/**
 * @lexContext stringEsc
 * @lexToken /\x62/
 */
$context
    ->setNewToken(TokenType::BACKSPACE)
    ->setContext('string');

/**
 * @lexContext stringEsc
 * @lexToken /\x66/
 */
$context
    ->setNewToken(TokenType::FORM_FEED)
    ->setContext('string');

/**
 * @lexContext stringEsc
 * @lexToken /\x6E/
 */
$context
    ->setNewToken(TokenType::LINE_FEED)
    ->setContext('string');

/**
 * @lexContext stringEsc
 * @lexToken /\x72/
 */
$context
    ->setNewToken(TokenType::CARRIAGE_RETURN)
    ->setContext('string');

/**
 * @lexContext stringEsc
 * @lexToken /\x74/
 */
$context
    ->setNewToken(TokenType::TAB)
    ->setContext('string');

/**
 * @lexContext stringEsc
 * @lexToken /\x75[\x30-\x39\x61-\x66\x41-\x46]{4}/
 */
$context
    ->setNewToken(TokenType::HEX)
    ->setTokenAttribute('json.text', $context->getSymbolString())
    ->setContext('string');
