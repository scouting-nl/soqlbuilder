<?php
declare(strict_types=1);

namespace ScoutingNL\Tests\Salesforce\Soql\Value\DateTime;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\TestWith;
use ScoutingNL\Salesforce\Soql\Value\DateTime\DateLiteral;
use ScoutingNL\Salesforce\Soql\Value\DateTime\DateUnit;
use ScoutingNL\Tests\Salesforce\Soql\TestCase;

class DateLiteralTest extends TestCase
{
    #[TestWith(['TODAY', DateLiteral::TODAY])]
    #[TestWith(['TOMORROW', DateLiteral::TOMORROW])]
    #[TestWith(['YESTERDAY', DateLiteral::YESTERDAY])]
    #[TestWith(['LAST_90_DAYS', DateLiteral::LAST_90_DAYS])]
    #[TestWith(['NEXT_90_DAYS', DateLiteral::NEXT_90_DAYS])]
    public function testToday(string $expected, DateLiteral $dateLiteral): void
    {
        self::assertSame($expected, $dateLiteral->format());
    }

    #[DataProvider('dateUnitProvider')]
    public function testNext(DateUnit $unit): void
    {
        if ($unit === DateUnit::DAY) {
            self::assertSame('TOMORROW', DateLiteral::next($unit)->format());
        } else {
            self::assertSame("NEXT_{$unit->name}", DateLiteral::next($unit)->format());
        }
    }

    #[DataProvider('dateUnitProvider')]
    public function testLast(DateUnit $unit): void
    {
        if ($unit === DateUnit::DAY) {
            self::assertSame('YESTERDAY', DateLiteral::last($unit)->format());
        } else {
            self::assertSame("LAST_{$unit->name}", DateLiteral::last($unit)->format());
        }
    }

    #[DataProvider('dateUnitProvider')]
    public function testThis(DateUnit $unit): void
    {
        if ($unit === DateUnit::DAY) {
            self::assertSame('TODAY', DateLiteral::this($unit)->format());
        } else {
            self::assertSame("THIS_{$unit->name}", DateLiteral::this($unit)->format());
        }
    }

    #[DataProvider('dateUnitProvider')]
    public function testNextN(DateUnit $unit): void
    {
        self::assertSame("NEXT_N_{$unit->name}S:10", DateLiteral::nextN($unit, 10)->format());
    }

    #[DataProvider('dateUnitProvider')]
    public function testLastN(DateUnit $unit): void
    {
        self::assertSame("LAST_N_{$unit->name}S:10", DateLiteral::lastN($unit, 10)->format());
    }

    #[DataProvider('dateUnitProvider')]
    public function testNAgo(DateUnit $unit): void
    {
        self::assertSame("N_{$unit->name}S_AGO:10", DateLiteral::nAgo($unit, 10)->format());
    }

    public static function dateUnitProvider(): \Generator
    {
        foreach (DateUnit::cases() as $dateUnit) {
            yield [$dateUnit];
        }
    }
}
