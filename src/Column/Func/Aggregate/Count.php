<?php
declare(strict_types=1);

namespace ScoutingNL\Salesforce\Soql\Column\Func\Aggregate;

final readonly class Count extends AggregateFunction
{
    public function __construct(?string $column = null, ?string $alias = null)
    {
        parent::__construct('COUNT', $column, $alias);
    }
}
