<?php
declare(strict_types=1);

namespace ScoutingNL\Tests\Salesforce\Soql\Comparing;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use ScoutingNL\Salesforce\Soql\Column\Date;
use ScoutingNL\Salesforce\Soql\Column\DateTime;
use ScoutingNL\Salesforce\Soql\Condition\Comparing\In;
use ScoutingNL\Salesforce\Soql\SoqlBuilder;
use ScoutingNL\Tests\Salesforce\Soql\TestCase;

#[CoversClass(In::class)]
class InTest extends TestCase
{
    /**
     * @param callable(): In $in
     */
    #[DataProvider('provideIn')]
    public function testIn(string $expected, callable $in): void
    {
        self::assertSameIgnoringWhitespace($expected, (string)$in());
    }

    /**
     * @return \Generator<array-key, array{string, callable(): In}>
     */
    public static function provideIn(): \Generator
    {
        yield [
            "a IN ('v1')",
            static fn () => new In('a', ['v1']),
        ];

        yield [
            "a NOT IN ('v1')",
            static fn () => new In('a', ['v1'], negate: true),
        ];

        $dateTime = new \DateTimeImmutable('2024-11-24T19:23:54+0200');

        yield [
            "a IN ('v1', 'v2', 10, NULL, FALSE, TRUE, 2024-11-24T19:23:54+02:00, 2024-11-24)",
            static fn () => new In('a', ['v1', 'v2', 10, null, false, true, new DateTime($dateTime), new Date($dateTime)]),
        ];

        yield [
            "a NOT IN ('v1', 'v2', 10, NULL, FALSE, TRUE, 2024-11-24T19:23:54+02:00, 2024-11-24)",
            static fn () => new In('a', ['v1', 'v2', 10, null, false, true, new DateTime($dateTime), new Date($dateTime)], negate: true),
        ];

        yield [
            'a IN (SELECT b, c FROM Object)',
            static fn () => new In('a', SoqlBuilder::select('Object')->columns('b', 'c')),
        ];

        yield [
            'a NOT IN (SELECT b, c FROM Object)',
            static fn () => new In('a', SoqlBuilder::select('Object')->columns('b', 'c'), negate: true),
        ];
    }

    public function testFailWhenNoValuesInArray(): void
    {
        self::expectException(\RuntimeException::class);
        self::expectExceptionMessage('In must have at least one value');
        new In('a', []);
    }
}
