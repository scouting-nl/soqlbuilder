<?php
declare(strict_types=1);

namespace ScoutingNL\Salesforce\Soql\Condition;

use ScoutingNL\Salesforce\Soql\Exception\RuntimeException;
use ScoutingNL\Salesforce\Soql\Value\Value;

abstract class Condition implements \Stringable
{
    public const int MAX_CONDITION_LENGTH = 4000;

    protected function escapeLiteral(string|Value|\Stringable|int|\UnitEnum|bool|null $value): string
    {
        if ($value instanceof Value) {
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

    abstract public function toString(): string;

    #[\Override]
    public function __toString(): string
    {
        $string = $this->toString();

        if (\mb_strlen($string) > self::MAX_CONDITION_LENGTH) {
            throw new RuntimeException('Condition is too long');
        }

        return $string;
    }
}
