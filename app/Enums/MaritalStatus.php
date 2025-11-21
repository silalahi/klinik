<?php

namespace App\Enums;

enum MaritalStatus: string
{
    case Single = 'single';
    case Married = 'married';
    case Divorced = 'divorced';
    case Widowed = 'widowed';

    public function label(): string
    {
        return match ($this) {
            self::Single => __('Single'),
            self::Married => __('Married'),
            self::Divorced => __('Divorced'),
            self::Widowed => __('Widowed'),
        };
    }
}
