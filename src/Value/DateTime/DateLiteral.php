<?php
declare(strict_types=1);

namespace ScoutingNL\Salesforce\Soql\Value\DateTime;

use ScoutingNL\Salesforce\Soql\Value\Value;

final readonly class DateLiteral implements Value
{
    private function __construct(private string $literal)
    {
    }

    public static function today(): self
    {
        return new self('TODAY');
    }

    public static function tomorrow(): self
    {
        return new self('TOMORROW');
    }

    public static function yesterday(): self
    {
        return new self('YESTERDAY');
    }

    public static function next(DateUnit $unit): self
    {
        return match ($unit) {
            DateUnit::DAY => self::tomorrow(),
            default => new self("NEXT_{$unit->name}"),
        };
    }

    public static function last(DateUnit $unit): self
    {
        return match ($unit) {
            DateUnit::DAY => self::yesterday(),
            default => new self("LAST_{$unit->name}"),
        };
    }

    public static function next90Days(): self
    {
        return new self('NEXT_90_DAYS');
    }

    public static function last90Days(): self
    {
        return new self('LAST_90_DAYS');
    }

    public static function this(DateUnit $unit): self
    {
        return match ($unit) {
            DateUnit::DAY => self::today(),
            default => new self("THIS_{$unit->name}"),
        };
    }

    /**
     * @param positive-int $units
     */
    public static function nextN(DateUnit $unit, int $units): self
    {
        return new self("NEXT_N_{$unit->name}S:{$units}");
    }

    /**
     * @param positive-int $units
     */
    public static function lastN(DateUnit $unit, int $units): self
    {
        return new self("LAST_N_{$unit->name}S:{$units}");
    }

    /**
     * @param positive-int $units
     */
    public static function nAgo(DateUnit $unit, int $units): self
    {
        return new self("N_{$unit->name}S_AGO:{$units}");
    }

    #[\Override]
    public function format(): string
    {
        return $this->literal;
    }
}
