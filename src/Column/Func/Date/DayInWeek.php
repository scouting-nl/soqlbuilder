<?php
declare(strict_types=1);

namespace ScoutingNL\Salesforce\Soql\Column\Func\Date;

final readonly class DayInWeek extends DateFunction
{
    public function __construct(string|ConvertTimezone $column, ?string $alias = null)
    {
        parent::__construct('DAY_IN_WEEK', $column, $alias);
    }
}
