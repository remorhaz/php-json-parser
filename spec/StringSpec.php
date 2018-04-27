<?php
/**
 * @lexTargetClass StringTokenMatcher
 * @lexHeader
 */

namespace Remorhaz\JSON\Parser;

/**
 * @var \Remorhaz\UniLex\Lexer\TokenMatcherContextInterface $context
 * @var int $code
 *
 * @lexToken /\x22/
 */
$context->setNewToken(TokenType::QUOTATION_MARK);
// context -> default

/** @lexToken /\x5C/ */
$context->setNewToken(TokenType::ESCAPE);
// context -> string esc

/** @lexToken /[\x20-\x21\x23-\x5B\x5D-\x{10FFFF}]+/ */
$context->setNewToken(TokenType::UNESCAPED);
