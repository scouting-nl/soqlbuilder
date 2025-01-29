<?php
declare(strict_types=1);

namespace App\Salesforce\Soql;

use App\Salesforce\Soql\Condition\Combining\_And;
use App\Salesforce\Soql\Condition\Condition;

final readonly class SoqlBuilder implements \Stringable
{
    public const int MAX_OFFSET = 2000;
    public const int MAX_QUERY_LENGTH = 100_000;

    private function __construct(
        /** @var non-empty-string */
        private string $object,
        /** @var list<string|self> */
        private array $columns = [],
        /** @var list<Condition> */
        private array $conditions = [],
        private ?int $limit = null,
        private ?int $offset = null,
    ) {
    }

    /**
     * @param list<string|self>|null $columns
     * @param list<Condition>|null $conditions
     */
    private function new(
        ?array $columns = null,
        ?array $conditions = null,
        ?int $limit = null,
        ?int $offset = null,
    ): self {
        return new self(
            $this->object,
            $columns ?? $this->columns,
            $conditions ?? $this->conditions,
            $limit ?? $this->limit,
            $offset ?? $this->offset,
        );
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
        return $this->new(columns: [$column, ...\array_values($columns)]);
    }

    public function addColumns(string|self $column, string|self ...$columns): self
    {
        return $this->new(columns: \array_merge($this->columns, [$column], \array_values($columns)));
    }

    public function where(Condition $condition, Condition ...$conditions): self
    {
        return $this->new(conditions: [$condition, ...\array_values($conditions)]);
    }

    public function andWhere(Condition $condition, Condition ...$conditions): self
    {
        return $this->new(conditions: \array_merge($this->conditions, [$condition], \array_values($conditions)));
    }

    public function limit(int $limit): self
    {
        return $this->new(limit: $limit);
    }

    public function offset(int $offset): self
    {
        if ($offset > self::MAX_OFFSET) {
            throw new \InvalidArgumentException('Offset must be less than or equal to ' . self::MAX_OFFSET);
        }

        return $this->new(offset: $offset);
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

        if ($this->limit !== null) {
            $elements[] = "LIMIT {$this->limit}";
        }

        if ($this->offset !== null) {
            $elements[] = "OFFSET {$this->offset}";
        }

        $query = \implode("\n", $elements);

        if (\mb_strlen($query) > self::MAX_QUERY_LENGTH) {
            throw new \RuntimeException('Query is too long');
        }

        return $query;
    }
}
