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
        $operator = $this->negate ? 'NOT LIKE' : 'LIKE';
        $value = $this->escapeLiteral($this->value);
        return "{$this->column} {$operator} {$value}";
    }
}
