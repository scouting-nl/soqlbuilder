<?php
declare(strict_types=1);

namespace ScoutingNL\Tests\Salesforce\Soql\Column;

use PHPUnit\Framework\Attributes\CoversClass;
use ScoutingNL\Salesforce\Soql\Column\DateTime;
use ScoutingNL\Tests\Salesforce\Soql\TestCase;

#[CoversClass(DateTime::class)]
class DateTimeTest extends TestCase
{
    public function test(): void
    {
        self::assertSameIgnoringWhitespace(
            '2025-10-12T12:06:32+02:00',
            (new DateTime(new \DateTimeImmutable('2025-10-12T12:06:32+0200')))->format(),
        );
    }
}
