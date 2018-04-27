<?php
/**
 * @lexTargetClass StringEscTokenMatcher
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
// context -> string

/** @lexToken /\x5C/ */
$context->setNewToken(TokenType::REVERSE_SOLIDUS);
// context -> string

/** @lexToken /\x2F/ */
$context->setNewToken(TokenType::SOLIDUS);
// context -> string

/** @lexToken /\x62/ */
$context->setNewToken(TokenType::BACKSPACE);
// context -> string

/** @lexToken /\x66/ */
$context->setNewToken(TokenType::FORM_FEED);
// context -> string

/** @lexToken /\x6E/ */
$context->setNewToken(TokenType::LINE_FEED);
// context -> string

/** @lexToken /\x72/ */
$context->setNewToken(TokenType::CARRIAGE_RETURN);
// context -> string

/** @lexToken /\x74/ */
$context->setNewToken(TokenType::TAB);
// context -> string

/** @lexToken /\x75[\x30-\x39\x61-\x66\x41-\x46]{4}/ */
$context->setNewToken(TokenType::HEX);
// context -> string
