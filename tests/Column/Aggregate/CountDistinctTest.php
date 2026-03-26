<?php
declare(strict_types=1);

namespace ScoutingNL\Tests\Salesforce\Soql\Column\Aggregate;

use PHPUnit\Framework\Attributes\CoversClass;
use ScoutingNL\Salesforce\Soql\Column\Aggregate\AggregateFunction;
use ScoutingNL\Salesforce\Soql\Column\Aggregate\CountDistinct;
use ScoutingNL\Tests\Salesforce\Soql\TestCase;

#[CoversClass(CountDistinct::class)]
#[CoversClass(AggregateFunction::class)]
class CountDistinctTest extends TestCase
{
    public function testWithoutAlias(): void
    {
        self::assertSameIgnoringWhitespace('COUNT_DISTINCT(Test)', (new CountDistinct('Test'))->format());
    }

    public function testWithAlias(): void
    {
        self::assertSameIgnoringWhitespace('COUNT_DISTINCT(Test) alias', (new CountDistinct('Test', 'alias'))->format());
    }
}
