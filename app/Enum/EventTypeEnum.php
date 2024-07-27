<?php

namespace App\Enum;

enum EventTypeEnum: string
{
    // Log Name
    case NOTIFY = 'notify';

    case ACTION = 'action';

    case SYSTEM = 'system';

    // Log Name End


    // Event
    case CREATE_ADMIN = 'create_admin';
}
