<?php

namespace App\Salesforce\Soql\Condition;

abstract class Condition implements \Stringable
{
    protected function escapeLiteral(string|int|bool|null $value): string
    {
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
