<?php
declare(strict_types=1);

namespace ScoutingNL\Tests\Salesforce\Soql\Comparing;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use ScoutingNL\Salesforce\Soql\Column\Date;
use ScoutingNL\Salesforce\Soql\Column\DateTime;
use ScoutingNL\Salesforce\Soql\Condition\Comparing\Compare;
use ScoutingNL\Salesforce\Soql\Condition\Comparing\CompareOperator;
use ScoutingNL\Salesforce\Soql\Condition\Condition;
use ScoutingNL\Salesforce\Soql\Where;
use ScoutingNL\Tests\Salesforce\Soql\Enum\TestEnum;
use ScoutingNL\Tests\Salesforce\Soql\Enum\TestIntEnum;
use ScoutingNL\Tests\Salesforce\Soql\Enum\TestStringEnum;

class CompareTest extends TestCase
{
    #[DataProvider('provideConditions')]
    public function testCompare(string $expected, Condition $condition): void
    {
        self::assertEquals($expected, (string)$condition);
    }

    /**
     * @return \Generator<array-key, array{string, Condition}>
     */
    public static function provideConditions(): \Generator
    {
        yield [
            'c = NULL',
            new Compare('c', CompareOperator::EQUALS, null),
        ];

        yield [
            'c != NULL',
            new Compare('c', CompareOperator::NOT_EQUALS, null),
        ];

        yield [
            'c = TRUE',
            new Compare('c', CompareOperator::EQUALS, true),
        ];

        yield [
            'c != TRUE',
            new Compare('c', CompareOperator::NOT_EQUALS, true),
        ];

        yield [
            'c = FALSE',
            new Compare('c', CompareOperator::EQUALS, false),
        ];

        yield [
            'c != FALSE',
            new Compare('c', CompareOperator::NOT_EQUALS, false),
        ];

        foreach (CompareOperator::cases() as $operator) {
            yield [
                "c {$operator->value} ''",
                new Compare('c', $operator, ''),
            ];

            yield [
                "c {$operator->value} 'value'",
                new Compare('c', $operator, 'value'),
            ];

            yield [
                "c {$operator->value} 'value'",
                new Compare('c', $operator, new class implements \Stringable {
                    public function __toString(): string
                    {
                        return 'value';
                    }
                }),
            ];

            yield [
                "c {$operator->value} 10",
                new Compare('c', $operator, 10),
            ];

            yield [
                "c {$operator->value} -10",
                new Compare('c', $operator, -10),
            ];

            yield [
                "c {$operator->value} 'T1'",
                new Compare('c', $operator, TestEnum::T1),
            ];

            yield [
                "c {$operator->value} 2",
                new Compare('c', $operator, TestIntEnum::I2),
            ];

            yield [
                "c {$operator->value} 'str1'",
                new Compare('c', $operator, TestStringEnum::STR1),
            ];

            yield [
                "c {$operator->value} 2024-11-24T19:23:54+02:00",
                new Compare('c', $operator, new DateTime(new \DateTimeImmutable('2024-11-24T19:23:54+0200'))),
            ];

            yield [
                "c {$operator->value} 2024-11-24",
                new Compare('c', $operator, new Date(new \DateTimeImmutable('2024-11-24T19:23:54+0200'))),
            ];
        }
    }

    #[DataProvider('provideOperatorsThatDontAllowNull')]
    public function testComparingNullFails(CompareOperator $operator): void
    {
        self::expectException(\RuntimeException::class);
        self::expectExceptionMessage('NULL is only allowed for equal or not equal comparison');

        new Compare('c', $operator, null);
    }

    /**
     * @return \Generator<array-key, array{CompareOperator}>
     */
    public static function provideOperatorsThatDontAllowNull(): \Generator
    {
        foreach (CompareOperator::cases() as $operator) {
            if (!\in_array($operator, [CompareOperator::EQUALS, CompareOperator::NOT_EQUALS], true)) {
                yield [$operator];
            }
        }
    }

    #[DataProvider('provideOperatorsThatDontAllowBoolean')]
    public function testComparingBooleanFails(CompareOperator $operator, bool $value): void
    {
        self::expectException(\RuntimeException::class);
        self::expectExceptionMessage('Booleans are only allowed for equal or not equal comparison');

        new Compare('c', $operator, $value);
    }

    /**
     * @return \Generator<array-key, array{CompareOperator, bool}>
     */
    public static function provideOperatorsThatDontAllowBoolean(): \Generator
    {
        foreach (CompareOperator::cases() as $operator) {
            if (!\in_array($operator, [CompareOperator::EQUALS, CompareOperator::NOT_EQUALS], true)) {
                yield [$operator, true];
                yield [$operator, false];
            }
        }
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
