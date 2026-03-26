<?php
declare(strict_types=1);

namespace ScoutingNL\Salesforce\Soql;

use ScoutingNL\Salesforce\Soql\Column\Column;
use ScoutingNL\Salesforce\Soql\Column\Fields;
use ScoutingNL\Salesforce\Soql\Column\Func\Aggregate\AggregateFunction;
use ScoutingNL\Salesforce\Soql\Condition\Condition;
use ScoutingNL\Salesforce\Soql\Exception\InvalidArgumentException;
use ScoutingNL\Salesforce\Soql\Exception\RuntimeException;

/**
 * @phpstan-type TColumn non-empty-string|Column|self
 */
final readonly class SoqlBuilder implements \Stringable
{
    public const int MAX_OFFSET = 2000;
    public const int MAX_QUERY_LENGTH = 100_000;
    public const int MAX_LIMIT_FOR_FIELDS = 200;

    /**
     * @param non-empty-string $object
     * @param list<TColumn> $columns
     * @param list<Condition> $conditions
     * @param list<string> $groupBy
     * @param positive-int|null $limit
     * @param positive-int|null $offset
     */
    private function __construct(
        private string $object,
        private array $columns = [],
        private array $conditions = [],
        private array $groupBy = [],
        private ?int $limit = null,
        private ?int $offset = null,
    ) {
    }

    /**
     * @param list<TColumn>|null $columns
     * @param list<Condition>|null $conditions
     * @param list<string>|null $groupBy
     * @param positive-int|null $limit
     * @param positive-int|null $offset
     */
    private function new(
        ?array $columns = null,
        ?array $conditions = null,
        ?array $groupBy = null,
        ?int $limit = null,
        ?int $offset = null,
    ): self {
        return new self(
            $this->object,
            $columns ?? $this->columns,
            $conditions ?? $this->conditions,
            $groupBy ?? $this->groupBy,
            $limit ?? $this->limit,
            $offset ?? $this->offset,
        );
    }

    public static function select(string $object): self
    {
        if ($object === '') {
            throw new RuntimeException('Object cannot be empty');
        }

        return new self($object);
    }

    /**
     * @param TColumn $column
     * @param TColumn ...$columns
     */
    public function columns(string|Column|self $column, string|Column|self ...$columns): self
    {
        return $this->new(columns: [$column, ...\array_values($columns)]);
    }

    /**
     * @param TColumn $column
     * @param TColumn ...$columns
     */
    public function addColumns(string|Column|self $column, string|Column|self ...$columns): self
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

    public function groupBy(string $groupBy, string ...$additionalGroupBy): self
    {
        return $this->new(groupBy: [$groupBy, ...\array_values($additionalGroupBy)]);
    }

    public function addGroupBy(string $groupBy, string ...$additionalGroupBy): self
    {
        return $this->new(groupBy: \array_merge($this->groupBy, [$groupBy], \array_values($additionalGroupBy)));
    }

    /**
     * @param positive-int $limit
     */
    public function limit(int $limit): self
    {
        return $this->new(limit: $limit);
    }

    /**
     * @param positive-int $offset
     */
    public function offset(int $offset): self
    {
        if ($offset > self::MAX_OFFSET) {
            throw new InvalidArgumentException('Offset must be less than or equal to ' . self::MAX_OFFSET);
        }

        return $this->new(offset: $offset);
    }

    #[\Override]
    public function __toString(): string
    {
        if (!$this->columns) {
            throw new RuntimeException('Must select at least one column');
        }

        $hasFieldsColumns = false;
        $hasAggregateFunctions = false;

        $elements = [
            'SELECT ' . \implode(
                ', ',
                \array_map(
                    static function (string|Column|self $column) use (&$hasFieldsColumns, &$hasAggregateFunctions) {
                        if ($column instanceof self) {
                            return "({$column})";
                        }

                        $hasFieldsColumns |= $column instanceof Fields;
                        $hasAggregateFunctions |= $column instanceof AggregateFunction;

                        if ($column instanceof Column) {
                            return $column->format();
                        }

                        return $column;
                    },
                    $this->columns,
                ),
            ),
            "FROM {$this->object}",
        ];

        if ($this->conditions) {
            $elements[] = 'WHERE ' . Where::and(...$this->conditions);
        }

        if ($this->groupBy) {
            $elements[] = 'GROUP BY ' . \implode(', ', $this->groupBy);
        }

        if ($this->limit !== null) {
            if ($hasFieldsColumns && $this->limit > self::MAX_LIMIT_FOR_FIELDS) {
                throw new RuntimeException('LIMIT must be less than ' . self::MAX_LIMIT_FOR_FIELDS . ' when using the FIELDS() function');
            }

            if ($hasAggregateFunctions && !$this->groupBy) {
                throw new RuntimeException('LIMIT is not allowed when using aggregate functions without a GROUP BY');
            }

            $elements[] = "LIMIT {$this->limit}";
        } elseif ($hasFieldsColumns) {
            throw new RuntimeException('LIMIT is required when using the FIELDS() function');
        }

        if ($this->offset !== null) {
            $elements[] = "OFFSET {$this->offset}";
        }

        $query = \implode("\n", $elements);

        if (\mb_strlen($query) > self::MAX_QUERY_LENGTH) {
            throw new RuntimeException('Query is too long');
        }

        return $query;
    }
}
