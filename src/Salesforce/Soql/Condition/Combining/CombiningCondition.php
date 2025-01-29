<?php
declare(strict_types=1);

namespace App\Salesforce\Soql\Condition\Combining;

use App\Salesforce\Soql\Condition\Condition;

abstract class CombiningCondition extends Condition
{
    /** @var non-empty-list<Condition> */
    private array $conditions;

    public function __construct(private CombiningType $type, Condition $condition, Condition ...$conditions)
    {
        $this->conditions = [$condition, ...\array_values($conditions)];
    }

    public function toString(): string
    {
        return \implode(
            "\n{$this->type->name} ",
            \array_map(
                static fn (Condition $condition) => $condition instanceof self ? "({$condition})" : (string)$condition,
                $this->conditions,
            ),
        );
    }

    public function __toString(): string
    {
        return $this->toString();
    }
}
