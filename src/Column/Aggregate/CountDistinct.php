<?php
declare(strict_types=1);

namespace ScoutingNL\Salesforce\Soql\Column\Aggregate;

final readonly class CountDistinct extends AggregateFunction
{
    public function __construct(string $column, ?string $alias = null)
    {
        parent::__construct('COUNT_DISTINCT', $column, $alias);
    }
}
