<?php
declare(strict_types=1);

namespace ScoutingNL\Salesforce\Soql\Column\Func\Date;

use ScoutingNL\Salesforce\Soql\Column\Func\Aggregate\AggregateFunction;

abstract readonly class DateFunction extends AggregateFunction
{
    public function __construct(string $function, string|ConvertTimezone $column, ?string $alias = null)
    {
        parent::__construct(
            $function,
            $column instanceof ConvertTimezone ? "convertTimezone({$column->column})" : $column,
            $alias,
        );
    }
}
