<?php
declare(strict_types=1);

namespace ScoutingNL\Tests\Salesforce\Soql\Column\Aggregate;

use PHPUnit\Framework\Attributes\CoversClass;
use ScoutingNL\Salesforce\Soql\Column\Aggregate\AggregateFunction;
use ScoutingNL\Salesforce\Soql\Column\Aggregate\Max;
use ScoutingNL\Tests\Salesforce\Soql\TestCase;

#[CoversClass(Max::class)]
#[CoversClass(AggregateFunction::class)]
class MaxTest extends TestCase
{
    public function testWithoutAlias(): void
    {
        self::assertSameIgnoringWhitespace('MAX(Test)', (new Max('Test'))->format());
    }

    public function testWithAlias(): void
    {
        self::assertSameIgnoringWhitespace('MAX(Test) alias', (new Max('Test', 'alias'))->format());
    }
}
