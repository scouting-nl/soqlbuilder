<?php
declare(strict_types=1);

namespace ScoutingNL\Tests\Salesforce\Soql\Column\Aggregate;

use PHPUnit\Framework\Attributes\CoversClass;
use ScoutingNL\Salesforce\Soql\Column\Aggregate\AggregateFunction;
use ScoutingNL\Salesforce\Soql\Column\Aggregate\Min;
use ScoutingNL\Tests\Salesforce\Soql\TestCase;

#[CoversClass(Min::class)]
#[CoversClass(AggregateFunction::class)]
class MinTest extends TestCase
{
    public function testWithoutAlias(): void
    {
        self::assertSameIgnoringWhitespace('MIN(Test)', (new Min('Test'))->format());
    }

    public function testWithAlias(): void
    {
        self::assertSameIgnoringWhitespace('MIN(Test) alias', (new Min('Test', 'alias'))->format());
    }
}
