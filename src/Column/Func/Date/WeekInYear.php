<?php
declare(strict_types=1);

namespace ScoutingNL\Salesforce\Soql\Column\Func\Date;

final readonly class WeekInYear extends DateFunction
{
    public function __construct(string|ConvertTimezone $column, ?string $alias = null)
    {
        parent::__construct('WEEK_IN_YEAR', $column, $alias);
    }
}
