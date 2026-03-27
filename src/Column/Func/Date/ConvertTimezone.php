<?php
declare(strict_types=1);

namespace ScoutingNL\Salesforce\Soql\Column\Func\Date;

final readonly class ConvertTimezone
{
    public function __construct(public string $column)
    {
    }
}
