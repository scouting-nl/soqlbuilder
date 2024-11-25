<?php
declare(strict_types=1);

namespace App\Tests\Salesforce\Soql\Comparing;

use App\Salesforce\Soql\Column\Date;
use App\Salesforce\Soql\Column\DateTime;
use App\Salesforce\Soql\Condition\Comparing\In;
use App\Salesforce\Soql\SoqlBuilder;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class InTest extends TestCase
{
    #[DataProvider('provideIn')]
    public function testIn(string $expected, In $in): void
    {
        self::assertEquals(
            \preg_replace('/(\s|[\n\r])+/', ' ', \trim($expected)),
            \preg_replace('/(\s|[\n\r])+/', ' ', (string)$in),
        );
    }

    /**
     * @return \Generator<array-key, array{string, In}>
     */
    public static function provideIn(): \Generator
    {
        yield [
            "a IN ('v1')",
            new In('a', ['v1']),
        ];

        yield [
            "a NOT IN ('v1')",
            new In('a', ['v1'], negate: true),
        ];

        $dateTime = new \DateTimeImmutable('2024-11-24T19:23:54+0200');

        yield [
            "a IN ('v1', 'v2', 10, NULL, FALSE, TRUE, 2024-11-24T19:23:54+02:00, 2024-11-24)",
            new In('a', ['v1', 'v2', 10, null, false, true, new DateTime($dateTime), new Date($dateTime)]),
        ];

        yield [
            "a NOT IN ('v1', 'v2', 10, NULL, FALSE, TRUE, 2024-11-24T19:23:54+02:00, 2024-11-24)",
            new In('a', ['v1', 'v2', 10, null, false, true, new DateTime($dateTime), new Date($dateTime)], negate: true),
        ];

        yield [
            'a IN (SELECT b, c FROM Object)',
            new In('a', SoqlBuilder::select('Object')->columns('b', 'c')),
        ];

        yield [
            'a NOT IN (SELECT b, c FROM Object)',
            new In('a', SoqlBuilder::select('Object')->columns('b', 'c'), negate: true),
        ];
    }

    public function testFailWhenNoValuesInArray(): void
    {
        self::expectException(\RuntimeException::class);
        self::expectExceptionMessage('In must have at least one value');
        new In('a', []);
    }
}
