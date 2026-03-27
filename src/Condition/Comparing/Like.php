<?php
declare(strict_types=1);

namespace ScoutingNL\Salesforce\Soql\Condition\Comparing;

use ScoutingNL\Salesforce\Soql\Condition\Condition;

class Like extends Condition
{
    public function __construct(
        private string $column,
        private string|\Stringable $value,
        private bool $negate = false,
    ) {
    }

    #[\Override]
    public function toString(): string
    {
        $value = $this->escapeValue($this->value);
        $expr = "{$this->column} LIKE {$value}";

        if ($this->negate) {
            return "(NOT {$expr})";
        }

        return $expr;
    }
}
