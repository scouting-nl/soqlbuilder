<?php
declare(strict_types=1);

namespace ScoutingNL\Salesforce\Soql\Column;

class DateTime implements Column
{
    public function __construct(private \DateTimeInterface $dateTime)
    {
    }

    #[\Override]
    public function format(): string
    {
        return $this->dateTime->format('Y-m-d\TH:i:sP');
    }
}
