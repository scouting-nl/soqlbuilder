<?php
declare(strict_types=1);

namespace ScoutingNL\Salesforce\Soql\Condition\Combining;

enum CombiningType
{
    case AND;
    case OR;
}
