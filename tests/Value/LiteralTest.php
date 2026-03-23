<?php
declare(strict_types=1);

namespace ScoutingNL\Tests\Salesforce\Soql\Value;

use PHPUnit\Framework\Attributes\CoversClass;
use ScoutingNL\Salesforce\Soql\Value\Literal;
use ScoutingNL\Tests\Salesforce\Soql\TestCase;

#[CoversClass(Literal::class)]
class LiteralTest extends TestCase
{
    public function test(): void
    {
        self::assertSameIgnoringWhitespace('Literal Test Data', (new Literal('Literal Test Data'))->format());
    }
}
