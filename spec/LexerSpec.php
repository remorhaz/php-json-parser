<?php
/**
 * @lexTargetClass TokenMatcher
 * @lexHeader
 */

namespace Remorhaz\JSON\Parser;

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

/** @lexToken /[\x31-\x39]/ */
$context->setNewToken(TokenType::DIGIT_1_9);

/** @lexToken /[\x65\x45]/ */
$context->setNewToken(TokenType::E);

/** @lexToken /\x2D/ */
$context->setNewToken(TokenType::MINUS);

/** @lexToken /\x2B/ */
$context->setNewToken(TokenType::PLUS);

/** @lexToken /\x30/ */
$context->setNewToken(TokenType::ZERO);

/** @lexToken /\x22/ */
$context->setNewToken(TokenType::QUOTATION_MARK);
// context -> string
