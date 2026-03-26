<?php
declare(strict_types=1);

namespace ScoutingNL\Salesforce\Soql\Column;

enum Fields
{
    case ALL;
    case CUSTOM;
    case STANDARD;

    public function format(): string
    {
        return "FIELDS({$this->name})";
    }
}
