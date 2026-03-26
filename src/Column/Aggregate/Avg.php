<?php
declare(strict_types=1);

namespace ScoutingNL\Salesforce\Soql\Column\Aggregate;

final readonly class Avg extends AggregateFunction
{
    public function __construct(string $column, ?string $alias = null)
    {
        parent::__construct('AVG', $column, $alias);
    }
}
