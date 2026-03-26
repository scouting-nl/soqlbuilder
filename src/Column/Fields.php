<?php
declare(strict_types=1);

namespace ScoutingNL\Salesforce\Soql\Column;

enum Fields implements Column
{
    case ALL;
    case CUSTOM;
    case STANDARD;

    #[\Override]
    public function format(): string
    {
        return "FIELDS({$this->name})";
    }
}
