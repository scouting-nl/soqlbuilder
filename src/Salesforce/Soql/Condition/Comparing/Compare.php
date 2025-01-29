<?php
declare(strict_types=1);

namespace App\Salesforce\Soql\Condition\Comparing;

use App\Salesforce\Soql\Column\Column;
use App\Salesforce\Soql\Condition\Condition;

class Compare extends Condition
{
    public function __construct(
        private string $column,
        private CompareOperator $operator,
        private string|Column|\Stringable|int|\UnitEnum|bool|null $value,
    ) {
        if ($this->value === null && !$this->operator->allowNull()) {
            throw new \RuntimeException('NULL is only allowed for equal or not equal comparison');
        }

        if (\is_bool($this->value) && !$this->operator->allowBoolean()) {
            throw new \RuntimeException('Booleans are only allowed for equal or not equal comparison');
        }
    }

    public function toString(): string
    {
        $value = $this->escapeLiteral($this->value);
        return "{$this->column} {$this->operator->value} {$value}";
    }
}
