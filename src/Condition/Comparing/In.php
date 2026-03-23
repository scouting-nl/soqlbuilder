<?php
declare(strict_types=1);

namespace ScoutingNL\Salesforce\Soql\Condition\Comparing;

use ScoutingNL\Salesforce\Soql\Condition\Condition;
use ScoutingNL\Salesforce\Soql\SoqlBuilder;
use ScoutingNL\Salesforce\Soql\Value\Value;

class In extends Condition
{
    /**
     * @param SoqlBuilder|list<string|Value|\Stringable|int|\UnitEnum|bool|null> $value
     */
    public function __construct(
        private string $column,
        private SoqlBuilder|array $value,
        private bool $negate = false,
    ) {
        if (\is_array($this->value) && \count($this->value) === 0) {
            throw new \RuntimeException('In must have at least one value');
        }
    }

    #[\Override]
    public function toString(): string
    {
        if (\is_array($this->value)) {
            $value = \implode(', ', \array_map($this->escapeLiteral(...), $this->value));
        } else {
            $value = (string)$this->value;
        }

        $operator = $this->negate ? 'NOT IN' : 'IN';

        return "{$this->column} {$operator} ({$value})";
    }
}
