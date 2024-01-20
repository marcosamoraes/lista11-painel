<?php

namespace App\Http\Enums;

enum OrderStatusEnum: string
{
    case Accomplished = 'accomplished';
    case Cancelled = 'cancelled';
    case Opened = 'opened';
    case NotRenewed = 'not_renewed';
}
