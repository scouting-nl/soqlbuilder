<?php

namespace App\Salesforce\Soql\Condition;

abstract class Condition implements \Stringable
{
    protected function escapeLiteral(string|int|\UnitEnum|bool|null $value): string
    {
        if ($value instanceof \BackedEnum) {
            $value = $value->value;
        } elseif ($value instanceof \UnitEnum) {
            $value = $value->name;
        }

        if (\is_string($value)) {
            return "'" . \str_replace("'", "\\'", $value) . "'";
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
