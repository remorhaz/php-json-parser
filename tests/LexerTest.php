<?php

namespace Remorhaz\JSON\Parser\Test;

use PHPUnit\Framework\TestCase;
use Remorhaz\JSON\Parser\TokenMatcher;
use Remorhaz\JSON\Parser\TokenType;
use Remorhaz\UniLex\Grammar\ContextFree\GrammarLoader;
use Remorhaz\UniLex\Grammar\ContextFree\TokenFactory;
use Remorhaz\UniLex\Lexer\TokenReader;
use Remorhaz\UniLex\Unicode\CharBufferFactory;

/**
 * @covers \Remorhaz\JSON\Parser\TokenMatcher
 */
class LexerTest extends TestCase
{

    /**
     * @param string $text
     * @param array $expectedValue
     * @throws \Remorhaz\UniLex\Exception
     * @dataProvider providerValidInputTypeList
     */
    public function testRead_ValidInputRepeatUntilEoi_MatchingTokenTypeList(string $text, array $expectedValue): void
    {
        $buffer = CharBufferFactory::createFromString($text);
        $grammar = GrammarLoader::loadFile(__DIR__ . "/../spec/GrammarSpec.php");
        $lexer = new TokenReader($buffer, new TokenMatcher, new TokenFactory($grammar));
        $tokenTypeList = [];
        do {
            $token = $lexer->read();
            if ($token->isEoi()) {
                break;
            }
            if ($token->getType() == TokenType::DIGIT_1_9) {
                var_dump($token->getAttribute('json.text'));
                var_dump($token->getAttribute('json.data'));
            }
            $tokenTypeList[] = $token->getType();
        } while (true);
        self::assertSame($expectedValue, $tokenTypeList);
    }

    public function providerValidInputTypeList(): array
    {
        return [
            "Empty object" => [
                "{}",
                [TokenType::LEFT_CURLY_BRACKET, TokenType::RIGHT_CURLY_BRACKET],
            ],
            "Empty array" => [
                "[]",
                [TokenType::LEFT_SQUARE_BRACKET, TokenType::RIGHT_SQUARE_BRACKET],
            ],
            "Single null" => [
                "null",
                [TokenType::NULL],
            ],
            "Object with two boolean values" => [
                '{"true":true,"false":false}',
                [
                    TokenType::LEFT_CURLY_BRACKET,
                    TokenType::QUOTATION_MARK,
                    TokenType::UNESCAPED,
                    TokenType::QUOTATION_MARK,
                    TokenType::COLON,
                    TokenType::TRUE,
                    TokenType::COMMA,
                    TokenType::QUOTATION_MARK,
                    TokenType::UNESCAPED,
                    TokenType::QUOTATION_MARK,
                    TokenType::COLON,
                    TokenType::FALSE,
                    TokenType::RIGHT_CURLY_BRACKET,
                ],
            ],
            "Array with two integers and some spaces" => [
                '[ 0, 2]',
                [
                    TokenType::LEFT_SQUARE_BRACKET,
                    TokenType::WS,
                    TokenType::ZERO,
                    TokenType::COMMA,
                    TokenType::WS,
                    TokenType::DIGIT_1_9,
                    TokenType::RIGHT_SQUARE_BRACKET,
                ],
            ],
            "Negative number with exponential part" => [
                '-2.3e+45',
                [
                    TokenType::MINUS,
                    TokenType::DIGIT_1_9,
                    TokenType::DECIMAL_POINT,
                    TokenType::DIGIT_1_9,
                    TokenType::E,
                    TokenType::PLUS,
                    TokenType::DIGIT_1_9,
                ],
            ],
        ];
    }
}
