<?php
declare(strict_types=1);

namespace ScoutingNL\Salesforce\Soql\Condition\Comparing;

use ScoutingNL\Salesforce\Soql\Column\Func\Date\DateFunction;
use ScoutingNL\Salesforce\Soql\Condition\Condition;
use ScoutingNL\Salesforce\Soql\Exception\InvalidArgumentException;
use ScoutingNL\Salesforce\Soql\Exception\RuntimeException;
use ScoutingNL\Salesforce\Soql\SoqlBuilder;
use ScoutingNL\Salesforce\Soql\Value\DateTime\DateLiteral;
use ScoutingNL\Salesforce\Soql\Value\Value;

class In extends Condition
{
    /**
     * @param SoqlBuilder|list<string|Value|\Stringable|int|\UnitEnum|bool|null> $value
     */
    public function __construct(
        private string|DateFunction $column,
        private SoqlBuilder|array $value,
        private bool $negate = false,
    ) {
        if (\is_array($this->value)) {
            if (\count($this->value) === 0) {
                throw new RuntimeException('In must have at least one value');
            }

            if ($this->column instanceof DateFunction) {
                foreach ($this->value as $item) {
                    if ($item instanceof DateLiteral) {
                        throw new InvalidArgumentException('Cannot compare a date function with a date literal');
                    }
                }
            }
        }
    }

    #[\Override]
    public function toString(): string
    {
        if (\is_array($this->value)) {
            $value = \implode(', ', \array_map($this->escapeValue(...), $this->value));
        } else {
            $value = (string)$this->value;
        }

        $operator = $this->negate ? 'NOT IN' : 'IN';

        return "{$this->escapeColumn($this->column)} {$operator} ({$value})";
    }
}
