<?php
declare(strict_types=1);

namespace App\Salesforce\Soql;

use App\Salesforce\Soql\Condition\Combining\_And;
use App\Salesforce\Soql\Condition\Condition;

final readonly class SoqlBuilder implements \Stringable
{
    private function __construct(
        /** @var non-empty-string */
        private string $object,
        /** @var list<string|self> */
        private array $columns = [],
        /** @var list<Condition> */
        private array $conditions = [],
    ) {
    }

    public static function select(string $object): self
    {
        if ($object === '') {
            throw new \RuntimeException('Object cannot be empty');
        }

        return new self($object);
    }

    public function columns(string|self $column, string|self ...$columns): self
    {
        return new self($this->object, [$column, ...\array_values($columns)]);
    }

    public function addColumns(string|self $column, string|self ...$columns): self
    {
        return new self(
            $this->object,
            \array_merge($this->columns, [$column], \array_values($columns)),
            $this->conditions,
        );
    }

    public function where(Condition $condition, Condition ...$conditions): self
    {
        return new self($this->object, $this->columns, [$condition, ...\array_values($conditions)]);
    }

    public function andWhere(Condition $condition, Condition ...$conditions): self
    {
        return new self(
            $this->object,
            $this->columns,
            \array_merge($this->conditions, [$condition], \array_values($conditions)),
        );
    }

    public function toSoql(): string
    {
        return (string)$this;
    }

    public function __toString(): string
    {
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
