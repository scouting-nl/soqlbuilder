<?php
declare(strict_types=1);

namespace ScoutingNL\Salesforce\Soql\Column;

class Date implements Column
{
    public function __construct(private \DateTimeInterface $dateTime)
    {
    }

    public function format(): string
    {
        return $this->dateTime->format('Y-m-d');
    }
}
