<?php
declare(strict_types=1);

namespace ScoutingNL\Salesforce\Soql\Column\Func\Aggregate;

final readonly class Sum extends AggregateFunction
{
    public function __construct(string $column, ?string $alias = null)
    {
        parent::__construct('SUM', $column, $alias);
    }
}
