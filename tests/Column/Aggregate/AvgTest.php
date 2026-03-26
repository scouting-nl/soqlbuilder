<?php
declare(strict_types=1);

namespace ScoutingNL\Tests\Salesforce\Soql\Column\Aggregate;

use PHPUnit\Framework\Attributes\CoversClass;
use ScoutingNL\Salesforce\Soql\Column\Aggregate\AggregateFunction;
use ScoutingNL\Salesforce\Soql\Column\Aggregate\Avg;
use ScoutingNL\Tests\Salesforce\Soql\TestCase;

#[CoversClass(Avg::class)]
#[CoversClass(AggregateFunction::class)]
class AvgTest extends TestCase
{
    public function testWithoutAlias(): void
    {
        self::assertSameIgnoringWhitespace('AVG(Test)', (new Avg('Test'))->format());
    }

    public function testWithAlias(): void
    {
        self::assertSameIgnoringWhitespace('AVG(Test) alias', (new Avg('Test', 'alias'))->format());
    }
}
