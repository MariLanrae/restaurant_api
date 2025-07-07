<?php

namespace App\Enums;

enum RoleEnum: string
{
    case Super_Admin = 'Super Admin';
    case Admin = 'admin';
    case Waiter = 'waiter';
}
