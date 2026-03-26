<?php
declare(strict_types=1);

namespace ScoutingNL\Salesforce\Soql\Column\Func;

use ScoutingNL\Salesforce\Soql\Column\Column;

abstract readonly class Func implements Column
{
    /**
     * @param non-empty-string $function
     * @param non-empty-string|null $column
     * @param non-empty-string|null $alias
     */
    public function __construct(private string $function, private ?string $column = null, private ?string $alias = null)
    {
    }

    #[\Override]
    public function format(): string
    {
        return $this->function . '(' . ($this->column ?? '') . ')' . ($this->alias !== null ? " {$this->alias}" : '');
    }
}
