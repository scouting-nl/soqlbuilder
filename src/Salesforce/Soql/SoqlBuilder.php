<?php

namespace App\Salesforce\Soql;

use App\Salesforce\Soql\Condition\Combining\_And;
use App\Salesforce\Soql\Condition\Condition;

final readonly class SoqlBuilder implements \Stringable
{
    private function __construct(
        /** @var non-empty-list<string|self> */
        private array $columns = [],
        private ?string $object = null,
        private array $conditions = [],
    ) {
    }

    public static function select(string|self $column, string|self ...$columns): self
    {
        return new self([$column, ...$columns]);
    }

    public function addSelect(string|self $column, string|self ...$columns): self
    {
        return new self(\array_merge($this->columns, [$column], $columns), $this->object, $this->conditions);
    }

    public function from(string $object): self
    {
        return new self($this->columns, $object, $this->conditions);
    }

    public function where(Condition $condition, Condition ...$conditions): self
    {
        return new self($this->columns, $this->object, [$condition, ...$conditions]);
    }

    public function andWhere(Condition $condition, Condition ...$conditions): self
    {
        return new self($this->columns, $this->object, \array_merge($this->conditions, [$condition], $conditions));
    }

    public function toSoql(): string
    {
        return (string)$this;
    }

    public function __toString(): string
    {
        if ($this->object === null || $this->object === '') {
            throw new \RuntimeException('Must select from an object');
        }

        $elements = [
            'SELECT ' . \implode(
                ', ',
                \array_map(
                    static fn (string|self $column) => $column instanceof self ? "({$column})" : $column,
                    $this->columns,
                ),
            ),
            "FROM {$this->object}",
        ];

        if ($this->conditions) {
            $elements[] = 'WHERE ' . (new _And(...$this->conditions));
        }

        return \implode("\n", $elements);
    }
}
