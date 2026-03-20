<?php
declare(strict_types=1);

namespace ScoutingNL\Tests\Salesforce\Soql;

use PHPUnit\Framework\TestCase as PhpunitTestCase;

class TestCase extends PhpunitTestCase
{
    private const string NOT_IN_QUOTES = '(?=(?:(\\\[\\\\\']|[^\\\\\']*)\'(\\\[\\\\\']|[^\\\\\'])*\')*(\\\[\\\\\']|[^\\\\\']*)$)';

    public static function stripWhitespace(string|\Stringable $string): string
    {
        return \mb_trim(
            \preg_replace(
                ['/(\s|[\n\r])+/', '/\(\s+' . self::NOT_IN_QUOTES . '/', '/\s+\)' . self::NOT_IN_QUOTES . '/'],
                [' ', '(', ')'],
                (string)$string,
            )
            ?? throw new \LogicException('preg_replace failed: ' . \preg_last_error_msg()),
        );
    }

    public static function assertSameIgnoringWhitespace(
        string|\Stringable $expected,
        string|\Stringable $actual,
        string $message = '',
    ): void {
        self::assertSame(self::stripWhitespace($expected), self::stripWhitespace($actual), $message);
    }
}
