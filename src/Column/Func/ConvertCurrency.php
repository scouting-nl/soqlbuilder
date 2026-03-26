<?php
declare(strict_types=1);

namespace ScoutingNL\Salesforce\Soql\Column\Func;

final readonly class ConvertCurrency extends Func
{
    public function __construct(string $column, ?string $alias = null)
    {
        parent::__construct('convertCurrency', $column, $alias);
    }
}
