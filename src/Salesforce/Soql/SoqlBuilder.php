<?php

namespace App\Salesforce\Soql;

final readonly class SoqlBuilder implements \Stringable
{
    private function __construct(
        /** @var non-empty-list<string|self> */
        private array $columns = [],
        private ?string $object = null,
    ) {
    }

    public static function select(string|self $column, string|self ...$columns): self
    {
        return new self([$column, ...$columns]);
    }

    public function addSelect(string|self ...$columns): self
    {
        return new self(\array_merge($this->columns, $columns), $this->object);
    }

    public function from(string $object): self
    {
        return new self($this->columns, $object);
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

        return \sprintf(
            <<<'SOQL'
            SELECT
                %s
            FROM %s
            SOQL,
            \implode(
                ",\n    ",
                \array_map(
                    static fn (string|self $column) => $column instanceof self ? "({$column})" : $column,
                    $this->columns,
                ),
            ),
            $this->object,
        );
    }
}
