<?php

namespace App\Enums;

enum Religion: string
{
    case Islam = 'islam';
    case Protestant = 'kristen';
    case Catholic = 'katolik';
    case Hindu = 'hindu';
    case Buddhist = 'buddha';
    case Confucianism = 'konghucu';

    public function label(): string
    {
        return match ($this) {
            self::Islam => 'Islam',
            self::Protestant => 'Kristen',
            self::Catholic => 'Katolik',
            self::Hindu => 'Hindu',
            self::Buddhist => 'Buddha',
            self::Confucianism => 'Konghucu',
        };
    }
}
