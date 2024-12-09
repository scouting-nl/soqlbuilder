<?php
declare(strict_types=1);

namespace App\Salesforce\Soql\Condition\Comparing;

use App\Salesforce\Soql\Condition\Condition;

class Like extends Condition
{
    public function __construct(
        private string $column,
        private string|\Stringable $value,
        private bool $negate = false,
    ) {
    }

    public function __toString(): string
    {
        $value = $this->escapeLiteral($this->value);
        $expr = "{$this->column} LIKE {$value}";

        if ($this->negate) {
            return "(NOT {$expr})";
        }

        return $expr;
    }
}
