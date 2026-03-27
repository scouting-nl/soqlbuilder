<?php
declare(strict_types=1);

namespace ScoutingNL\Tests\Salesforce\Soql\Column\Func\Aggregate;

use PHPUnit\Framework\Attributes\CoversClass;
use ScoutingNL\Salesforce\Soql\Column\Func\Aggregate\Max;
use ScoutingNL\Salesforce\Soql\Column\Func\Func;
use ScoutingNL\Tests\Salesforce\Soql\TestCase;

#[CoversClass(Max::class)]
#[CoversClass(Func::class)]
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

    public function testWithoutAliasFormatWithoutAlias(): void
    {
        self::assertSameIgnoringWhitespace('MAX(Test)', (new Max('Test'))->formatWithoutAlias());
    }

    public function testWithAliasFormatWithoutAlias(): void
    {
        self::assertSameIgnoringWhitespace('MAX(Test)', (new Max('Test', 'alias'))->formatWithoutAlias());
    }
}
