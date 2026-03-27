<?php
declare(strict_types=1);

namespace ScoutingNL\Tests\Salesforce\Soql\Column\Func\Aggregate;

use PHPUnit\Framework\Attributes\CoversClass;
use ScoutingNL\Salesforce\Soql\Column\Func\Aggregate\Min;
use ScoutingNL\Salesforce\Soql\Column\Func\Func;
use ScoutingNL\Tests\Salesforce\Soql\TestCase;

#[CoversClass(Min::class)]
#[CoversClass(Func::class)]
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

    public function testWithoutAliasFormatWithoutAlias(): void
    {
        self::assertSameIgnoringWhitespace('MIN(Test)', (new Min('Test'))->formatWithoutAlias());
    }

    public function testWithAliasFormatWithoutAlias(): void
    {
        self::assertSameIgnoringWhitespace('MIN(Test)', (new Min('Test', 'alias'))->formatWithoutAlias());
    }
}
