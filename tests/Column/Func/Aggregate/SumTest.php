<?php
declare(strict_types=1);

namespace ScoutingNL\Tests\Salesforce\Soql\Column\Func\Aggregate;

use PHPUnit\Framework\Attributes\CoversClass;
use ScoutingNL\Salesforce\Soql\Column\Func\Aggregate\Sum;
use ScoutingNL\Salesforce\Soql\Column\Func\Func;
use ScoutingNL\Tests\Salesforce\Soql\TestCase;

#[CoversClass(Sum::class)]
#[CoversClass(Func::class)]
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

    public function testWithoutAliasFormatWithoutAlias(): void
    {
        self::assertSameIgnoringWhitespace('SUM(Test)', (new Sum('Test'))->formatWithoutAlias());
    }

    public function testWithAliasFormatWithoutAlias(): void
    {
        self::assertSameIgnoringWhitespace('SUM(Test)', (new Sum('Test', 'alias'))->formatWithoutAlias());
    }
}
