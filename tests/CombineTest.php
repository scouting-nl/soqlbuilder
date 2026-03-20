<?php
declare(strict_types=1);

namespace ScoutingNL\Tests\Salesforce\Soql;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use ScoutingNL\Salesforce\Soql\Condition\Combining\_And;
use ScoutingNL\Salesforce\Soql\Condition\Combining\_Or;
use ScoutingNL\Salesforce\Soql\Condition\Comparing\Compare;
use ScoutingNL\Salesforce\Soql\Condition\Comparing\CompareOperator;
use ScoutingNL\Salesforce\Soql\Condition\Condition;

#[CoversClass(_And::class)]
#[CoversClass(_Or::class)]
class CombineTest extends TestCase
{
    /**
     * @param callable(): Condition $condition
     */
    #[DataProvider('provideConditions')]
    public function testCombine(string $expected, callable $condition): void
    {
        self::assertSameIgnoringWhitespace($expected, (string)$condition());
    }

    /**
     * @return \Generator<array-key, array{string, callable(): Condition}>
     */
    public static function provideConditions(): \Generator
    {
        yield [
            "a = 'v1'",
            static fn () => new _And(new Compare('a', CompareOperator::EQUALS, 'v1')),
        ];

        yield [
            "a = 'v1' AND b = 'v2'",
            static fn () => new _And(
                new Compare('a', CompareOperator::EQUALS, 'v1'),
                new Compare('b', CompareOperator::EQUALS, 'v2'),
            ),
        ];

        yield [
            "a = 'v1' AND b = 'v2' AND c = 'v3' AND d = 'v4' AND e = 'v5'",
            static fn () => new _And(
                new Compare('a', CompareOperator::EQUALS, 'v1'),
                new Compare('b', CompareOperator::EQUALS, 'v2'),
                new Compare('c', CompareOperator::EQUALS, 'v3'),
                new Compare('d', CompareOperator::EQUALS, 'v4'),
                new Compare('e', CompareOperator::EQUALS, 'v5'),
            ),
        ];

        yield [
            "a = 'v1'",
            static fn () => new _Or(new Compare('a', CompareOperator::EQUALS, 'v1')),
        ];

        yield [
            "a = 'v1' OR b = 'v2'",
            static fn () => new _Or(
                new Compare('a', CompareOperator::EQUALS, 'v1'),
                new Compare('b', CompareOperator::EQUALS, 'v2'),
            ),
        ];

        yield [
            "a = 'v1' OR b = 'v2' OR c = 'v3' OR d = 'v4' OR e = 'v5'",
            static fn () => new _Or(
                new Compare('a', CompareOperator::EQUALS, 'v1'),
                new Compare('b', CompareOperator::EQUALS, 'v2'),
                new Compare('c', CompareOperator::EQUALS, 'v3'),
                new Compare('d', CompareOperator::EQUALS, 'v4'),
                new Compare('e', CompareOperator::EQUALS, 'v5'),
            ),
        ];

        yield [
            "a = 'v1' AND (b = 'v2' OR c = 'v3')",
            static fn () => new _And(
                new Compare('a', CompareOperator::EQUALS, 'v1'),
                new _Or(
                    new Compare('b', CompareOperator::EQUALS, 'v2'),
                    new Compare('c', CompareOperator::EQUALS, 'v3'),
                ),
            ),
        ];

        yield [
            "a = 'v1' OR (b = 'v2' AND c = 'v3')",
            static fn () => new _Or(
                new Compare('a', CompareOperator::EQUALS, 'v1'),
                new _And(
                    new Compare('b', CompareOperator::EQUALS, 'v2'),
                    new Compare('c', CompareOperator::EQUALS, 'v3'),
                ),
            ),
        ];

        yield [
            "(b = 'v2' OR c = 'v3') AND a = 'v1'",
            static fn () => new _And(
                new _Or(
                    new Compare('b', CompareOperator::EQUALS, 'v2'),
                    new Compare('c', CompareOperator::EQUALS, 'v3'),
                ),
                new Compare('a', CompareOperator::EQUALS, 'v1'),
            ),
        ];

        yield [
            "a = 'v1' AND (b = 'v2' OR c = 'v3' OR (d = 'v4' AND e = 'v5'))",
            static fn () => new _And(
                new Compare('a', CompareOperator::EQUALS, 'v1'),
                new _Or(
                    new Compare('b', CompareOperator::EQUALS, 'v2'),
                    new Compare('c', CompareOperator::EQUALS, 'v3'),
                    new _And(
                        new Compare('d', CompareOperator::EQUALS, 'v4'),
                        new Compare('e', CompareOperator::EQUALS, 'v5'),
                    ),
                ),
            ),
        ];
    }
}
