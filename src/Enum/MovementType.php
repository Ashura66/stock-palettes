<?php

namespace App\Enum;

enum MovementType: string
{
    case IN = 'IN';
    case OUT = 'OUT';
    case ADJUSTMENT = 'ADJUSTMENT';
    case WASTE = 'WASTE';

    public function label(): string
    {
        return match ($this) {
            self::IN => 'Entrée',
            self::OUT => 'Sortie',
            self::ADJUSTMENT => 'Ajustement',
            self::WASTE => 'Déchet',
        };
    }
}
