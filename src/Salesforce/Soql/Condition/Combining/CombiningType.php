<?php
declare(strict_types=1);

namespace App\Salesforce\Soql\Condition\Combining;

enum CombiningType
{
    case AND;
    case OR;
}
