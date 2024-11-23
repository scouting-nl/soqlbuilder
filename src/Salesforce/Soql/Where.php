<?php
declare(strict_types=1);

namespace App\Salesforce\Soql;

use App\Salesforce\Soql\Condition\Combining\_And;
use App\Salesforce\Soql\Condition\Combining\_Or;
use App\Salesforce\Soql\Condition\Comparing\Compare;
use App\Salesforce\Soql\Condition\Comparing\CompareOperator;
use App\Salesforce\Soql\Condition\Comparing\In;
use App\Salesforce\Soql\Condition\Condition;

final readonly class Where
{
    public static function equals(string $column, string|int|\UnitEnum|bool|null $value): Compare
    {
        return new Compare($column, CompareOperator::EQUALS, $value);
    }

    public static function notEquals(string $column, string|int|\UnitEnum|bool|null $value): Compare
    {
        return new Compare($column, CompareOperator::NOT_EQUALS, $value);
    }

    public static function greater(string $column, string|int|\UnitEnum $value): Compare
    {
        return new Compare($column, CompareOperator::GREATER, $value);
    }

    public static function greaterEqual(string $column, string|int|\UnitEnum $value): Compare
    {
        return new Compare($column, CompareOperator::GREATER_EQUALS, $value);
    }

    public static function less(string $column, string|int|\UnitEnum $value): Compare
    {
        return new Compare($column, CompareOperator::LESS, $value);
    }

    public static function lessEquals(string $column, string|int|\UnitEnum $value): Compare
    {
        return new Compare($column, CompareOperator::LESS_EQUALS, $value);
    }

    /**
     * @param SoqlBuilder|non-empty-list<string|int|\UnitEnum|bool|null> $value
     */
    public static function in(string $column, SoqlBuilder|array $value): In
    {
        return new In($column, $value);
    }

    /**
     * @param SoqlBuilder|non-empty-list<string|int|\UnitEnum|bool|null> $value
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
