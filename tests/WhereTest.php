<?php
declare(strict_types=1);

namespace ScoutingNL\Tests\Salesforce\Soql;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\TestWith;
use PHPUnit\Framework\Attributes\UsesClass;
use ScoutingNL\Salesforce\Soql\Column\Column;
use ScoutingNL\Salesforce\Soql\Column\Date;
use ScoutingNL\Salesforce\Soql\Column\DateTime;
use ScoutingNL\Salesforce\Soql\Condition\Combining\_And;
use ScoutingNL\Salesforce\Soql\Condition\Combining\_Or;
use ScoutingNL\Salesforce\Soql\Condition\Comparing\Compare;
use ScoutingNL\Salesforce\Soql\Condition\Comparing\CompareOperator;
use ScoutingNL\Salesforce\Soql\Condition\Comparing\In;
use ScoutingNL\Salesforce\Soql\Condition\Comparing\Like;
use ScoutingNL\Salesforce\Soql\Condition\Condition;
use ScoutingNL\Salesforce\Soql\Where;
use ScoutingNL\Tests\Salesforce\Soql\Enum\TestEnum;
use ScoutingNL\Tests\Salesforce\Soql\Enum\TestIntEnum;
use ScoutingNL\Tests\Salesforce\Soql\Enum\TestStringEnum;

#[CoversClass(Where::class)]
#[UsesClass(Compare::class)]
#[UsesClass(CompareOperator::class)]
#[UsesClass(In::class)]
#[UsesClass(Like::class)]
#[UsesClass(_And::class)]
#[UsesClass(_Or::class)]
class WhereTest extends TestCase
{
    #[TestWith(['c = NULL', null])]
    #[TestWith(['c = TRUE', true])]
    #[TestWith(['c = FALSE', false])]
    #[TestWith(["c = ''", ''])]
    #[TestWith(["c = 'value'", 'value'])]
    #[TestWith(['c = 10', 10])]
    #[TestWith(['c = -10', -10])]
    #[TestWith(["c = 'T1'", TestEnum::T1])]
    #[TestWith(['c = 2', TestIntEnum::I2])]
    #[TestWith(["c = 'str1'", TestStringEnum::STR1])]
    #[TestWith(['c = 2024-11-24T19:23:54+02:00', new DateTime(new \DateTimeImmutable('2024-11-24T19:23:54+0200'))])]
    #[TestWith(['c = 2024-11-24', new Date(new \DateTimeImmutable('2024-11-24T19:23:54+0200'))])]
    public function testEquals(string $expected, bool|int|string|Column|\Stringable|\UnitEnum|null $value): void
    {
        self::assertSameIgnoringWhitespace($expected, Where::equals('c', $value));
    }

    public function testEqualsWithStringable(): void
    {
        self::assertSameIgnoringWhitespace(
            "c = 'value'",
            Where::equals(
                'c',
                new class implements \Stringable {
                    #[\Override]
                    public function __toString(): string
                    {
                        return 'value';
                    }
                },
            ),
        );
    }

    #[TestWith(['c != NULL', null])]
    #[TestWith(['c != TRUE', true])]
    #[TestWith(['c != FALSE', false])]
    #[TestWith(["c != ''", ''])]
    #[TestWith(["c != 'value'", 'value'])]
    #[TestWith(['c != 10', 10])]
    #[TestWith(['c != -10', -10])]
    #[TestWith(["c != 'T1'", TestEnum::T1])]
    #[TestWith(['c != 2', TestIntEnum::I2])]
    #[TestWith(["c != 'str1'", TestStringEnum::STR1])]
    #[TestWith(['c != 2024-11-24T19:23:54+02:00', new DateTime(new \DateTimeImmutable('2024-11-24T19:23:54+0200'))])]
    #[TestWith(['c != 2024-11-24', new Date(new \DateTimeImmutable('2024-11-24T19:23:54+0200'))])]
    public function testNotEquals(string $expected, bool|int|string|Column|\Stringable|\UnitEnum|null $value): void
    {
        self::assertSameIgnoringWhitespace($expected, Where::notEquals('c', $value));
    }

    public function testNotEqualsWithStringable(): void
    {
        self::assertSameIgnoringWhitespace(
            "c != 'value'",
            Where::notEquals(
                'c',
                new class implements \Stringable {
                    #[\Override]
                    public function __toString(): string
                    {
                        return 'value';
                    }
                },
            ),
        );
    }

    #[TestWith(["c > ''", ''])]
    #[TestWith(["c > 'value'", 'value'])]
    #[TestWith(['c > 10', 10])]
    #[TestWith(['c > -10', -10])]
    #[TestWith(["c > 'T1'", TestEnum::T1])]
    #[TestWith(['c > 2', TestIntEnum::I2])]
    #[TestWith(["c > 'str1'", TestStringEnum::STR1])]
    #[TestWith(['c > 2024-11-24T19:23:54+02:00', new DateTime(new \DateTimeImmutable('2024-11-24T19:23:54+0200'))])]
    #[TestWith(['c > 2024-11-24', new Date(new \DateTimeImmutable('2024-11-24T19:23:54+0200'))])]
    public function testGreater(string $expected, int|string|Column|\Stringable|\UnitEnum $value): void
    {
        self::assertSameIgnoringWhitespace($expected, Where::greater('c', $value));
    }

    public function testGreaterWithStringable(): void
    {
        self::assertSameIgnoringWhitespace(
            "c > 'value'",
            Where::greater(
                'c',
                new class implements \Stringable {
                    #[\Override]
                    public function __toString(): string
                    {
                        return 'value';
                    }
                },
            ),
        );
    }

    #[TestWith(["c >= ''", ''])]
    #[TestWith(["c >= 'value'", 'value'])]
    #[TestWith(['c >= 10', 10])]
    #[TestWith(['c >= -10', -10])]
    #[TestWith(["c >= 'T1'", TestEnum::T1])]
    #[TestWith(['c >= 2', TestIntEnum::I2])]
    #[TestWith(["c >= 'str1'", TestStringEnum::STR1])]
    #[TestWith(['c >= 2024-11-24T19:23:54+02:00', new DateTime(new \DateTimeImmutable('2024-11-24T19:23:54+0200'))])]
    #[TestWith(['c >= 2024-11-24', new Date(new \DateTimeImmutable('2024-11-24T19:23:54+0200'))])]
    public function testGreaterEqual(string $expected, int|string|Column|\Stringable|\UnitEnum $value): void
    {
        self::assertSameIgnoringWhitespace($expected, Where::greaterEqual('c', $value));
    }

    public function testGreaterEqualWithStringable(): void
    {
        self::assertSameIgnoringWhitespace(
            "c >= 'value'",
            Where::greaterEqual(
                'c',
                new class implements \Stringable {
                    #[\Override]
                    public function __toString(): string
                    {
                        return 'value';
                    }
                },
            ),
        );
    }

    #[TestWith(["c < ''", ''])]
    #[TestWith(["c < 'value'", 'value'])]
    #[TestWith(['c < 10', 10])]
    #[TestWith(['c < -10', -10])]
    #[TestWith(["c < 'T1'", TestEnum::T1])]
    #[TestWith(['c < 2', TestIntEnum::I2])]
    #[TestWith(["c < 'str1'", TestStringEnum::STR1])]
    #[TestWith(['c < 2024-11-24T19:23:54+02:00', new DateTime(new \DateTimeImmutable('2024-11-24T19:23:54+0200'))])]
    #[TestWith(['c < 2024-11-24', new Date(new \DateTimeImmutable('2024-11-24T19:23:54+0200'))])]
    public function testLess(string $expected, int|string|Column|\Stringable|\UnitEnum $value): void
    {
        self::assertSameIgnoringWhitespace($expected, Where::less('c', $value));
    }

    public function testLessWithStringable(): void
    {
        self::assertSameIgnoringWhitespace(
            "c < 'value'",
            Where::less(
                'c',
                new class implements \Stringable {
                    #[\Override]
                    public function __toString(): string
                    {
                        return 'value';
                    }
                },
            ),
        );
    }

    #[TestWith(["c <= ''", ''])]
    #[TestWith(["c <= 'value'", 'value'])]
    #[TestWith(['c <= 10', 10])]
    #[TestWith(['c <= -10', -10])]
    #[TestWith(["c <= 'T1'", TestEnum::T1])]
    #[TestWith(['c <= 2', TestIntEnum::I2])]
    #[TestWith(["c <= 'str1'", TestStringEnum::STR1])]
    #[TestWith(['c <= 2024-11-24T19:23:54+02:00', new DateTime(new \DateTimeImmutable('2024-11-24T19:23:54+0200'))])]
    #[TestWith(['c <= 2024-11-24', new Date(new \DateTimeImmutable('2024-11-24T19:23:54+0200'))])]
    public function testLessEqual(string $expected, int|string|Column|\Stringable|\UnitEnum $value): void
    {
        self::assertSameIgnoringWhitespace($expected, Where::lessEquals('c', $value));
    }

    public function testLessEqualWithStringable(): void
    {
        self::assertSameIgnoringWhitespace(
            "c <= 'value'",
            Where::lessEquals(
                'c',
                new class implements \Stringable {
                    #[\Override]
                    public function __toString(): string
                    {
                        return 'value';
                    }
                },
            ),
        );
    }

    public function testLike(): void
    {
        self::assertSameIgnoringWhitespace("a LIKE '%v1%'", Where::like('a', '%v1%'));
    }

    public function testNotLike(): void
    {
        self::assertSameIgnoringWhitespace("(NOT a LIKE '%v1%')", Where::notLike('a', '%v1%'));
    }

    public function testIn(): void
    {
        self::assertSameIgnoringWhitespace("a IN ('v1')", Where::in('a', ['v1']));
    }

    public function testNotIn(): void
    {
        self::assertSameIgnoringWhitespace("a NOT IN ('v1')", Where::notIn('a', ['v1']));
    }

    public function testAnd(): void
    {
        self::assertSameIgnoringWhitespace(
            "a = 'v1' AND b = 'v2'",
            Where::and(Where::equals('a', 'v1'), Where::equals('b', 'v2')),
        );
    }

    public function testOr(): void
    {
        self::assertSameIgnoringWhitespace(
            "a = 'v1' OR b = 'v2'",
            Where::or(Where::equals('a', 'v1'), Where::equals('b', 'v2')),
        );
    }

    public function testTooLongValueComparisonFails(): void
    {
        self::expectException(\RuntimeException::class);
        self::expectExceptionMessage('Condition is too long');

        Where::equals('test', \str_repeat('x', Condition::MAX_CONDITION_LENGTH + 1))->__toString();
    }

    public function testTooLongIdentifierComparisonFails(): void
    {
        self::expectException(\RuntimeException::class);
        self::expectExceptionMessage('Condition is too long');

        Where::equals(\str_repeat('x', Condition::MAX_CONDITION_LENGTH + 1), 'test')->__toString();
    }
}
