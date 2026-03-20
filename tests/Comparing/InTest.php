<?php
declare(strict_types=1);

namespace ScoutingNL\Tests\Salesforce\Soql\Comparing;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\TestWith;
use ScoutingNL\Salesforce\Soql\Column\Date;
use ScoutingNL\Salesforce\Soql\Column\DateTime;
use ScoutingNL\Salesforce\Soql\Condition\Comparing\In;
use ScoutingNL\Salesforce\Soql\SoqlBuilder;
use ScoutingNL\Tests\Salesforce\Soql\TestCase;

#[CoversClass(In::class)]
class InTest extends TestCase
{
    #[TestWith(["a IN ('v1')", new In('a', ['v1'])])]
    #[TestWith(["a NOT IN ('v1')", new In('a', ['v1'], negate: true)])]
    #[TestWith([
        "a IN ('v1', 'v2', 10, NULL, FALSE, TRUE, 2024-11-24T19:23:54+02:00, 2024-11-24)",
        new In('a', ['v1', 'v2', 10, null, false, true, new DateTime(new \DateTimeImmutable('2024-11-24T19:23:54+0200')), new Date(new \DateTimeImmutable('2024-11-24T19:23:54+0200'))]),
    ])]
    #[TestWith([
        "a NOT IN ('v1', 'v2', 10, NULL, FALSE, TRUE, 2024-11-24T19:23:54+02:00, 2024-11-24)",
        new In('a', ['v1', 'v2', 10, null, false, true, new DateTime(new \DateTimeImmutable('2024-11-24T19:23:54+0200')), new Date(new \DateTimeImmutable('2024-11-24T19:23:54+0200'))], negate: true),
    ])]
    public function testIn(string $expected, In $in): void
    {
        self::assertSameIgnoringWhitespace($expected, (string)$in);
    }

    public function testInWithSoqlBuilder(): void
    {
        self::assertSameIgnoringWhitespace(
            'a IN (SELECT b, c FROM Object)',
            new In('a', SoqlBuilder::select('Object')->columns('b', 'c')),
        );
        self::assertSameIgnoringWhitespace(
            'a NOT IN (SELECT b, c FROM Object)',
            new In('a', SoqlBuilder::select('Object')->columns('b', 'c'), negate: true),
        );
    }

    public function testFailWhenNoValuesInArray(): void
    {
        self::expectException(\RuntimeException::class);
        self::expectExceptionMessage('In must have at least one value');
        new In('a', []);
    }
}
