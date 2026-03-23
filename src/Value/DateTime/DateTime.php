<?php
declare(strict_types=1);

namespace ScoutingNL\Salesforce\Soql\Value\DateTime;

use ScoutingNL\Salesforce\Soql\Value\Value;

class DateTime implements Value
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
