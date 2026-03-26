<?php
declare(strict_types=1);

namespace ScoutingNL\Tests\Salesforce\Soql\Column\Aggregate;

use PHPUnit\Framework\Attributes\CoversClass;
use ScoutingNL\Salesforce\Soql\Column\Aggregate\AggregateFunction;
use ScoutingNL\Salesforce\Soql\Column\Aggregate\Count;
use ScoutingNL\Tests\Salesforce\Soql\TestCase;

#[CoversClass(Count::class)]
#[CoversClass(AggregateFunction::class)]
class CountTest extends TestCase
{
    public function testWIthoutColumnWithoutAlias(): void
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
}
