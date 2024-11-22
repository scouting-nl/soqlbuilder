<?php

namespace App\Tests\Salesforce\Soql;

use App\Salesforce\Soql\SoqlBuilder;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\TestWith;
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
            SoqlBuilder::select('Id')->from('Object'),
        ];

        yield [
            'SELECT Id, Name FROM Object',
            SoqlBuilder::select('Id', 'Name')->from('Object'),
        ];

        yield [
            'SELECT Id, Name, c1, c2, c3, c4, c5, c6, c7, c8 FROM Object',
            SoqlBuilder::select('Id', 'Name', 'c1', 'c2', 'c3', 'c4', 'c5', 'c6', 'c7', 'c8')->from('Object'),
        ];

        yield [
            'SELECT Id, Name, More, Columns FROM Object',
            SoqlBuilder::select('Id', 'Name')->addSelect('More', 'Columns')->from('Object'),
        ];

        yield [
            'SELECT Id, Name, More, Columns FROM Object',
            SoqlBuilder::select('Id', 'Name')->from('Object')->addSelect('More', 'Columns'),
        ];

        yield [
            'SELECT Id, (SELECT Name FROM Other_Object) FROM Object',
            SoqlBuilder::select(
                'Id',
                SoqlBuilder::select('Name')->from('Other_Object'),
            )->from('Object'),
        ];

        yield [
            'SELECT Id, (SELECT Name FROM Other_Object), More, Columns FROM Object',
            SoqlBuilder::select(
                'Id',
                SoqlBuilder::select('Name')->from('Other_Object'),
            )->from('Object')
                ->addSelect('More', 'Columns'),
        ];

        yield [
            'SELECT Id, (SELECT Name FROM Other_Object), (SELECT More FROM More_Object), Columns FROM Object',
            SoqlBuilder::select(
                'Id',
                SoqlBuilder::select('Name')->from('Other_Object'),
            )->from('Object')
                ->addSelect(SoqlBuilder::select('More')->from('More_Object'), 'Columns'),
        ];
    }

    #[TestWith([null])]
    #[TestWith([''])]
    public function testBuilderFailsWithoutFrom(?string $object): void
    {
        self::expectException(\RuntimeException::class);
        self::expectExceptionMessage('Must select from an object');

        if ($object === null) {
            SoqlBuilder::select('Id')->toSoql();
        } else {
            SoqlBuilder::select('Id')->from($object)->toSoql();
        }
    }
}
