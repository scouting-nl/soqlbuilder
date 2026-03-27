<?php
declare(strict_types=1);

namespace ScoutingNL\Tests\Salesforce\Soql\Column\Func\Aggregate;

use PHPUnit\Framework\Attributes\CoversClass;
use ScoutingNL\Salesforce\Soql\Column\Func\Aggregate\Count;
use ScoutingNL\Salesforce\Soql\Column\Func\Func;
use ScoutingNL\Tests\Salesforce\Soql\TestCase;

#[CoversClass(Count::class)]
#[CoversClass(Func::class)]
class CountTest extends TestCase
{
    public function testWithoutColumnWithoutAlias(): void
    {
        self::assertSameIgnoringWhitespace('COUNT()', (new Count())->format());
    }

    public function testWithoutColumnWithAlias(): void
    {
        self::assertSameIgnoringWhitespace('COUNT() alias', (new Count(alias: 'alias'))->format());
    }

    public function testWIthColumnWithoutAlias(): void
    {
        self::assertSameIgnoringWhitespace('COUNT(Test)', (new Count('Test'))->format());
    }

    public function testWithColumnWithAlias(): void
    {
        self::assertSameIgnoringWhitespace('COUNT(Test) alias', (new Count('Test', 'alias'))->format());
    }

    public function testWithoutColumnWithoutAliasFormatWithoutAlias(): void
    {
        self::assertSameIgnoringWhitespace('COUNT()', (new Count())->formatWithoutAlias());
    }

    public function testWithoutColumnWithAliasFormatWithoutAlias(): void
    {
        self::assertSameIgnoringWhitespace('COUNT()', (new Count(alias: 'alias'))->formatWithoutAlias());
    }

    public function testWithColumnWithoutAliasFormatWithoutAlias(): void
    {
        self::assertSameIgnoringWhitespace('COUNT(Test)', (new Count('Test'))->formatWithoutAlias());
    }

    public function testWithColumnWithAliasFormatWithoutAlias(): void
    {
        self::assertSameIgnoringWhitespace('COUNT(Test)', (new Count('Test', 'alias'))->formatWithoutAlias());
    }
}
