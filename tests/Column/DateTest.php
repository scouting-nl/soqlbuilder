<?php
declare(strict_types=1);

namespace ScoutingNL\Tests\Salesforce\Soql\Column;

use PHPUnit\Framework\Attributes\CoversClass;
use ScoutingNL\Salesforce\Soql\Column\Date;
use ScoutingNL\Tests\Salesforce\Soql\TestCase;

#[CoversClass(Date::class)]
class DateTest extends TestCase
{
    public function test(): void
    {
        self::assertSameIgnoringWhitespace(
            '2025-10-12',
            (new Date(new \DateTimeImmutable('2025-10-12T12:06:32+0200')))->format(),
        );
    }
}
