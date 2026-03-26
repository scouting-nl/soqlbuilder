<?php
declare(strict_types=1);

namespace ScoutingNL\Salesforce\Soql\Column\Aggregate;

final readonly class Max extends AggregateFunction
{
    public function __construct(string $column, ?string $alias = null)
    {
        parent::__construct('MAX', $column, $alias);
    }
}
