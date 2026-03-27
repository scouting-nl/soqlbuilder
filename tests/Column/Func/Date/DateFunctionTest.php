<?php
declare(strict_types=1);

namespace ScoutingNL\Tests\Salesforce\Soql\Column\Func\Date;

use PHPUnit\Framework\Attributes\DataProvider;
use ScoutingNL\Salesforce\Soql\Column\Func\Date\CalendarMonth;
use ScoutingNL\Salesforce\Soql\Column\Func\Date\CalendarQuarter;
use ScoutingNL\Salesforce\Soql\Column\Func\Date\CalendarYear;
use ScoutingNL\Salesforce\Soql\Column\Func\Date\ConvertTimezone;
use ScoutingNL\Salesforce\Soql\Column\Func\Date\DateFunction;
use ScoutingNL\Salesforce\Soql\Column\Func\Date\DayInMonth;
use ScoutingNL\Salesforce\Soql\Column\Func\Date\DayInWeek;
use ScoutingNL\Salesforce\Soql\Column\Func\Date\DayInYear;
use ScoutingNL\Salesforce\Soql\Column\Func\Date\DayOnly;
use ScoutingNL\Salesforce\Soql\Column\Func\Date\FiscalMonth;
use ScoutingNL\Salesforce\Soql\Column\Func\Date\FiscalQuarter;
use ScoutingNL\Salesforce\Soql\Column\Func\Date\FiscalYear;
use ScoutingNL\Salesforce\Soql\Column\Func\Date\HourInDay;
use ScoutingNL\Salesforce\Soql\Column\Func\Date\WeekInMonth;
use ScoutingNL\Salesforce\Soql\Column\Func\Date\WeekInYear;
use ScoutingNL\Tests\Salesforce\Soql\TestCase;

class DateFunctionTest extends TestCase
{
    /**
     * @return \Generator<array{string, class-string<DateFunction>}>
     */
    public static function dateFunctionProvider(): \Generator
    {
        yield ['CALENDAR_MONTH', CalendarMonth::class];
        yield ['CALENDAR_QUARTER', CalendarQuarter::class];
        yield ['CALENDAR_YEAR', CalendarYear::class];
        yield ['DAY_IN_MONTH', DayInMonth::class];
        yield ['DAY_IN_WEEK', DayInWeek::class];
        yield ['DAY_IN_YEAR', DayInYear::class];
        yield ['DAY_ONLY', DayOnly::class];
        yield ['FISCAL_MONTH', FiscalMonth::class];
        yield ['FISCAL_QUARTER', FiscalQuarter::class];
        yield ['FISCAL_YEAR', FiscalYear::class];
        yield ['HOUR_IN_DAY', HourInDay::class];
        yield ['WEEK_IN_MONTH', WeekInMonth::class];
        yield ['WEEK_IN_YEAR', WeekInYear::class];
    }

    /**
     * @param class-string<DateFunction> $dateFunctionClass
     */
    #[DataProvider('dateFunctionProvider')]
    public function testWithoutAlias(string $dateFunctionString, string $dateFunctionClass): void
    {
        self::assertSameIgnoringWhitespace($dateFunctionString . '(Test)', (new $dateFunctionClass('Test'))->format());
    }

    /**
     * @param class-string<DateFunction> $dateFunctionClass
     */
    #[DataProvider('dateFunctionProvider')]
    public function testWithAlias(string $dateFunctionString, string $dateFunctionClass): void
    {
        self::assertSameIgnoringWhitespace($dateFunctionString . '(Test) alias', (new $dateFunctionClass('Test', 'alias'))->format());
    }

    /**
     * @param class-string<DateFunction> $dateFunctionClass
     */
    #[DataProvider('dateFunctionProvider')]
    public function testWithoutAliasFormatWithoutAlias(string $dateFunctionString, string $dateFunctionClass): void
    {
        self::assertSameIgnoringWhitespace($dateFunctionString . '(Test)', (new $dateFunctionClass('Test'))->formatWithoutAlias());
    }

    /**
     * @param class-string<DateFunction> $dateFunctionClass
     */
    #[DataProvider('dateFunctionProvider')]
    public function testWithAliasFormatWithoutAlias(string $dateFunctionString, string $dateFunctionClass): void
    {
        self::assertSameIgnoringWhitespace($dateFunctionString . '(Test)', (new $dateFunctionClass('Test', 'alias'))->formatWithoutAlias());
    }

    /**
     * @param class-string<DateFunction> $dateFunctionClass
     */
    #[DataProvider('dateFunctionProvider')]
    public function testWithoutAliasWithConvertTimezone(string $dateFunctionString, string $dateFunctionClass): void
    {
        self::assertSameIgnoringWhitespace($dateFunctionString . '(convertTimezone(Test))', (new $dateFunctionClass(new ConvertTimezone('Test')))->format());
    }

    /**
     * @param class-string<DateFunction> $dateFunctionClass
     */
    #[DataProvider('dateFunctionProvider')]
    public function testWithAliasWithConvertTimezone(string $dateFunctionString, string $dateFunctionClass): void
    {
        self::assertSameIgnoringWhitespace($dateFunctionString . '(convertTimezone(Test)) alias', (new $dateFunctionClass(new ConvertTimezone('Test'), 'alias'))->format());
    }

    /**
     * @param class-string<DateFunction> $dateFunctionClass
     */
    #[DataProvider('dateFunctionProvider')]
    public function testWithoutAliasFormatWithoutAliasWithConvertTimezone(string $dateFunctionString, string $dateFunctionClass): void
    {
        self::assertSameIgnoringWhitespace($dateFunctionString . '(convertTimezone(Test))', (new $dateFunctionClass(new ConvertTimezone('Test')))->formatwithoutalias());
    }

    /**
     * @param class-string<DateFunction> $dateFunctionClass
     */
    #[DataProvider('dateFunctionProvider')]
    public function testWithAliasFormatWithoutAliasWithConvertTimezone(string $dateFunctionString, string $dateFunctionClass): void
    {
        self::assertSameIgnoringWhitespace($dateFunctionString . '(convertTimezone(Test))', (new $dateFunctionClass(new ConvertTimezone('Test'), 'alias'))->formatWithoutAlias());
    }
}
