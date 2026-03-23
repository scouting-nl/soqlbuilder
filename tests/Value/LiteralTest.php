<?php
declare(strict_types=1);

namespace ScoutingNL\Tests\Salesforce\Soql\Value;

use ScoutingNL\Salesforce\Soql\Value\Literal;
use ScoutingNL\Tests\Salesforce\Soql\TestCase;

class LiteralTest extends TestCase
{
    public function testToday(): void
    {
        self::assertSame('TODAY', Literal::TODAY->format());
    }
}
