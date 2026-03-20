<?php
declare(strict_types=1);

namespace ScoutingNL\Tests\Salesforce\Soql;

use PHPUnit\Framework\Attributes\CoversClass;
use ScoutingNL\Salesforce\Soql\Condition\Combining\_And;
use ScoutingNL\Salesforce\Soql\Condition\Combining\_Or;
use ScoutingNL\Salesforce\Soql\Condition\Combining\CombiningCondition;
use ScoutingNL\Salesforce\Soql\Condition\Combining\CombiningType;
use ScoutingNL\Salesforce\Soql\Condition\Comparing\Compare;
use ScoutingNL\Salesforce\Soql\Condition\Comparing\CompareOperator;

#[CoversClass(_And::class)]
#[CoversClass(_Or::class)]
#[CoversClass(CombiningCondition::class)]
#[CoversClass(CombiningType::class)]
class CombineTest extends TestCase
{
    public function testAndWithSingleCondition(): void
    {
        self::assertSameIgnoringWhitespace(
            "a = 'v1'",
            new _And(new Compare('a', CompareOperator::EQUALS, 'v1')),
        );
    }

    public function testAndWithTwoConditions(): void
    {
        self::assertSameIgnoringWhitespace(
            "a = 'v1' AND b = 'v2'",
            new _And(
                new Compare('a', CompareOperator::EQUALS, 'v1'),
                new Compare('b', CompareOperator::EQUALS, 'v2'),
            ),
        );
    }

    public function testAndWithMoreThanTwoConditions(): void
    {
        self::assertSameIgnoringWhitespace(
            "a = 'v1' AND b = 'v2' AND c = 'v3' AND d = 'v4' AND e = 'v5'",
            new _And(
                new Compare('a', CompareOperator::EQUALS, 'v1'),
                new Compare('b', CompareOperator::EQUALS, 'v2'),
                new Compare('c', CompareOperator::EQUALS, 'v3'),
                new Compare('d', CompareOperator::EQUALS, 'v4'),
                new Compare('e', CompareOperator::EQUALS, 'v5'),
            ),
        );
    }

    public function testOrWithSingleCondition(): void
    {
        self::assertSameIgnoringWhitespace(
            "a = 'v1'",
            new _Or(new Compare('a', CompareOperator::EQUALS, 'v1')),
        );
    }

    public function testOrWithTwoConditions(): void
    {
        self::assertSameIgnoringWhitespace(
            "a = 'v1' OR b = 'v2'",
            new _Or(
                new Compare('a', CompareOperator::EQUALS, 'v1'),
                new Compare('b', CompareOperator::EQUALS, 'v2'),
            ),
        );
    }

    public function testOrWithMoreThanTwoConditions(): void
    {
        self::assertSameIgnoringWhitespace(
            "a = 'v1' OR b = 'v2' OR c = 'v3' OR d = 'v4' OR e = 'v5'",
            new _Or(
                new Compare('a', CompareOperator::EQUALS, 'v1'),
                new Compare('b', CompareOperator::EQUALS, 'v2'),
                new Compare('c', CompareOperator::EQUALS, 'v3'),
                new Compare('d', CompareOperator::EQUALS, 'v4'),
                new Compare('e', CompareOperator::EQUALS, 'v5'),
            ),
        );
    }

    public function testAndAndOrConditionsCombined(): void
    {
        self::assertSameIgnoringWhitespace(
            "a = 'v1' AND (b = 'v2' OR c = 'v3')",
            new _And(
                new Compare('a', CompareOperator::EQUALS, 'v1'),
                new _Or(
                    new Compare('b', CompareOperator::EQUALS, 'v2'),
                    new Compare('c', CompareOperator::EQUALS, 'v3'),
                ),
            ),
        );
    }

    public function testOrAndConditionsCombined(): void
    {
        self::assertSameIgnoringWhitespace(
            "a = 'v1' OR (b = 'v2' AND c = 'v3')",
            new _Or(
                new Compare('a', CompareOperator::EQUALS, 'v1'),
                new _And(
                    new Compare('b', CompareOperator::EQUALS, 'v2'),
                    new Compare('c', CompareOperator::EQUALS, 'v3'),
                ),
            ),
        );
    }

    public function testAndAndOrConditionsCombinedWithCompareAfterOr(): void
    {
        self::assertSameIgnoringWhitespace(
            "(b = 'v2' OR c = 'v3') AND a = 'v1'",
            new _And(
                new _Or(
                    new Compare('b', CompareOperator::EQUALS, 'v2'),
                    new Compare('c', CompareOperator::EQUALS, 'v3'),
                ),
                new Compare('a', CompareOperator::EQUALS, 'v1'),
            ),
        );
    }

    public function testThreeLevelCombination(): void
    {
        self::assertSameIgnoringWhitespace(
            "a = 'v1' AND (b = 'v2' OR c = 'v3' OR (d = 'v4' AND e = 'v5'))",
            new _And(
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
        );
    }
}
