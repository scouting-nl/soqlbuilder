<?php
declare(strict_types=1);

namespace App\Tests\Salesforce\Soql;

use App\Salesforce\Soql\SoqlBuilder;
use App\Salesforce\Soql\Where;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class SoqlBuilderTest extends TestCase
{
    #[DataProvider('provideBuilder')]
    public function testSoqlBuilder(string $expected, SoqlBuilder $builder): void
    {
        self::assertEquals(
            \preg_replace('/(\s|[\n\r])+/', ' ', \trim($expected)),
            \preg_replace('/(\s|[\n\r])+/', ' ', $builder->toSoql()),
        );
    }

    /**
     * @return \Generator<array-key, array{string, SoqlBuilder}>
     */
    public static function provideBuilder(): \Generator
    {
        yield [
            'SELECT Id FROM Object',
            SoqlBuilder::select('Object')->columns('Id'),
        ];

        yield [
            'SELECT Id, Name FROM Object',
            SoqlBuilder::select('Object')->columns('Id', 'Name'),
        ];

        yield [
            'SELECT Id, Name, c1, c2, c3, c4, c5, c6, c7, c8 FROM Object',
            SoqlBuilder::select('Object')
                ->columns('Id', 'Name', 'c1', 'c2', 'c3', 'c4', 'c5', 'c6', 'c7', 'c8'),
        ];

        yield [
            'SELECT Id, Name, More, Columns FROM Object',
            SoqlBuilder::select('Object')
                ->columns('Id', 'Name')
                ->addColumns('More', 'Columns'),
        ];

        yield [
            'SELECT Id, Name FROM Object',
            SoqlBuilder::select('Object')
                ->addColumns('More', 'Columns')
                ->columns('Id', 'Name'),
        ];

        yield [
            'SELECT Id, (SELECT Name FROM Other_Object) FROM Object',
            SoqlBuilder::select('Object')
                ->columns(
                    'Id',
                    SoqlBuilder::select('Other_Object')->columns('Name'),
                ),
        ];

        yield [
            'SELECT Id, (SELECT Name FROM Other_Object), More, Columns FROM Object',
            SoqlBuilder::select('Object')
                ->columns(
                    'Id',
                    SoqlBuilder::select('Other_Object')->columns('Name'),
                )
                ->addColumns('More', 'Columns'),
        ];

        yield [
            'SELECT Id, (SELECT Name FROM Other_Object), (SELECT More FROM More_Object), Columns FROM Object',
            SoqlBuilder::select('Object')
                ->columns(
                    'Id',
                    SoqlBuilder::select('Other_Object')->columns('Name'),
                )
                ->addColumns(SoqlBuilder::select('More_Object')->columns('More'), 'Columns'),
        ];

        yield [
            "SELECT Id FROM Object WHERE a = 'v1'",
            SoqlBuilder::select('Object')->columns('Id')->where(Where::equals('a', 'v1')),
        ];

        yield [
            "SELECT Id FROM Object WHERE a = 'v1' AND b = 'v2'",
            SoqlBuilder::select('Object')->columns('Id')->where(
                Where::equals('a', 'v1'),
                Where::equals('b', 'v2'),
            ),
        ];

        yield [
            "SELECT Id FROM Object WHERE a = 'v1' AND b = 'v2'",
            SoqlBuilder::select('Object')
                ->columns('Id')
                ->where(Where::equals('a', 'v1'))
                ->andWhere(Where::equals('b', 'v2')),
        ];

        yield [
            "SELECT Id FROM Object WHERE a = 'v1' AND b = 'v2'",
            SoqlBuilder::select('Object')
                ->columns('Id')
                ->andWhere(Where::equals('a', 'v1'))
                ->andWhere(Where::equals('b', 'v2')),
        ];

        yield [
            "SELECT Id FROM Object WHERE a = 'v1' AND b > 10 AND ((e = NULL AND f != FALSE) OR c < 'v3' OR d >= -10)",
            SoqlBuilder::select('Object')->columns('Id')->where(
                Where::equals('a', 'v1'),
                Where::greater('b', 10),
                Where::orX(
                    Where::andX(
                        Where::equals('e', null),
                        Where::notEquals('f', false),
                    ),
                    Where::less('c', 'v3'),
                    Where::greaterEqual('d', -10),
                ),
            ),
        ];

        yield [
            "SELECT Id FROM Object WHERE a = 'v1' AND b > 10 AND ((e = NULL AND f != FALSE) OR c < 'v3' OR d >= -10)",
            SoqlBuilder::select('Object')
                ->columns('Id')
                ->where(
                    Where::equals('a', 'v1'),
                    Where::greater('b', 10),
                )
                ->andWhere(
                    Where::orX(
                        Where::andX(
                            Where::equals('e', null),
                            Where::notEquals('f', false),
                        ),
                        Where::less('c', 'v3'),
                        Where::greaterEqual('d', -10),
                    ),
                ),
        ];

        yield [
            "SELECT Id FROM Object WHERE a = 'v1' LIMIT 10",
            SoqlBuilder::select('Object')
                ->columns('Id')
                ->where(Where::equals('a', 'v1'))
                ->limit(10),
        ];

        yield [
            "SELECT Id FROM Object WHERE a = 'v1' OFFSET 10",
            SoqlBuilder::select('Object')
                ->columns('Id')
                ->where(Where::equals('a', 'v1'))
                ->offset(10),
        ];

        yield [
            "SELECT Id FROM Object WHERE a = 'v1' LIMIT 100 OFFSET 10",
            SoqlBuilder::select('Object')
                ->columns('Id')
                ->where(Where::equals('a', 'v1'))
                ->limit(100)
                ->offset(10),
        ];
    }

    public function testBuilderFailsWithEmptyFrom(): void
    {
        self::expectException(\RuntimeException::class);
        self::expectExceptionMessage('Object cannot be empty');

        SoqlBuilder::select('');
    }

    public function testBuilderFailsWithTooLargeOffset(): void
    {
        self::expectException(\InvalidArgumentException::class);
        self::expectExceptionMessage('Offset must be less than or equal to ' . SoqlBuilder::MAX_OFFSET);

        SoqlBuilder::select('o')->offset(SoqlBuilder::MAX_OFFSET + 1);
    }
}
