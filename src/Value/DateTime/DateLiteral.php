<?php
declare(strict_types=1);

namespace ScoutingNL\Salesforce\Soql\Value\DateTime;

use ScoutingNL\Salesforce\Soql\Value\Literal;
use ScoutingNL\Salesforce\Soql\Value\Value;

enum DateLiteral implements Value
{
    case LAST_90_DAYS;
    case NEXT_90_DAYS;
    case TODAY;
    case TOMORROW;
    case YESTERDAY;

    public static function next(DateUnit $unit): Value
    {
        return match ($unit) {
            DateUnit::DAY => self::TOMORROW,
            default => new Literal("NEXT_{$unit->name}"),
        };
    }

    public static function last(DateUnit $unit): Value
    {
        return match ($unit) {
            DateUnit::DAY => self::YESTERDAY,
            default => new Literal("LAST_{$unit->name}"),
        };
    }

    public static function this(DateUnit $unit): Value
    {
        return match ($unit) {
            DateUnit::DAY => self::TODAY,
            default => new Literal("THIS_{$unit->name}"),
        };
    }

    /**
     * @param positive-int $units
     */
    public static function nextN(DateUnit $unit, int $units): Literal
    {
        return new Literal("NEXT_N_{$unit->name}S:{$units}");
    }

    /**
     * @param positive-int $units
     */
    public static function lastN(DateUnit $unit, int $units): Literal
    {
        return new Literal("LAST_N_{$unit->name}S:{$units}");
    }

    /**
     * @param positive-int $units
     */
    public static function nAgo(DateUnit $unit, int $units): Literal
    {
        return new Literal("N_{$unit->name}S_AGO:{$units}");
    }

    #[\Override]
    public function format(): string
    {
        return $this->name;
    }
}
