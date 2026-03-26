<?php
declare(strict_types=1);

namespace ScoutingNL\Tests\Salesforce\Soql\Column\Aggregate;

use PHPUnit\Framework\Attributes\CoversClass;
use ScoutingNL\Salesforce\Soql\Column\Aggregate\AggregateFunction;
use ScoutingNL\Salesforce\Soql\Column\Aggregate\Sum;
use ScoutingNL\Tests\Salesforce\Soql\TestCase;

#[CoversClass(Sum::class)]
#[CoversClass(AggregateFunction::class)]
class SumTest extends TestCase
{
    public function testWithoutAlias(): void
    {
        self::assertSameIgnoringWhitespace('SUM(Test)', (new Sum('Test'))->format());
    }

    public function testWithAlias(): void
    {
        self::assertSameIgnoringWhitespace('SUM(Test) alias', (new Sum('Test', 'alias'))->format());
    }
}
