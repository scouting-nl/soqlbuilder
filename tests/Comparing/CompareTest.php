<?php
declare(strict_types=1);

namespace ScoutingNL\Tests\Salesforce\Soql\Comparing;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\TestWith;
use ScoutingNL\Salesforce\Soql\Column\Func\Date\DayInMonth;
use ScoutingNL\Salesforce\Soql\Condition\Comparing\Compare;
use ScoutingNL\Salesforce\Soql\Condition\Comparing\CompareOperator;
use ScoutingNL\Salesforce\Soql\Condition\Condition;
use ScoutingNL\Salesforce\Soql\Exception\InvalidArgumentException;
use ScoutingNL\Salesforce\Soql\Exception\RuntimeException;
use ScoutingNL\Salesforce\Soql\Value\DateTime\Date;
use ScoutingNL\Salesforce\Soql\Value\DateTime\DateLiteral;
use ScoutingNL\Salesforce\Soql\Value\DateTime\DateTime;
use ScoutingNL\Salesforce\Soql\Value\Value;
use ScoutingNL\Tests\Salesforce\Soql\Enum\TestEnum;
use ScoutingNL\Tests\Salesforce\Soql\Enum\TestIntEnum;
use ScoutingNL\Tests\Salesforce\Soql\Enum\TestStringEnum;
use ScoutingNL\Tests\Salesforce\Soql\TestCase;

#[CoversClass(Compare::class)]
#[CoversClass(CompareOperator::class)]
class CompareTest extends TestCase
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
    public function testEquals(string $expected, bool|int|string|Value|\Stringable|\UnitEnum|null $value): void
    {
        self::assertSameIgnoringWhitespace($expected, new Compare('c', CompareOperator::EQUALS, $value));
    }

    public function testEqualsWithStringable(): void
    {
        self::assertSameIgnoringWhitespace(
            "c = 'value'",
            new Compare(
                'c',
                CompareOperator::EQUALS,
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

    public function testEqualsWithDateLiteral(): void
    {
        self::assertSameIgnoringWhitespace(
            'c = TODAY',
            new Compare('c', CompareOperator::EQUALS, DateLiteral::today()),
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
    public function testNotEquals(string $expected, bool|int|string|Value|\Stringable|\UnitEnum|null $value): void
    {
        self::assertSameIgnoringWhitespace($expected, new Compare('c', CompareOperator::NOT_EQUALS, $value));
    }

    public function testNotEqualsWithStringable(): void
    {
        self::assertSameIgnoringWhitespace(
            "c != 'value'",
            new Compare(
                'c',
                CompareOperator::NOT_EQUALS,
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

    public function testNotEqualsWithDateLiteral(): void
    {
        self::assertSameIgnoringWhitespace(
            'c != TODAY',
            new Compare('c', CompareOperator::NOT_EQUALS, DateLiteral::today()),
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
    public function testGreater(string $expected, bool|int|string|Value|\Stringable|\UnitEnum|null $value): void
    {
        self::assertSameIgnoringWhitespace($expected, new Compare('c', CompareOperator::GREATER, $value));
    }

    public function testGreaterWithStringable(): void
    {
        self::assertSameIgnoringWhitespace(
            "c > 'value'",
            new Compare(
                'c',
                CompareOperator::GREATER,
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

    public function testGreaterWithDateLiteral(): void
    {
        self::assertSameIgnoringWhitespace(
            'c > TODAY',
            new Compare('c', CompareOperator::GREATER, DateLiteral::today()),
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
    public function testGreaterEquals(string $expected, bool|int|string|Value|\Stringable|\UnitEnum|null $value): void
    {
        self::assertSameIgnoringWhitespace($expected, new Compare('c', CompareOperator::GREATER_EQUALS, $value));
    }

    public function testGreaterEqualsWithStringable(): void
    {
        self::assertSameIgnoringWhitespace(
            "c >= 'value'",
            new Compare(
                'c',
                CompareOperator::GREATER_EQUALS,
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

    public function testGreaterEqualsWithDateLiteral(): void
    {
        self::assertSameIgnoringWhitespace(
            'c >= TODAY',
            new Compare('c', CompareOperator::GREATER_EQUALS, DateLiteral::today()),
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
    public function testLess(string $expected, bool|int|string|Value|\Stringable|\UnitEnum|null $value): void
    {
        self::assertSameIgnoringWhitespace($expected, new Compare('c', CompareOperator::LESS, $value));
    }

    public function testLessWithStringable(): void
    {
        self::assertSameIgnoringWhitespace(
            "c < 'value'",
            new Compare(
                'c',
                CompareOperator::LESS,
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

    public function testLessWithDateLiteral(): void
    {
        self::assertSameIgnoringWhitespace(
            'c < TODAY',
            new Compare('c', CompareOperator::LESS, DateLiteral::today()),
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
    public function testLessEqual(string $expected, bool|int|string|Value|\Stringable|\UnitEnum|null $value): void
    {
        self::assertSameIgnoringWhitespace($expected, new Compare('c', CompareOperator::LESS_EQUALS, $value));
    }

    public function testLessEqualWithStringable(): void
    {
        self::assertSameIgnoringWhitespace(
            "c <= 'value'",
            new Compare(
                'c',
                CompareOperator::LESS_EQUALS,
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

    public function testLessEqualsWithDateLiteral(): void
    {
        self::assertSameIgnoringWhitespace(
            'c <= TODAY',
            new Compare('c', CompareOperator::LESS_EQUALS, DateLiteral::today()),
        );
    }

    /**
     * @param non-empty-string|null $alias
     */
    #[TestWith(['=', CompareOperator::EQUALS])]
    #[TestWith(['=', CompareOperator::EQUALS, 'alias'])]
    #[TestWith(['!=', CompareOperator::NOT_EQUALS])]
    #[TestWith(['!=', CompareOperator::NOT_EQUALS, 'alias'])]
    #[TestWith(['>', CompareOperator::GREATER])]
    #[TestWith(['>', CompareOperator::GREATER, 'alias'])]
    #[TestWith(['>=', CompareOperator::GREATER_EQUALS])]
    #[TestWith(['>=', CompareOperator::GREATER_EQUALS, 'alias'])]
    #[TestWith(['<', CompareOperator::LESS])]
    #[TestWith(['<', CompareOperator::LESS, 'alias'])]
    #[TestWith(['<=', CompareOperator::LESS_EQUALS])]
    #[TestWith(['<=', CompareOperator::LESS_EQUALS, 'alias'])]
    public function testWithDateFunctionWithAlias(string $expectedOperator, CompareOperator $operator, ?string $alias = null): void
    {
        self::assertSameIgnoringWhitespace(
            "DAY_IN_MONTH(c) {$expectedOperator} 10",
            new Compare(new DayInMonth('c', $alias), $operator, 10),
        );
    }

    #[TestWith([CompareOperator::GREATER])]
    #[TestWith([CompareOperator::GREATER_EQUALS])]
    #[TestWith([CompareOperator::LESS])]
    #[TestWith([CompareOperator::LESS_EQUALS])]
    public function testComparingNullFails(CompareOperator $operator): void
    {
        self::expectException(InvalidArgumentException::class);
        self::expectExceptionMessage('NULL is only allowed for equal or not equal comparison');

        new Compare('c', $operator, null);
    }

    #[TestWith([CompareOperator::GREATER, true])]
    #[TestWith([CompareOperator::GREATER_EQUALS, true])]
    #[TestWith([CompareOperator::LESS, true])]
    #[TestWith([CompareOperator::LESS_EQUALS, true])]
    #[TestWith([CompareOperator::GREATER, false])]
    #[TestWith([CompareOperator::GREATER_EQUALS, false])]
    #[TestWith([CompareOperator::LESS, false])]
    #[TestWith([CompareOperator::LESS_EQUALS, false])]
    public function testComparingBooleanFails(CompareOperator $operator, bool $value): void
    {
        self::expectException(InvalidArgumentException::class);
        self::expectExceptionMessage('Booleans are only allowed for equal or not equal comparison');

        new Compare('c', $operator, $value);
    }

    public function testTooLongValueComparisonFails(): void
    {
        self::expectException(RuntimeException::class);
        self::expectExceptionMessage('Condition is too long');

        (new Compare('test', CompareOperator::EQUALS, \str_repeat('x', Condition::MAX_CONDITION_LENGTH + 1)))->__toString();
    }

    public function testTooLongIdentifierComparisonFails(): void
    {
        self::expectException(RuntimeException::class);
        self::expectExceptionMessage('Condition is too long');

        (new Compare(\str_repeat('x', Condition::MAX_CONDITION_LENGTH + 1), CompareOperator::EQUALS, 'test'))->__toString();
    }

    public function testCompareBetweenDateFunctionAndDateLiteralFails(): void
    {
        self::expectException(InvalidArgumentException::class);
        self::expectExceptionMessage('Cannot compare a date function with a date literal');
        (new Compare(new DayInMonth('c'), CompareOperator::EQUALS, DateLiteral::today()))->__toString();
    }
}
