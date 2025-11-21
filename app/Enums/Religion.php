<?php

namespace App\Enums;

enum Religion: string
{
    case Islam = 'islam';
    case Protestant = 'protestant';
    case Catholic = 'catholic';
    case Hindu = 'hindu';
    case Buddhist = 'buddha';
    case Confucianism = 'confucianism';

    public function label(): string
    {
        return match ($this) {
            self::Islam => __('Islam'),
            self::Protestant => __('Protestants'),
            self::Catholic => __('Catholic'),
            self::Hindu => __('Hindu'),
            self::Buddhist => __('Buddhist'),
            self::Confucianism => __('Confucianism'),
        };
    }
}
