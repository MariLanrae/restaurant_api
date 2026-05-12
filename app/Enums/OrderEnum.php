<?php

namespace App\Enums;

enum OrderEnum: string
{
    case CLOSED= 'closed';
    case OPEN = 'open';
    case CANCELED = 'canceled';
    case PAID = 'paid';

}
