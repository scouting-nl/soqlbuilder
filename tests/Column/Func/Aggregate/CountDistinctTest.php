<?php
declare(strict_types=1);

namespace ScoutingNL\Tests\Salesforce\Soql\Column\Func\Aggregate;

use PHPUnit\Framework\Attributes\CoversClass;
use ScoutingNL\Salesforce\Soql\Column\Func\Aggregate\CountDistinct;
use ScoutingNL\Salesforce\Soql\Column\Func\Func;
use ScoutingNL\Tests\Salesforce\Soql\TestCase;

#[CoversClass(CountDistinct::class)]
#[CoversClass(Func::class)]
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

    public function testWithoutAliasFormatWithoutAlias(): void
    {
        self::assertSameIgnoringWhitespace('COUNT_DISTINCT(Test)', (new CountDistinct('Test'))->formatWithoutAlias());
    }

    public function testWithAliasFormatWithoutAlias(): void
    {
        self::assertSameIgnoringWhitespace('COUNT_DISTINCT(Test)', (new CountDistinct('Test', 'alias'))->formatWithoutAlias());
    }
}
