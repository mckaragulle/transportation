<?php

namespace App\Enum;

enum StatusEnum: string
{
    case PASSIVE = 'passive';
    case ACTIVE = 'active';

    public static function getText($arguments)
    {
        return match ($arguments) {
            StatusEnum::PASSIVE => '0',
            StatusEnum::ACTIVE => 'Aktif',
        };
    }

}
