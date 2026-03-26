<?php
declare(strict_types=1);

namespace ScoutingNL\Salesforce\Soql\Column\Func\Aggregate;

use ScoutingNL\Salesforce\Soql\Column\Func\Func;

final readonly class Max extends Func
{
    public function __construct(string $column, ?string $alias = null)
    {
        parent::__construct('MAX', $column, $alias);
    }
}
