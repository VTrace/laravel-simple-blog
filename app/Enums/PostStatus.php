<?php

namespace App\Enums;

enum PostStatus: string
{
    case Draft = 'draft';
    case Published = 'published';
    case Scheduled = 'scheduled';

    public function badgeColor(): string
    {
        return match ($this) {
            self::Draft => 'bg-gray-100 px-2 py-1 text-gray-800',
            self::Published => 'bg-green-100 px-2 py-1 text-green-800',
            self::Scheduled => 'bg-yellow-100 px-2 py-1 text-yellow-800',
        };
    }
}
