<?php
declare(strict_types=1);

namespace ScoutingNL\Salesforce\Soql\Value\DateTime;

enum DateUnit
{
    case DAY;
    case FISCAL_QUARTER;
    case FISCAL_YEAR;
    case MONTH;
    case QUARTER;
    case WEEK;
    case YEAR;
}
