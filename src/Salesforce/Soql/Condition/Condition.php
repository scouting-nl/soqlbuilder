<?php
declare(strict_types=1);

namespace App\Salesforce\Soql\Condition;

use App\Salesforce\Soql\Column\Column;

abstract class Condition implements \Stringable
{
    protected function escapeLiteral(string|Column|\Stringable|int|\UnitEnum|bool|null $value): string
    {
        if ($value instanceof Column) {
            return $value->format();
        }

        if ($value instanceof \BackedEnum) {
            $value = $value->value;
        } elseif ($value instanceof \UnitEnum) {
            $value = $value->name;
        }

        if (\is_string($value) || $value instanceof \Stringable) {
            return "'" . \str_replace("'", "\\'", (string)$value) . "'";
        }

        if (\is_bool($value)) {
            return $value ? 'TRUE' : 'FALSE';
        }

        if ($value === null) {
            return 'NULL';
        }

        return (string)$value;
    }
}
