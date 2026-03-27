<?php
declare(strict_types=1);

namespace ScoutingNL\Salesforce\Soql\Condition\Comparing;

use ScoutingNL\Salesforce\Soql\Column\Func\Date\DateFunction;
use ScoutingNL\Salesforce\Soql\Condition\Condition;
use ScoutingNL\Salesforce\Soql\Exception\InvalidArgumentException;
use ScoutingNL\Salesforce\Soql\Value\DateTime\DateLiteral;
use ScoutingNL\Salesforce\Soql\Value\Value;

class Compare extends Condition
{
    public function __construct(
        private string|DateFunction $column,
        private CompareOperator $operator,
        private string|Value|\Stringable|int|\UnitEnum|bool|null $value,
    ) {
        if ($this->value === null && !$this->operator->allowNull()) {
            throw new InvalidArgumentException('NULL is only allowed for equal or not equal comparison');
        }

        if (\is_bool($this->value) && !$this->operator->allowBoolean()) {
            throw new InvalidArgumentException('Booleans are only allowed for equal or not equal comparison');
        }

        if ($this->column instanceof DateFunction && $value instanceof DateLiteral) {
            throw new InvalidArgumentException('Cannot compare a date function with a date literal');
        }
    }

    #[\Override]
    public function toString(): string
    {
        return "{$this->escapeColumn($this->column)} {$this->operator->value} {$this->escapeValue($this->value)}";
    }
}
