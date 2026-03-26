<?php
declare(strict_types=1);

namespace ScoutingNL\Tests\Salesforce\Soql;

use PHPUnit\Framework\Attributes\CoversClass;
use ScoutingNL\Salesforce\Soql\Column\Fields;
use ScoutingNL\Salesforce\Soql\Column\Func\Aggregate\Avg;
use ScoutingNL\Salesforce\Soql\Exception\InvalidArgumentException;
use ScoutingNL\Salesforce\Soql\Exception\RuntimeException;
use ScoutingNL\Salesforce\Soql\SoqlBuilder;
use ScoutingNL\Salesforce\Soql\Where;

#[CoversClass(SoqlBuilder::class)]
class SoqlBuilderTest extends TestCase
{
    public function testSelectWithoutColumns(): void
    {
        self::expectException(RuntimeException::class);
        self::expectExceptionMessage('Must select at least one column');
        SoqlBuilder::select('Object')->__toString();
    }

    public function testColumnWithOneColumn(): void
    {
        self::assertSameIgnoringWhitespace(
            'SELECT Id FROM Object',
            SoqlBuilder::select('Object')->columns('Id'),
        );
    }

    public function testColumnWithTwoColumns(): void
    {
        self::assertSameIgnoringWhitespace(
            'SELECT Id, Name FROM Object',
            SoqlBuilder::select('Object')->columns('Id', 'Name'),
        );
    }

    public function testColumnWithMoreThanTwoColumns(): void
    {
        self::assertSameIgnoringWhitespace(
            'SELECT Id, Name, c1, c2, c3, c4, c5, c6, c7, c8 FROM Object',
            SoqlBuilder::select('Object')
                ->columns('Id', 'Name', 'c1', 'c2', 'c3', 'c4', 'c5', 'c6', 'c7', 'c8'),
        );
    }

    public function testAddColumnWithOneColumn(): void
    {
        self::assertSameIgnoringWhitespace(
            'SELECT Id FROM Object',
            SoqlBuilder::select('Object')->columns('Id'),
        );
    }

    public function testAddColumnWithTwoColumns(): void
    {
        self::assertSameIgnoringWhitespace(
            'SELECT Id, Name FROM Object',
            SoqlBuilder::select('Object')->columns('Id', 'Name'),
        );
    }

    public function testAddColumnWithMoreThanTwoColumns(): void
    {
        self::assertSameIgnoringWhitespace(
            'SELECT Id, Name, c1, c2, c3, c4, c5, c6, c7, c8 FROM Object',
            SoqlBuilder::select('Object')
                ->columns('Id', 'Name', 'c1', 'c2', 'c3', 'c4', 'c5', 'c6', 'c7', 'c8'),
        );
    }

    public function testAddColumnsAfterColumns(): void
    {
        self::assertSameIgnoringWhitespace(
            'SELECT Id, Name, More, Columns FROM Object',
            SoqlBuilder::select('Object')
                ->columns('Id', 'Name')
                ->addColumns('More', 'Columns'),
        );
    }

    public function testThatColumnsResetsPreviousColumns(): void
    {
        self::assertSameIgnoringWhitespace(
            'SELECT Id, Name FROM Object',
            SoqlBuilder::select('Object')
                ->columns('More', 'Columns')
                ->columns('Id', 'Name'),
        );
    }

    public function testThatColumnsResetsPreviousAddColumns(): void
    {
        self::assertSameIgnoringWhitespace(
            'SELECT Id, Name FROM Object',
            SoqlBuilder::select('Object')
                ->addColumns('More', 'Columns')
                ->columns('Id', 'Name'),
        );
    }

    public function testColumnsWithSoqlBuilder(): void
    {
        self::assertSameIgnoringWhitespace(
            'SELECT Id, (SELECT Name FROM Other_Object) FROM Object',
            SoqlBuilder::select('Object')
                ->columns(
                    'Id',
                    SoqlBuilder::select('Other_Object')->columns('Name'),
                ),
        );
    }

    public function testAddColumnsAfterColumnsWithSoqlBuilder(): void
    {
        self::assertSameIgnoringWhitespace(
            'SELECT Id, (SELECT Name FROM Other_Object), More, Columns FROM Object',
            SoqlBuilder::select('Object')
                ->columns(
                    'Id',
                    SoqlBuilder::select('Other_Object')->columns('Name'),
                )
                ->addColumns('More', 'Columns'),
        );
    }

    public function testAddColumnsWithSoqlBuilder(): void
    {
        self::assertSameIgnoringWhitespace(
            'SELECT Id, (SELECT Name FROM Other_Object), (SELECT More FROM More_Object), Columns FROM Object',
            SoqlBuilder::select('Object')
                ->columns(
                    'Id',
                    SoqlBuilder::select('Other_Object')->columns('Name'),
                )
                ->addColumns(SoqlBuilder::select('More_Object')->columns('More'), 'Columns'),
        );
    }

    public function testColumnsWithOnlyFields(): void
    {
        self::assertSameIgnoringWhitespace(
            'SELECT FIELDS(ALL) FROM Object LIMIT 10',
            SoqlBuilder::select('Object')->columns(Fields::ALL)->limit(10),
        );
    }

    public function testColumnsWithFields(): void
    {
        self::assertSameIgnoringWhitespace(
            'SELECT Other__r.Id, FIELDS(ALL) FROM Object LIMIT 10',
            SoqlBuilder::select('Object')->columns('Other__r.Id', Fields::ALL)->limit(10),
        );
    }

    public function testAddColumnsAfterColumnsWithFields(): void
    {
        self::assertSameIgnoringWhitespace(
            'SELECT FIELDS(ALL), Other__r.Id FROM Object LIMIT 10',
            SoqlBuilder::select('Object')
                ->columns(Fields::ALL)
                ->addColumns('Other__r.Id')
                ->limit(10),
        );
    }

    public function testAddColumnsWithFields(): void
    {
        self::assertSameIgnoringWhitespace(
            'SELECT Other__r.Id, FIELDS(ALL) FROM Object LIMIT 10',
            SoqlBuilder::select('Object')
                ->columns('Other__r.Id')
                ->addColumns(Fields::ALL)
                ->limit(10),
        );
    }

    public function testThatLimitIsRequiredWhenUsingFieldsColumn(): void
    {
        self::expectException(RuntimeException::class);
        self::expectExceptionMessage('LIMIT is required when using the FIELDS() function');
        SoqlBuilder::select('Object')->columns(Fields::ALL)->__toString();
    }

    public function testThatLimitNotLargerThanMaxWhenUsingFieldsColumn(): void
    {
        self::expectException(RuntimeException::class);
        self::expectExceptionMessage('LIMIT must be less than ' . SoqlBuilder::MAX_LIMIT_FOR_FIELDS . ' when using the FIELDS() function');
        SoqlBuilder::select('Object')
            ->columns(Fields::ALL)
            ->limit(SoqlBuilder::MAX_LIMIT_FOR_FIELDS + 1)
            ->__toString();
    }

    public function testColumnsWithOnlyAggregateFunction(): void
    {
        self::assertSameIgnoringWhitespace(
            'SELECT AVG(Field) alias FROM Object',
            SoqlBuilder::select('Object')->columns(new Avg('Field', 'alias')),
        );
    }

    public function testColumnsWithOnlyAggregateFunctionAndGroupBy(): void
    {
        self::assertSameIgnoringWhitespace(
            'SELECT AVG(Field) alias FROM Object GROUP BY Name',
            SoqlBuilder::select('Object')
                ->columns(new Avg('Field', 'alias'))
                ->groupBy('Name'),
        );
    }

    public function testColumnsWithAggregateFunction(): void
    {
        self::assertSameIgnoringWhitespace(
            'SELECT Other__c, AVG(Field) alias FROM Object',
            SoqlBuilder::select('Object')->columns('Other__c', new Avg('Field', 'alias')),
        );
    }

    public function testAddColumnsAfterColumnsWithAggregateFunction(): void
    {
        self::assertSameIgnoringWhitespace(
            'SELECT AVG(Field) alias, Other__c FROM Object',
            SoqlBuilder::select('Object')
                ->columns(new Avg('Field', 'alias'))
                ->addColumns('Other__c'),
        );
    }

    public function testAddColumnsWithAggregateFunction(): void
    {
        self::assertSameIgnoringWhitespace(
            'SELECT Other__c, AVG(Field) alias FROM Object',
            SoqlBuilder::select('Object')
                ->columns('Other__c')
                ->addColumns(new Avg('Field', 'alias')),
        );
    }

    public function testThatAggregateFunctionNotValidWithLimitWithoutGroupBy(): void
    {
        self::expectException(RuntimeException::class);
        self::expectExceptionMessage('LIMIT is not allowed when using aggregate functions without a GROUP BY');
        SoqlBuilder::select('Object')
            ->columns(new Avg('Field', 'alias'))
            ->limit(10)
            ->__toString();
    }

    public function testWhereWithOneCondition(): void
    {
        self::assertSameIgnoringWhitespace(
            "SELECT Id FROM Object WHERE a = 'v1'",
            SoqlBuilder::select('Object')->columns('Id')->where(Where::equals('a', 'v1')),
        );
    }

    public function testWhereWithTwoConditions(): void
    {
        self::assertSameIgnoringWhitespace(
            "SELECT Id FROM Object WHERE a = 'v1' AND b = 'v2'",
            SoqlBuilder::select('Object')->columns('Id')->where(
                Where::equals('a', 'v1'),
                Where::equals('b', 'v2'),
            ),
        );
    }

    public function testAndWhereWithOneConditionAfterWhereWithOneCondition(): void
    {
        self::assertSameIgnoringWhitespace(
            "SELECT Id FROM Object WHERE a = 'v1' AND b = 'v2'",
            SoqlBuilder::select('Object')
                ->columns('Id')
                ->where(Where::equals('a', 'v1'))
                ->andWhere(Where::equals('b', 'v2')),
        );
    }

    public function testTwoAndWheres(): void
    {
        self::assertSameIgnoringWhitespace(
            "SELECT Id FROM Object WHERE a = 'v1' AND b = 'v2'",
            SoqlBuilder::select('Object')
                ->columns('Id')
                ->andWhere(Where::equals('a', 'v1'))
                ->andWhere(Where::equals('b', 'v2')),
        );
    }

    public function testGroupByWithOneColumn(): void
    {
        self::assertSameIgnoringWhitespace(
            'SELECT Id FROM Object GROUP BY Name',
            SoqlBuilder::select('Object')->columns('Id')->groupBy('Name'),
        );
    }

    public function testGroupByWithTwoConditions(): void
    {
        self::assertSameIgnoringWhitespace(
            'SELECT Id FROM Object GROUP BY Name, Other',
            SoqlBuilder::select('Object')->columns('Id')->groupBy('Name', 'Other'),
        );
    }

    public function testAddGroupByWithOneCondition(): void
    {
        self::assertSameIgnoringWhitespace(
            'SELECT Id FROM Object GROUP BY Name, Other',
            SoqlBuilder::select('Object')->columns('Id')
                ->groupBy('Name')
                ->addGroupBy('Other'),
        );
    }

    public function testAddGroupByWithTwoConditions(): void
    {
        self::assertSameIgnoringWhitespace(
            'SELECT Id FROM Object GROUP BY Name, Other, Even.More, And.More',
            SoqlBuilder::select('Object')->columns('Id')
                ->groupBy('Name', 'Other')
                ->addGroupBy('Even.More', 'And.More'),
        );
    }

    public function testTwoAddGroupBysWithoutGroupBy(): void
    {
        self::assertSameIgnoringWhitespace(
            'SELECT Id FROM Object GROUP BY Name, Other',
            SoqlBuilder::select('Object')->columns('Id')
                ->addGroupBy('Name')
                ->addGroupBy('Other'),
        );
    }

    public function testWhereWithCombiningConditions(): void
    {
        self::assertSameIgnoringWhitespace(
            "SELECT Id FROM Object WHERE a = 'v1' AND b > 10 AND ((e = NULL AND f != FALSE) OR c < 'v3' OR d >= -10) GROUP BY Name",
            SoqlBuilder::select('Object')
                ->columns('Id')
                ->where(
                    Where::equals('a', 'v1'),
                    Where::greater('b', 10),
                    Where::or(
                        Where::and(
                            Where::equals('e', null),
                            Where::notEquals('f', false),
                        ),
                        Where::less('c', 'v3'),
                        Where::greaterEqual('d', -10),
                    ),
                )
                ->groupBy('Name'),
        );
    }

    public function testComplexQuery(): void
    {
        self::assertSameIgnoringWhitespace(
            "SELECT Id FROM Object WHERE a = 'v1' AND b > 10 AND ((e = NULL AND f != FALSE) OR c < 'v3' OR d >= -10) GROUP BY Name, Other",
            SoqlBuilder::select('Object')
                ->columns('Id')
                ->where(
                    Where::equals('a', 'v1'),
                    Where::greater('b', 10),
                )
                ->andWhere(
                    Where::or(
                        Where::and(
                            Where::equals('e', null),
                            Where::notEquals('f', false),
                        ),
                        Where::less('c', 'v3'),
                        Where::greaterEqual('d', -10),
                    ),
                )
                ->groupBy('Name')
                ->addGroupBy('Other'),
        );
    }

    public function testLimit(): void
    {
        self::assertSameIgnoringWhitespace(
            "SELECT Id FROM Object WHERE a = 'v1' LIMIT 10",
            SoqlBuilder::select('Object')
                ->columns('Id')
                ->where(Where::equals('a', 'v1'))
                ->limit(10),
        );
    }

    public function testOffset(): void
    {
        self::assertSameIgnoringWhitespace(
            "SELECT Id FROM Object WHERE a = 'v1' OFFSET 10",
            SoqlBuilder::select('Object')
                ->columns('Id')
                ->where(Where::equals('a', 'v1'))
                ->offset(10),
        );
    }

    public function testLimitAndOffset(): void
    {
        self::assertSameIgnoringWhitespace(
            "SELECT Id FROM Object WHERE a = 'v1' LIMIT 100 OFFSET 10",
            SoqlBuilder::select('Object')
                ->columns('Id')
                ->where(Where::equals('a', 'v1'))
                ->limit(100)
                ->offset(10),
        );
    }

    public function testBuilderFailsWithEmptyFrom(): void
    {
        self::expectException(RuntimeException::class);
        self::expectExceptionMessage('Object cannot be empty');

        SoqlBuilder::select('');
    }

    public function testBuilderFailsWithTooLargeOffset(): void
    {
        self::expectException(InvalidArgumentException::class);
        self::expectExceptionMessage('Offset must be less than or equal to ' . SoqlBuilder::MAX_OFFSET);

        SoqlBuilder::select('o')->offset(SoqlBuilder::MAX_OFFSET + 1);
    }

    public function testTooLongQueryFails(): void
    {
        self::expectException(RuntimeException::class);
        self::expectExceptionMessage(' is too long');

        SoqlBuilder::select('o')
            ->columns(\str_repeat('x', SoqlBuilder::MAX_QUERY_LENGTH + 1))
            ->__toString();
    }
}
