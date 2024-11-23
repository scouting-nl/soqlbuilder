<?php
declare(strict_types=1);

namespace App\Salesforce\Soql\Condition\Comparing;

enum CompareOperator: string
{
    case EQUALS = '=';
    case NOT_EQUALS = '!=';
    case GREATER = '>';
    case GREATER_EQUALS = '>=';
    case LESS = '<';
    case LESS_EQUALS = '<=';

    public function allowNull(): bool
    {
        return \in_array($this, [self::EQUALS, self::NOT_EQUALS], true);
    }

    public function allowBoolean(): bool
    {
        return \in_array($this, [self::EQUALS, self::NOT_EQUALS], true);
    }
}
