<?php
declare(strict_types=1);

namespace App\Salesforce\Soql\Condition\Combining;

use App\Salesforce\Soql\Condition\Condition;

class _Or extends CombiningCondition
{
    public function __construct(Condition $condition, Condition ...$conditions)
    {
        parent::__construct(CombiningType::OR, $condition, ...$conditions);
    }
}
