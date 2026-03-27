<?php
declare(strict_types=1);

namespace ScoutingNL\Tests\Salesforce\Soql\Column\Func\Aggregate;

use PHPUnit\Framework\Attributes\CoversClass;
use ScoutingNL\Salesforce\Soql\Column\Func\Aggregate\Avg;
use ScoutingNL\Salesforce\Soql\Column\Func\Func;
use ScoutingNL\Tests\Salesforce\Soql\TestCase;

#[CoversClass(Avg::class)]
#[CoversClass(Func::class)]
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

    public function testWithoutAliasFormatWithoutAlias(): void
    {
        self::assertSameIgnoringWhitespace('AVG(Test)', (new Avg('Test'))->formatWithoutAlias());
    }

    public function testWithAliasFormatWithoutAlias(): void
    {
        self::assertSameIgnoringWhitespace('AVG(Test)', (new Avg('Test', 'alias'))->formatWithoutAlias());
    }
}
