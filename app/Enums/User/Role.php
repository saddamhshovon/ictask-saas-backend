<?php

namespace App\Enums\User;

use App\Traits\Enum\Enumerrayble;

enum Role: string
{
    use Enumerrayble;
    case Admin = 'admin';
    case Tenant = 'tenant';
}
