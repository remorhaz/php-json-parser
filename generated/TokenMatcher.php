<?php
/**
 * JSON token matcher.
 *
 * Auto-generated file, please don't edit manually.
 * Run following command to update this file:
 *     vendor/bin/phing json-token-matcher
 *
 * Phing version: 2.16.1
 */

namespace Remorhaz\JSON\Parser;

use Remorhaz\UniLex\IO\CharBufferInterface;
use Remorhaz\UniLex\Lexer\TokenFactoryInterface;
use Remorhaz\UniLex\Lexer\TokenMatcherInterface;
use Remorhaz\UniLex\Lexer\TokenMatcherTemplate;

class TokenMatcher extends TokenMatcherTemplate
{

    public function match(CharBufferInterface $buffer, TokenFactoryInterface $tokenFactory): bool
    {
        $context = $this->createContext($buffer, $tokenFactory);
        if ($context->getContext() == 'string') {
            goto stateString1;
        }
        if ($context->getContext() == 'stringEsc') {
            goto stateStringEsc1;
        }
        goto state1;

        state1:
        if ($context->getBuffer()->isEnd()) {
            goto error;
        }
        $char = $context->getBuffer()->getSymbol();
        if (0x5B == $char) {
            $context->getBuffer()->nextSymbol();
            $context->setNewToken(TokenType::LEFT_SQUARE_BRACKET);
            return true;
        }
        if (0x7B == $char) {
            $context->getBuffer()->nextSymbol();
            $context->setNewToken(TokenType::LEFT_CURLY_BRACKET);
            return true;
        }
        if (0x5D == $char) {
            $context->getBuffer()->nextSymbol();
            $context->setNewToken(TokenType::RIGHT_SQUARE_BRACKET);
            return true;
        }
        if (0x7D == $char) {
            $context->getBuffer()->nextSymbol();
            $context->setNewToken(TokenType::RIGHT_CURLY_BRACKET);
            return true;
        }
        if (0x3A == $char) {
            $context->getBuffer()->nextSymbol();
            $context->setNewToken(TokenType::COLON);
            return true;
        }
        if (0x2C == $char) {
            $context->getBuffer()->nextSymbol();
            $context->setNewToken(TokenType::COMMA);
            return true;
        }
        if (0x09 == $char || 0x0A == $char || 0x0D == $char || 0x20 == $char) {
            $context->getBuffer()->nextSymbol();
            goto state8;
        }
        if (0x66 == $char) {
            $context->getBuffer()->nextSymbol();
            goto state9;
        }
        if (0x65 == $char) {
            $context->getBuffer()->nextSymbol();
            goto state10;
        }
        if (0x45 == $char) {
            $context->getBuffer()->nextSymbol();
            goto state10;
        }
        if (0x6E == $char) {
            $context->getBuffer()->nextSymbol();
            goto state11;
        }
        if (0x74 == $char) {
            $context->getBuffer()->nextSymbol();
            goto state12;
        }
        if (0x2E == $char) {
            $context->getBuffer()->nextSymbol();
            $context->setNewToken(TokenType::DECIMAL_POINT);
            return true;
        }
        if (0x31 <= $char && $char <= 0x39) {
            $context->getBuffer()->nextSymbol();
            goto state14;
        }
        if (0x30 == $char) {
            $context->getBuffer()->nextSymbol();
            $context->setNewToken(TokenType::ZERO);
            return true;
        }
        if (0x2D == $char) {
            $context->getBuffer()->nextSymbol();
            $context->setNewToken(TokenType::MINUS);
            return true;
        }
        if (0x2B == $char) {
            $context->getBuffer()->nextSymbol();
            $context->setNewToken(TokenType::PLUS);
            return true;
        }
        if (0x22 == $char) {
            $context->getBuffer()->nextSymbol();
            $context
                ->setNewToken(TokenType::QUOTATION_MARK)
                ->setTokenAttribute('json.context', TokenMatcherInterface::DEFAULT_CONTEXT)
                ->setContext('string');
            return true;
        }
        goto error;

        state8:
        if ($context->getBuffer()->isEnd()) {
            goto finish8;
        }
        $char = $context->getBuffer()->getSymbol();
        if (0x09 == $char || 0x0A == $char || 0x0D == $char || 0x20 == $char) {
            $context->getBuffer()->nextSymbol();
            goto state30;
        }
        finish8:
        $context->setNewToken(TokenType::WS);
        return true;

        state9:
        if ($context->getBuffer()->isEnd()) {
            goto error;
        }
        $char = $context->getBuffer()->getSymbol();
        if (0x61 == $char) {
            $context->getBuffer()->nextSymbol();
            goto state26;
        }
        goto error;

        state10:
        $context->setNewToken(TokenType::E);
        return true;

        state11:
        if ($context->getBuffer()->isEnd()) {
            goto error;
        }
        $char = $context->getBuffer()->getSymbol();
        if (0x75 == $char) {
            $context->getBuffer()->nextSymbol();
            goto state23;
        }
        goto error;

        state12:
        if ($context->getBuffer()->isEnd()) {
            goto error;
        }
        $char = $context->getBuffer()->getSymbol();
        if (0x72 == $char) {
            $context->getBuffer()->nextSymbol();
            goto state20;
        }
        goto error;

        state14:
        if ($context->getBuffer()->isEnd()) {
            goto finish14;
        }
        $char = $context->getBuffer()->getSymbol();
        if (0x31 <= $char && $char <= 0x39) {
            $context->getBuffer()->nextSymbol();
            goto state19;
        }
        if (0x30 == $char) {
            $context->getBuffer()->nextSymbol();
            goto state19;
        }
        finish14:
        $context
            ->setNewToken(TokenType::DIGIT_1_9)
            ->setTokenAttribute('json.text', $context->getSymbolString())
            ->setTokenAttribute('json.data', $context->getSymbolList());
        return true;

        state19:
        if ($context->getBuffer()->isEnd()) {
            goto finish19;
        }
        $char = $context->getBuffer()->getSymbol();
        if (0x31 <= $char && $char <= 0x39) {
            $context->getBuffer()->nextSymbol();
            goto state19;
        }
        if (0x30 == $char) {
            $context->getBuffer()->nextSymbol();
            goto state19;
        }
        finish19:
        $context
            ->setNewToken(TokenType::DIGIT_1_9)
            ->setTokenAttribute('json.text', $context->getSymbolString())
            ->setTokenAttribute('json.data', $context->getSymbolList());
        return true;

        state20:
        if ($context->getBuffer()->isEnd()) {
            goto error;
        }
        $char = $context->getBuffer()->getSymbol();
        if (0x75 == $char) {
            $context->getBuffer()->nextSymbol();
            goto state21;
        }
        goto error;

        state21:
        if ($context->getBuffer()->isEnd()) {
            goto error;
        }
        $char = $context->getBuffer()->getSymbol();
        if (0x65 == $char) {
            $context->getBuffer()->nextSymbol();
            $context->setNewToken(TokenType::TRUE);
            return true;
        }
        goto error;

        state23:
        if ($context->getBuffer()->isEnd()) {
            goto error;
        }
        $char = $context->getBuffer()->getSymbol();
        if (0x6C == $char) {
            $context->getBuffer()->nextSymbol();
            goto state24;
        }
        goto error;

        state24:
        if ($context->getBuffer()->isEnd()) {
            goto error;
        }
        $char = $context->getBuffer()->getSymbol();
        if (0x6C == $char) {
            $context->getBuffer()->nextSymbol();
            $context->setNewToken(TokenType::NULL);
            return true;
        }
        goto error;

        state26:
        if ($context->getBuffer()->isEnd()) {
            goto error;
        }
        $char = $context->getBuffer()->getSymbol();
        if (0x6C == $char) {
            $context->getBuffer()->nextSymbol();
            goto state27;
        }
        goto error;

        state27:
        if ($context->getBuffer()->isEnd()) {
            goto error;
        }
        $char = $context->getBuffer()->getSymbol();
        if (0x73 == $char) {
            $context->getBuffer()->nextSymbol();
            goto state28;
        }
        goto error;

        state28:
        if ($context->getBuffer()->isEnd()) {
            goto error;
        }
        $char = $context->getBuffer()->getSymbol();
        if (0x65 == $char) {
            $context->getBuffer()->nextSymbol();
            $context->setNewToken(TokenType::FALSE);
            return true;
        }
        goto error;

        state30:
        if ($context->getBuffer()->isEnd()) {
            goto finish30;
        }
        $char = $context->getBuffer()->getSymbol();
        if (0x09 == $char || 0x0A == $char || 0x0D == $char || 0x20 == $char) {
            $context->getBuffer()->nextSymbol();
            goto state30;
        }
        finish30:
        $context->setNewToken(TokenType::WS);
        return true;

        stateString1:
        if ($context->getBuffer()->isEnd()) {
            goto error;
        }
        $char = $context->getBuffer()->getSymbol();
        if (0x22 == $char) {
            $context->getBuffer()->nextSymbol();
            $context
                ->setNewToken(TokenType::QUOTATION_MARK)
                ->setTokenAttribute('json.context', 'string')
                ->setContext(TokenMatcherInterface::DEFAULT_CONTEXT);
            return true;
        }
        if (0x5C == $char) {
            $context->getBuffer()->nextSymbol();
            $context
                ->setNewToken(TokenType::ESCAPE)
                ->setContext('stringEsc');
            return true;
        }
        if (0x20 == $char || 0x21 == $char || 0x23 <= $char && $char <= 0x5B || 0x5D <= $char && $char <= 0x10FFFF) {
            $context->getBuffer()->nextSymbol();
            goto stateString4;
        }
        goto error;

        stateString4:
        if ($context->getBuffer()->isEnd()) {
            goto finishString4;
        }
        $char = $context->getBuffer()->getSymbol();
        if (0x20 == $char || 0x21 == $char || 0x23 <= $char && $char <= 0x5B || 0x5D <= $char && $char <= 0x10FFFF) {
            $context->getBuffer()->nextSymbol();
            goto stateString5;
        }
        finishString4:
        $context
            ->setNewToken(TokenType::UNESCAPED)
            ->setTokenAttribute('json.text', $context->getSymbolList());
        return true;

        stateString5:
        if ($context->getBuffer()->isEnd()) {
            goto finishString5;
        }
        $char = $context->getBuffer()->getSymbol();
        if (0x20 == $char || 0x21 == $char || 0x23 <= $char && $char <= 0x5B || 0x5D <= $char && $char <= 0x10FFFF) {
            $context->getBuffer()->nextSymbol();
            goto stateString5;
        }
        finishString5:
        $context
            ->setNewToken(TokenType::UNESCAPED)
            ->setTokenAttribute('json.text', $context->getSymbolList());
        return true;

        stateStringEsc1:
        if ($context->getBuffer()->isEnd()) {
            goto error;
        }
        $char = $context->getBuffer()->getSymbol();
        if (0x22 == $char) {
            $context->getBuffer()->nextSymbol();
            $context
                ->setNewToken(TokenType::QUOTATION_MARK)
                ->setTokenAttribute('json.context', 'stringEsc')
                ->setTokenAttribute('json.text', [0x22])
                ->setContext('string');
            return true;
        }
        if (0x5C == $char) {
            $context->getBuffer()->nextSymbol();
            $context
                ->setNewToken(TokenType::REVERSE_SOLIDUS)
                ->setTokenAttribute('json.text', [0x5C])
                ->setContext('string');
            return true;
        }
        if (0x2F == $char) {
            $context->getBuffer()->nextSymbol();
            $context
                ->setNewToken(TokenType::SOLIDUS)
                ->setTokenAttribute('json.text', [0x2F])
                ->setContext('string');
            return true;
        }
        if (0x62 == $char) {
            $context->getBuffer()->nextSymbol();
            $context
                ->setNewToken(TokenType::BACKSPACE)
                ->setTokenAttribute('json.text', [0x08])
                ->setContext('string');
            return true;
        }
        if (0x66 == $char) {
            $context->getBuffer()->nextSymbol();
            $context
                ->setNewToken(TokenType::FORM_FEED)
                ->setTokenAttribute('json.text', [0x0C])
                ->setContext('string');
            return true;
        }
        if (0x6E == $char) {
            $context->getBuffer()->nextSymbol();
            $context
                ->setNewToken(TokenType::LINE_FEED)
                ->setTokenAttribute('json.text', [0x0A])
                ->setContext('string');
            return true;
        }
        if (0x72 == $char) {
            $context->getBuffer()->nextSymbol();
            $context
                ->setNewToken(TokenType::CARRIAGE_RETURN)
                ->setTokenAttribute('json.text', [0x0D])
                ->setContext('string');
            return true;
        }
        if (0x74 == $char) {
            $context->getBuffer()->nextSymbol();
            $context
                ->setNewToken(TokenType::TAB)
                ->setTokenAttribute('json.text', [0x09])
                ->setContext('string');
            return true;
        }
        if (0x75 == $char) {
            $context->getBuffer()->nextSymbol();
            goto stateStringEsc10;
        }
        goto error;

        stateStringEsc10:
        if ($context->getBuffer()->isEnd()) {
            goto error;
        }
        $char = $context->getBuffer()->getSymbol();
        if (0x62 == $char) {
            $context->getBuffer()->nextSymbol();
            goto stateStringEsc11;
        }
        if (0x66 == $char) {
            $context->getBuffer()->nextSymbol();
            goto stateStringEsc11;
        }
        if (0x30 <= $char && $char <= 0x39 ||
            0x41 <= $char && $char <= 0x46 ||
            0x61 == $char ||
            0x63 <= $char && $char <= 0x65
        ) {
            $context->getBuffer()->nextSymbol();
            goto stateStringEsc11;
        }
        goto error;

        stateStringEsc11:
        if ($context->getBuffer()->isEnd()) {
            goto error;
        }
        $char = $context->getBuffer()->getSymbol();
        if (0x62 == $char) {
            $context->getBuffer()->nextSymbol();
            goto stateStringEsc12;
        }
        if (0x66 == $char) {
            $context->getBuffer()->nextSymbol();
            goto stateStringEsc12;
        }
        if (0x30 <= $char && $char <= 0x39 ||
            0x41 <= $char && $char <= 0x46 ||
            0x61 == $char ||
            0x63 <= $char && $char <= 0x65
        ) {
            $context->getBuffer()->nextSymbol();
            goto stateStringEsc12;
        }
        goto error;

        stateStringEsc12:
        if ($context->getBuffer()->isEnd()) {
            goto error;
        }
        $char = $context->getBuffer()->getSymbol();
        if (0x62 == $char) {
            $context->getBuffer()->nextSymbol();
            goto stateStringEsc13;
        }
        if (0x66 == $char) {
            $context->getBuffer()->nextSymbol();
            goto stateStringEsc13;
        }
        if (0x30 <= $char && $char <= 0x39 ||
            0x41 <= $char && $char <= 0x46 ||
            0x61 == $char ||
            0x63 <= $char && $char <= 0x65
        ) {
            $context->getBuffer()->nextSymbol();
            goto stateStringEsc13;
        }
        goto error;

        stateStringEsc13:
        if ($context->getBuffer()->isEnd()) {
            goto error;
        }
        $char = $context->getBuffer()->getSymbol();
        if (0x62 == $char) {
            $context->getBuffer()->nextSymbol();
            goto stateStringEsc14;
        }
        if (0x66 == $char) {
            $context->getBuffer()->nextSymbol();
            goto stateStringEsc14;
        }
        if (0x30 <= $char && $char <= 0x39 ||
            0x41 <= $char && $char <= 0x46 ||
            0x61 == $char ||
            0x63 <= $char && $char <= 0x65
        ) {
            $context->getBuffer()->nextSymbol();
            goto stateStringEsc14;
        }
        goto error;

        stateStringEsc14:
        // Extracting hexadecimal 16-bit symbol code
        $symbolList = $context->getSymbolList();
        $symbol = 0;
        for ($i = 4; $i > 0; $i--) {
            $power = 4 - $i;
            $digit = $symbolList[$i];
            if (0x30 <= $digit && $digit <= 0x39) {
                $digit -= 0x30;
            } elseif (0x61 <= $digit && $digit <= 0x66) {
                $digit -= 0x51;
            } else {
                $digit -= 0x31;
            }
            $symbol += $digit * pow(0x10, $power);
        }
        $isSurrogate = 0xD800 <= $symbol && $symbol <= 0xDFFF;
        $context
            ->setNewToken(TokenType::HEX)
            ->setTokenAttribute('json.text_char16', $symbol)
            ->setTokenAttribute('json.text_is_utf16_surrogate', $isSurrogate)
            ->setContext('string');
        return true;

        error:
        return false;
    }
}
