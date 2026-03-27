<?php
declare(strict_types=1);

namespace ScoutingNL\Salesforce\Soql\Column\Func\Date;

final readonly class CalendarQuarter extends DateFunction
{
    public function __construct(string|ConvertTimezone $column, ?string $alias = null)
    {
        parent::__construct('CALENDAR_QUARTER', $column, $alias);
    }
}
