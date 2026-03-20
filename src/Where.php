<?php
declare(strict_types=1);

namespace ScoutingNL\Salesforce\Soql;

use ScoutingNL\Salesforce\Soql\Column\Column;
use ScoutingNL\Salesforce\Soql\Condition\Combining\_And;
use ScoutingNL\Salesforce\Soql\Condition\Combining\_Or;
use ScoutingNL\Salesforce\Soql\Condition\Comparing\Compare;
use ScoutingNL\Salesforce\Soql\Condition\Comparing\CompareOperator;
use ScoutingNL\Salesforce\Soql\Condition\Comparing\In;
use ScoutingNL\Salesforce\Soql\Condition\Comparing\Like;
use ScoutingNL\Salesforce\Soql\Condition\Condition;

final readonly class Where
{
    public static function equals(string $column, string|Column|\Stringable|int|\UnitEnum|bool|null $value): Compare
    {
        return new Compare($column, CompareOperator::EQUALS, $value);
    }

    public static function notEquals(string $column, string|Column|\Stringable|int|\UnitEnum|bool|null $value): Compare
    {
        return new Compare($column, CompareOperator::NOT_EQUALS, $value);
    }

    public static function greater(string $column, string|Column|\Stringable|int|\UnitEnum $value): Compare
    {
        return new Compare($column, CompareOperator::GREATER, $value);
    }

    public static function greaterEqual(string $column, string|Column|\Stringable|int|\UnitEnum $value): Compare
    {
        return new Compare($column, CompareOperator::GREATER_EQUALS, $value);
    }

    public static function less(string $column, string|Column|\Stringable|int|\UnitEnum $value): Compare
    {
        return new Compare($column, CompareOperator::LESS, $value);
    }

    public static function lessEquals(string $column, string|Column|\Stringable|int|\UnitEnum $value): Compare
    {
        return new Compare($column, CompareOperator::LESS_EQUALS, $value);
    }

    public static function like(string $column, string|\Stringable $value): Like
    {
        return new Like($column, $value);
    }

    public static function notLike(string $column, string|\Stringable $value): Like
    {
        return new Like($column, $value, true);
    }

    /**
     * @param SoqlBuilder|non-empty-list<string|Column|\Stringable|int|\UnitEnum|bool|null> $value
     */
    public static function in(string $column, SoqlBuilder|array $value): In
    {
        return new In($column, $value);
    }

    /**
     * @param SoqlBuilder|non-empty-list<string|Column|\Stringable|int|\UnitEnum|bool|null> $value
     */
    public static function notIn(string $column, SoqlBuilder|array $value): In
    {
        return new In($column, $value, negate: true);
    }

    public static function andX(Condition $condition, Condition ...$conditions): _And
    {
        return new _And($condition, ...$conditions);
    }

    public static function orX(Condition $condition, Condition ...$conditions): _Or
    {
        return new _Or($condition, ...$conditions);
    }
}
