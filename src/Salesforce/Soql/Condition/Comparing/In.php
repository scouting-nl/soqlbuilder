<?php
declare(strict_types=1);

namespace App\Salesforce\Soql\Condition\Comparing;

use App\Salesforce\Soql\Column\Column;
use App\Salesforce\Soql\Condition\Condition;
use App\Salesforce\Soql\SoqlBuilder;

class In extends Condition
{
    /**
     * @param SoqlBuilder|list<string|Column|\Stringable|int|\UnitEnum|bool|null> $value
     */
    public function __construct(private string $column, private SoqlBuilder|array $value, private bool $negate = false)
    {
        if (\is_array($this->value) && \count($this->value) === 0) {
            throw new \RuntimeException('In must have at least one value');
        }
    }

    public function __toString(): string
    {
        if ($this->value instanceof SoqlBuilder) {
            $value = (string)$this->value;
        } else {
            $value = \implode(
                ', ',
                \array_map($this->escapeLiteral(...), $this->value),
            );
        }

        $operator = $this->negate ? 'NOT IN' : 'IN';

        return "{$this->column} {$operator} ({$value})";
    }
}
